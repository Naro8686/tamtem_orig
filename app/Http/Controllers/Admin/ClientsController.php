<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Client\ClientStoreRequest;
use App\Http\Requests\Admin\Client\ClientStoreUserRequest;
use App\Http\Requests\Admin\Client\ClientUpdateRequest;
use App\Http\Requests\Admin\Client\ClientUpdateUser;
use App\Models\LogAdmin;
use App\Models\Org\Organization;
use App\Models\Org\OrganizationDeal;
use App\Models\Org\OrganizationOffice;
use App\Models\Org\OrganizationStore;
use App\Models\ScoreDocs;
use App\Models\User;
use App\Notifications\SendScoreClient;
use App\Repositories\OrganizationRepository;
use App\Traits\ApiControllerTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Support\Facades\Hash;
use PDF;
use Mail;
use App\Services\FinanceOperation\FinanceOperationService;
use App\Models\FinanceOperation;
use App\Http\Controllers\Client\Api\v1\PaymentServicesController;
use App\Http\Controllers\Client\Api\v1\DialogsController;
use App\Models\Payment\Subscription;
use App\Models\Category;
use Illuminate\Notifications\Notifiable;



class ClientsController extends Controller
{

    use ApiControllerTrait;

     /**
    * @var FinanceOperationService
    */
    private $financeOperationService;

    private $paymentService;

    /**
    * @param FinanceOperationService $financeOperationService
    */
    public function __construct()
    {
        $this->financeOperationService = new FinanceOperationService();
        $this->paymentService = new PaymentServicesController();
    }


    /**
     * @param Request $request
     * @return array
     */
    public function index(Request $request, $type)
    {
        $validator = Validator::make($request->route()->parameters(), [
            'type' => 'in:' . Organization::ORG_TYPE_SELLER . ',' . Organization::ORG_TYPE_BUYER,
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->messages());
        }

        return Organization::filtredData($request)
            ->where('org_type', $type)
            ->with(['owner', 'city.region.country', 'legalForm'])
            ->paginate($request->input('per_page', 10));
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function show($id)
    {
        return Organization::with([
            'owner', 'users', 'offices', 'stores', 'city.region.country', 'legalForm'
        ])->findOrFail($id);
    }

    /**
     * @param ClientStoreRequest $request
     * @return array
     */
    public function store(ClientStoreRequest $request)
    {
        if ($user = OrganizationRepository::createOrganization($request))
            return [
                'organization_id' => $user->organization->id,
            ];

        return ['result' => false];
    }

    /**
     * @param ClientUpdateRequest $request
     * @param $id
     * @return mixed
     */
    public function update(ClientUpdateRequest $request, $id)
    {
        if ($request->get('org_status', 'register') != 'register') {
            if (!Auth()->user()->can('clients.moderate')) {
                $request->merge([
                    'org_status' => 'register'
                ]);
            }
        }

        $organization = Organization::findOrFail($id);
        $organization->update($request->all());
        $organization->owner->name = $request->user_name;
        $organization->owner->save();

        dispatch((new \App\Jobs\Geo\GeoCodeOrganizationAddress($organization))->onQueue('geo'));

        return $organization;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $organization = Organization::findOrFail($id);
        $organization->delete();
        return '';
    }

    /**
     * @param $id
     * @return mixed
     */
    public function moderate($id)
    {
        $organization = Organization::findOrFail($id);
        $organization->on_moderate = 0;
        $organization->save();

        return $organization;
    }

    /**
     * @param $id
     * @return string
     */
    public function deleteOffice($id)
    {
        $office = OrganizationOffice::findOrFail($id);
        $office->delete();

        return $this->successResponse();
    }

    /**
     * @param $id
     * @return string
     */
    public function deleteStore($id)
    {
        $store = OrganizationStore::findOrFail($id);
        $store->delete();

        return $this->successResponse();
    }

    /*22.03.19*/

    /**
     * @param Request $request
     *
     * Методы для работы с базой прямо тут.
     *
     * @todo если все ок при тестировании перенести в репозиторий или модель запросы.
     */
    public function storeUserClient(ClientStoreUserRequest $request)
    {

        // не даем задать категорию выше второго уровня
        $organization = $request->organization;
        
        if ($organization !== null) {

            $orgCatId = $organization['categories']['0'];

            if(Category::where('id', $orgCatId)->first()->parent === null){

                return $this->errorResponse("Категория должна быть не выже второго уровня");
                // $categorySecondLevelId = Category::CategoriesNotRoot()->first(); //id категории , не выше первого уровня
                // if($categorySecondLevelId === null){
                //     $categorySecondLevelId = 2;
                // }
                // else{
                //     $categorySecondLevelId = $categorySecondLevelId->id;
                // }

                // $organization['categories']["0"] = $categorySecondLevelId;
            }
        }

        $password = str_random(8);
        $user = new User();
        $user->name = $request->user['name'];
        $user->password = Hash::make($password);
        $user->email = mb_strtolower($request->user['email']);
        $user->phone = $request->user['phone'];
        $user->role = User::ROLE_CLIENT;
        $user->status = User::USER_STATUS_APPROVE;
        $user->unique_id = $this->generateUniqueUserIdNumber();
        $user->save();

        $newOrg = $this->createOrg($user, $request->organization);

        // новую организацию в статистику
        $statistic = $newOrg->statistic()->create(['inn' => $newOrg->org_inn]);

        // подписка на оповещения
        $tagsIds = \App\Models\Org\Channel::select('id')->pluck('id')->toArray();
        $newOrg->channels()->syncWithoutDetaching($tagsIds);

        // 
        $user->is_org_created = true;
        $user->save();

        $log = [
            'method' => __METHOD__,
            'data' => json_encode($request->all()),
            'user_id' => Auth::user()->id
        ];
        
        LogAdmin::create($log)->save();

        $user->notify(new \App\Notifications\SendClientPassword($request->user['email'], $password));

        return $this->successResponse($user);

    }


    private function createOrg($user, $organization)
    {
        $newOrg = null;

        if ($organization === null) { // MVP версия , без заполнения организации
            $newOrg = $user->company()->create();
        } else {
            $cat = $organization['categories'];
            
            $newOrg = $user->company()->create($organization);
            $newOrg->categories()->sync($cat);

        }
        $user->organization()->associate($newOrg);  // Этот метод обновит отношения belongsTo() и установит внешний ключ на дочерней модели, в частности owner_id = users.id

        $user->save();

        return $newOrg;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getAllClients(Request $request)
    {
        $query = User::workers();
        if ($request->search) {
            $query->where('email', $request->search);
        }
        if ($request->blocked == 'false') {
            $query->where('status', '<>', 'banned');
        } else {

        }
        $users = $query->orderBy('created_at', 'DESC')->paginate($request->input('per_page', 10));
        foreach ($users as $user){
            $user->makeVisible(['created_at']);
        }
        return $users;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getClient($id)
    {
        $user = User::where('id', $id)->first();
        $organization = Organization::where('owner_id', $user->id)->first();
        $organization->categories = DB::table('organizations_categories')->select('category_id')->where('organization_id', $organization->id)->get()->pluck('category_id');

        return response()->json(['user' => $user, 'organization' => $organization]);
    }

    /**
     * @param ClientUpdateUser $request
     * @param $id
     * @return array
     */
    public function updateUserClient(ClientUpdateUser $request)
    {

        $requestBody = $request->body;
        $requestOrganization = $requestBody['organization'];
        // это если дата приходит с временем - у фронтов нет времени на правку, потому пока костылик
        if(isset($requestOrganization['org_registration_date'])){
            $requestOrganization['org_registration_date'] =  explode(' ', $requestOrganization['org_registration_date'])[0];
        }

        $validatedDataOrg = Validator::make($requestOrganization, [

            'phone'             => 'sometimes|regex:/(^(\d+)$)/u|min:5|max:11',
            'org_city_id'       => 'sometimes|exists:cities,id',
            'org_name'          => 'sometimes|max:255',
            'org_inn'           => 'sometimes|min:10|max:12',
            'org_kpp'           => 'sometimes|min:9|max:9',
            'org_legal_form_id' => 'sometimes|exists:organizations_legal_forms,id',
            'org_director'      => 'sometimes|max:64',

            'org_manager_post'  => 'sometimes|min:1|max:190',
            'org_okved'         => 'sometimes|min:1|max:190',
            'org_ogrn'          => 'sometimes|min:1|max:190',
            'org_is_active'     => 'sometimes|boolean',
            'org_registration_date' => 'sometimes|date_format:"Y-m-d',
        ]);

        if ($validatedDataOrg->fails()) {
            return $this->errorResponse($validatedDataOrg->messages());
        }

        $validatedDataOrg = Validator::make($requestBody['user'], [
            'phone'             => 'sometimes|required|regex:/(^(\d+)$)/u|min:5|max:11',
        ]);

        if ($validatedDataOrg->fails()) {
            return $this->errorResponse($validatedDataOrg->messages());
        }

        User::where('id', $request->id)->update(
            [
                'name' => $requestBody['user']['name'],
                'email' => $requestBody['user']['email'],
                'phone' => $requestBody['user']['phone']
            ]
        );

        $modelOrg = Organization::find($requestOrganization['id']);
        if (isset($requestOrganization['org_address'])) $modelOrg->org_address           = $requestOrganization['org_address'];
        if (isset($requestOrganization['org_description'])) $modelOrg->org_description       = $requestOrganization['org_description'];
        if (isset($requestOrganization['org_director'])) $modelOrg->org_director          = $requestOrganization['org_director'];
        if (isset($requestOrganization['org_inn'])) $modelOrg->org_inn               = $requestOrganization['org_inn'];
        if (isset($requestOrganization['org_kpp'])) $modelOrg->org_kpp               = $requestOrganization['org_kpp'];
        if (isset($requestOrganization['org_legal_form_id'])) $modelOrg->org_legal_form_id     = $requestOrganization['org_legal_form_id'];
        if (isset($requestOrganization['org_name'])) $modelOrg->org_name              = $requestOrganization['org_name'];
        if (isset($requestOrganization['org_type'])) $modelOrg->org_type              = $requestOrganization['org_type'];
        if (isset($requestOrganization['phone'])) $modelOrg->phone                 = $requestOrganization['phone'];
        if (isset($requestOrganization['org_manager_post'])) $modelOrg->org_manager_post      = $requestOrganization['org_manager_post'];
        if (isset($requestOrganization['org_okved'])) $modelOrg->org_okved             = $requestOrganization['org_okved'];
        if (isset($requestOrganization['org_ogrn'])) $modelOrg->org_ogrn              = $requestOrganization['org_ogrn'];
        if (isset($requestOrganization['org_is_active'])) $modelOrg->org_is_active         = $requestOrganization['org_is_active'];
        if (isset($requestOrganization['org_registration_date'])) $modelOrg->org_registration_date = $requestOrganization['org_registration_date'];

        $modelOrg->save();

        if (isset($requestOrganization['categories'])) $modelOrg->categories()->sync($requestOrganization['categories']);

        $log = [
            'method' => __METHOD__,
            'data' => json_encode($request->all()),
            'user_id' => Auth::user()->id
        ];

        User::where('id', $request->id)->update(
            [
                'is_org_created' => 1
            ]
        );

        LogAdmin::create($log)->save();

        return $this->successResponse();
    }


    /**
     *
     */
    public function bannedClientUser(Request $request)
    {

        User::where('id', $request->id)->update(
            [
                'status' => $request->params['status']
            ]
        );

        $log = [
            'method' => __METHOD__,
            'data' => json_encode($request->all()),
            'user_id' => Auth::user()->id
        ];
        LogAdmin::create($log)->save();

        return $this->successResponse();
    }

    /**
     * Продублировал код 2 метода изи UserController. Знаю, что плохо))
     * @return int
     * @todo при рефакторинге вычистить
     */
    public static function generateUniqueUserIdNumber()
    {

        $number = mt_rand(1000000, 9999999); // better than rand()

        if (self::barcodeNumberExists($number)) {
            return self::generateUniqueUserIdNumber();
        }

        return $number;
    }

    public static function barcodeNumberExists($number)
    {
        return User::whereUniqueId($number)->exists();
    }


    public function generatePassword(Request $request)
    {
        $password = str_random(8);
        $user = User::where('unique_id', $request->params['unique_id'])->first();
        $user->password=bcrypt($password);
        $user->save();
        $user->notify(new \App\Notifications\SendClientPassword($user['email'], $password));

        return $this->successResponse();
    }


    /**
     * Генерация счета
     * @param Request $request
     */
    public function generateScore(Request $request)
    {

        $unique_id = $request->params['unique_id'];
        $summ = number_format($request->params['summ'], 2, '.', '');
        
        $modelOrgDeal=new OrganizationDeal();
        $fileName=$modelOrgDeal->score($unique_id, $summ,  Auth::user());

        //   // создадим запись в финансовых операциях
        //   $data = [
        //     'user_id'           => User::where('unique_id', $unique_id)->first()->id,
        //     'payment_id' 		=> substr($fileName, 0 , strripos($fileName,'.')),
        //     'amount'            => (int)$summ,
        //     'payment_system'    => FinanceOperation::PAYMENT_SYSTEM_SCORE,
        //     'status'    		=> FinanceOperation::PAYMENT_STATUS_WAITING,
        //     'text'    			=> "Попытка оплаты выпиской счета",
        //     'manager_id'    	=> Auth::user()->id,
        // ];
        // $financeOperation = $this->financeOperationService->create(Auth::user(), $data);

        // if(!($financeOperation instanceof FinanceOperation)){
        //     return $this->errorResponse($financeOperation);
        // }

        return response()->json(['result' => true, 'link' => '/' . $fileName]);
    }



    public function scoreSend (Request $request) {

       $user=User::where('unique_id', $request->params['unique_id'])->first();

       $user->notify(new \App\Notifications\SendScoreClient($request->params['usermail'], $request->params['link']));

       return $this->successResponse();
    }

    public function updateBalance (Request $request) {

        $summ =  $request->params['summ'];
        $user = User::where('unique_id', $request->params['unique_id'])->first();

        User::where('unique_id', $request->params['unique_id'])->update(
            [
                'ballance'=>$user->ballance+$summ
            ]
        );

        // создадим запись в финансовых операциях
        $rowFinanceOperation = (new \App\Services\FinanceOperation\FinanceOperationService())->storeLocalPayment($user, Auth::user(), $summ, "Пополнение кошелька администратором");

        $messageToMail = "  Ваш кошелек был успешно пополнен по безналичной оплате на сумму " . $summ . " руб. ";
        // неоплаченные сделки
        $user = User::where('unique_id', $request->params['unique_id'])->first();
        $dealsWaitingPayment = $user->getDealsWaitingPayment();
//dd($dealsWaitingPayment->get());
        // есть неоплаченные сделки
        if($dealsWaitingPayment->count() > 0 ){
            // dd( $dealsWaitingPayment->get());

            $dealsNotPayed = array();  // ссылки на неоплаченные сделки   
            $summDealsNotPayed = 0;  // сколько нужно еще внести в кошелек для оплаты
            $dealsPayed = array();      // ссылки на оплаченные сделки     

            //переберем каждую непроплаченную сделку
            foreach($dealsWaitingPayment->get() as $deal){

                $idsDealsBuyContacts =  $user->idsDealsBuyContacts(); // id объявлений с проплаченными контактами
                $commission = count($idsDealsBuyContacts) > 0 ? $deal->commission : 200;

                $user = User::where('unique_id', $request->params['unique_id'])->first();
                // попытка оплаты контактов по сделке
                $resultPayment = $this->paymentService->paymentSubscription($request, Subscription::SUBSCRIPTION_PAYMENT_ONCE_DEAL_BUY_GET_CONTACTS, true, $deal->id, $user, round($commission, 0, PHP_ROUND_HALF_DOWN));

                // проплачены ли победителем контакты сделки
                $idsDealsBuyContacts = $user->idsDealsBuyContacts(); // id объявлений с проплаченными контактами
                $isPayed = isset($idsDealsBuyContacts[$deal->id]);            

                if($isPayed){
    
                    $dealsPayed[] = "<p><a href=" . url('bids/' . $deal->id). ">" . url('bids/' . $deal->id) . "</a></p>";
       
                    // надо создать диалог по данной сделке между продавцом и покупателем и послеть первое сообщение
                    if(!$dialogId = DialogsController::newLocalDialog($deal->id, $deal->winner_id, $deal->organization_id)){
                    // return $this->errorResponse('ошибка создания диалога');
                    }
                    if(!$messageId = DialogsController::sendLocal($dialogId, $user, 'Мои контакты у Вас есть, можно обговорить детали')){
                    // return $this->errorResponse('ошибка отправки сообщения продавцу');
                    }
        
                }
                else{ // если неоплачено
                    $summDealsNotPayed += round($commission, 0, PHP_ROUND_HALF_DOWN);
                    $dealsNotPayed[] = "<p><a href=" . url('bids/' . $deal->id). ">" . url('bids/' . $deal->id) . "</a></p>";
                  
                }
            }

            // если есть оплаченные сделки
            if(count($dealsPayed)>0){
                $messageToMail .= " Вас выбрали победителем в сделках:";
                foreach($dealsPayed as $dealPayed){
                    $messageToMail .= $dealPayed;
                }
                $messageToMail .= "<br/> Доступ к контактной информации по данным сделкам был автоматически оплачен. Перейдите в личный кабинет на сайте " 
                                    . "<a href=" . url('/'). ">" . url('/') . "</a>" .  " в раздел 'Мои предложения' и статус предложения 'Завершено'";
            }

            // если есть неоплаченные сделки
            if(count($dealsNotPayed)>0){
                $messageToMail .= "<p> У вас остались не оплаченные контакты по сделкам:</p>";
                foreach($dealsNotPayed as $dealPayed){
                    $messageToMail .= $dealPayed;
                }

                $ballance =  $user = User::where('unique_id', $request->params['unique_id'])->first()->ballance;
                $messageToMail .= ", на общую сумму: " . $summDealsNotPayed . " руб.";
                $messageToMail .= "<br/> На данный момент у вас на счету: " . $ballance  . " руб.";
                $messageToMail .= "<br/> Для оплаты контактов необходимо довнести на счет: " . -($ballance - $summDealsNotPayed). " руб.";
            }

        }
        else{
            $messageToMail .= " Если Вас выберут победителем в сделках, то контакты будут автоматически оплачены.";
        }

        $messageToMail .= "<br/> Спасибо, что Вы с нами";
        $user = User::where('unique_id', $request->params['unique_id'])->first();
        $user->notify(new \App\Notifications\SendEmailAdminkaUpdateBallance($messageToMail, "Пополнение балланса"));
        
        return $this->successResponse();
    }

   /**
    * @param string $prefix
    * @return string
    */
    public function generateUniqueOrderIdValue($prefix = null) {
        
        $orderId = md5(uniqid(rand(), true));
        $orderId = ($prefix !== null) ? $prefix . "_" . $orderId : $orderId;

        if ($this->financeOperationService->isRowValueExists('payment_id', $orderId)) {
            return $this->generateUniqueOrderIdValue($prefix);
        }
 
        return $orderId;
    }
    

 


}
