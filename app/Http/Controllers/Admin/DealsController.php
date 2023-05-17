<?php

namespace App\Http\Controllers\Admin;

use App\Models\Org\ImagesDeals;
use App\Models\Org\Organization;
use App\Models\Org\OrganizationDeal;
use App\Models\Org\OrganizationDealMember;
use App\Models\ResponsesFile;
use App\Models\Payment\Subscription;
use App\Models\Payment\UserSubscription;
use App\Models\User;
use App\Notifications\SendEmailModerate;
use App\Notifications\SendEmailModerateResponse;
use App\Notifications\SendEmailModerateNewResponse;
use App\Traits\ApiControllerTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use App\Notifications\UserNewDealBuyMessage;
use App\Notifications\DealCreateToCategoriesOrgSubscription;
use App\Notifications\DealUpdateToCategoriesOrgSubscription;
use App\Notifications\SendEmailModerateDealToTagSubscription;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Http\Controllers\Admin\ClientsController;

//use App\Http\Requests\Admin\Deal\DealStoreRequest;
use App\Http\Requests\Admin\Deal\DealUpdateRequest;
use App\Services\DealsFile\DealsFileService;
use App\Models\DealsFile;
use App\Http\Requests\Client\Api\v1\DealsFile\DealsFileStoreRequest;
use App\Http\Resources\DealsFile\DealsFileResource;
use App\Http\Requests\Admin\Responses\ResponseUpdateRequest;

use App\Services\ResponsesFile\ResponsesFileService;

use App\Services\DealsQuestionsHeader\DealsQuestionsHeaderService;
use App\Services\OrganizationDealQuestion\OrganizationDealQuestionService;
use App\Formatter\Api\v1\AdminDealsItemFormatter;
use App\Formatter\Api\v1\AdminResponsesItemFormatter;
use App\Models\Category;
use App\Services\FinanceOperation\FinanceOperationService;
use App\Services\Tag\TagService;
use App\Models\FinanceOperation;


use App\Http\Controllers\Admin\TagsController;

class DealsController extends Controller
{
    use ApiControllerTrait;

    public $dealsFileService = null;
    public $responsesFileService = null;
    public $dealsQuestionsService = null;
    public $dealsQuestionsHeaderService = null;
    private $financeOperationService;

     /**
     * Create a new controller instance.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct()
    {
        $this->dealsFileService         = new DealsFileService();
        $this->responsesFileService         = new ResponsesFileService();
        $this->dealsQuestionsService    = new OrganizationDealQuestionService();
        $this->dealsQuestionsHeaderService    = new DealsQuestionsHeaderService();
        $this->financeOperationService = new FinanceOperationService();
    }

    /**
     * @param Request $request
     * @return array
     */
    public function index(Request $request)
    {

        // по дате публикации сортировка
        $request->merge([
            'sort_key' => 'published_at',
            'sort_order' => 'desc'
        ]);

        $deals = OrganizationDeal::filtredData($request, ['id', 'user_id', 'name', 'status', 'budget_from', 'budget_to', 'budget_all', 'is_fake', 'type_deal', 'finished_at', 'finish', 'organization_id', 'deadline_deal', 'created_at', 'published_at', 'planned_finish', 'manager_id', 'moderator_id']);

        if ($request->finished == 'false') {
            $deals = $deals->where('finish', 0);
        } else if ($request->finished == 'true'){
            $deals = $deals->withTrashed();
        }

        if (true === $request->has('type_deal') and in_array($request->type_deal, [OrganizationDeal::DEAL_TYPE_BUY, OrganizationDeal::DEAL_TYPE_SELL])) {
            $deals = $deals->where('type_deal', $request->type_deal);
        }

        // поиск по id сделки
        if (true === $request->has('search')) {
            $deals = $deals->where('id', '=' , (int)$request->search);
        }

        $deals = $deals->with('organization', 'categories', 'user')
                ->where('status', '<>', OrganizationDeal::DEAL_STATUS_MODERATION)
                ->where('status', '<>', OrganizationDeal::DEAL_STATUS_BANNED)
                ->orderBy('id', 'desc')
                ->paginate($request->input('per_page', 10));
        return AdminDealsItemFormatter::formatPaginator($deals);

    }


    /**
     *
     *  Показать только сделки по покупке на которые есть не отмодерированные отклики
     * @param Request $request
     * @return array
     */
    public function responseDealsIndex(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make( $input, [
            'only_new' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages());
        }

        $deals = OrganizationDeal::filtredData($request, ['id', 'user_id', 'name', 'status', 'type_deal', 'finished_at', 'finish', 'organization_id', 'deadline_deal', 'created_at', 'published_at', 'planned_finish', 'manager_id', 'moderator_id']);

        $deals = $deals->with('organization', 'categories', 'user')
                ->where('status', '<>', OrganizationDeal::DEAL_STATUS_MODERATION)
                ->where('finish', 0)
                ->where('type_deal', OrganizationDeal::DEAL_TYPE_BUY);

        $dealsresponse = [];

        foreach ( $deals->get() as  $deal) {
            $curDeal = AdminDealsItemFormatter::format($deal);

            if(isset($curDeal['count_response']) and $curDeal['count_response'] !== 0){
                // если стоит - показать только с неотмодерированными откликами
                if((boolean)$input['only_new'] === true and $curDeal['count_response_moderate'] < 1){
                    continue;
                }
                $dealsresponse[] = $curDeal;
            }
        }

        return $this->paginateCollection(collect($dealsresponse),  $request->input('per_page', 10), $request->input('page', 1), ['path' => $request->url(), 'query' => $request->query()]);

    }

    /**
     *
     *  Показать отклики по выбранной сделке
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function responsesByDealIndex(Request $request, $id)
    {

        try{
            $id = (int) $id;

            //$deal = OrganizationDeal::with(['organization', 'members', 'categories', 'cities', 'user', 'questions', 'files'])->find($id);
            $responses = OrganizationDealMember::with(['organizationWithUser'])->where('deal_id',  $id );

            if (!$responses or $responses->count() === 0){
                return $this->errorResponse("Нет участников в сделке с id = " . $id);
            }

            // показать только на модерации
            if($request->has('all') and ($request->input('all') === "true" or $request->input('all') === true) ) {
            // $responses->where('trading_status', OrganizationDeal::DEAL_TRADING_STATUS_MODERATION);
            }
            else{
                $responses->where('trading_status', OrganizationDeal::DEAL_TRADING_STATUS_MODERATION);
            }

            return $this->successResponse(AdminResponsesItemFormatter::formatPaginator($responses->paginate($request->input('per_page', 10))));

		}
		catch(Throwable $e){
			return $this->errorResponse($e->getMessage());
		}
    }

    /**
     *
     *  Показать отклик по его id
     * @param int $id
     * @return array
     */
    public function getResponse($id)
    {

        try{
            $id = (int) $id;

            $response = OrganizationDealMember::with('answers', 'files')->where('organizations_deals_members.id',  $id )->first() ;

            if (!$response){
                return $this->errorResponse("Нет отклика с id = " . $id);
            }

          //  return $response->toArray();
            return $this->successResponse(AdminResponsesItemFormatter::format($response, true));

		}
		catch(Throwable $e){
			return $this->errorResponse($e->getMessage());
		}
    }

    /**
     *  Обновить отклик по его id
     *
     * @param  ResponseUpdateRequest $request
     *
     * @return boolean
     */
    public function updateResponse(ResponseUpdateRequest $request)
    {

        try{

            $dealsMemberId = (int) $request->id;

            $dealsMember = OrganizationDealMember::find($dealsMemberId);

            if(!$dealsMember){
				return $this->errorResponse("Нет участника с id = " .  $dealsMemberId);
            }

            $price_offer = (true === $request->has('price_offer')) ? $request->price_offer :  $dealsMember->price_offer;
            $notice = (true === $request->has('notice')) ?  ($request->get('notice') ?? "") : $dealsMember->notice;
            $is_shipping_included = (true === $request->has('is_shipping_included')) ? $request->is_shipping_included : $dealsMember->is_shipping_included;

            $result = $dealsMember->update(
                [
                    'price_offer' => $price_offer,
                    'notice' => $notice,
                    'is_shipping_included' => $is_shipping_included,
                ]
            );

            $deal = $dealsMember->deal;

            //записываем ответы на вопросы
            $questionsWithSlugs = $deal->questionsWithSlugs()->toArray();
            $answers = $request->get('answers', []); //dd($answers);
            $organizationDealAnswerService    = new \App\Services\OrganizationDealAnswer\OrganizationDealAnswerService();

            foreach ($answers as $slug => $value) {

                $question_id = $questionsWithSlugs[$slug]['id'];
                if($question_id === null){
                    continue;
                }

                $curAnswer = [
                    'member_id'    => $dealsMemberId,
                    'deal_id'     => $deal->id,
                    'organization_id' => $dealsMember->organization_id ,
                    'question_id' => $question_id,
                    'is_agree'    => $value,
                ];

                // если есть старое значение, то обновить
                $oldAnswer = $organizationDealAnswerService->getRowIfRowValExists(['deal_id' =>  $deal->id,  'organization_id' => $dealsMember->organization_id,  'question_id' => $question_id]);

                if($oldAnswer){
                    $organizationDealAnswerService->update($oldAnswer->id, $curAnswer);
                }
                else{
                    $organizationDealAnswerService->create($curAnswer);
                }

            }

            return $this->successResponse($result);

		}
		catch(Throwable $e){
			return $this->errorResponse($e->getMessage());
		}
    }

    /**
     * moderateIndex
     *
     *  Показать список объявлений на модерации
     *
     * @param  Request $request
     *
     * @return collection
     */
    public function moderateIndex(Request $request)
    {

        $query = OrganizationDeal::filtredData($request, ['id', 'user_id', 'name', 'budget_from', 'budget_to', 'budget_all', 'is_fake', 'type_deal', 'finish', 'organization_id', 'deadline_deal', 'created_at', 'published_at', 'manager_id', 'planned_finish', 'moderator_id']);

        $query->where('status', OrganizationDeal::DEAL_STATUS_MODERATION);

        $isOnlyMy = ($request->has('only_my_deals') and $request->input('only_my_deals') === "true") ? true : false;

        if($isOnlyMy){
           // $query->whereNull('manager_id')->orWhere('manager_id', '=', Auth::user()->id);
            $query->where('manager_id', '=', Auth::user()->id)->orWhere('manager_id', '=', Auth::user()->id);
        }

        return $query->with('organization', 'categories', 'user')
            ->paginate($request->input('per_page', 10));

    }

    public function moderateSuccess(Request $request)
    {

        $input = $request->all();
       // dd($input);
        $validator = Validator::make( $input, [
            'tags' => 'sometimes|string|min:1|max:500',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages());
        }

        $deal = $this->getDeals($request->id);
        $user = $deal->user()->first();
        // ============================== Платность услуги рассылки оповещений по эмайл ============================================================
        // если объявление о покупке, разошлем всем письма , кто в той же категории
        if ($deal->type_deal === OrganizationDeal::DEAL_TYPE_BUY) {

            $dealQuestion = $deal->questionsWithSlugs()->toArray();

            $dqh_specification = isset ($dealQuestion['dqh_specification']) ? $dealQuestion['dqh_specification']['question'] : '';
            $dqh_volume = isset ($dealQuestion['dqh_volume']) ? $dealQuestion['dqh_volume']['question'] : '';
            $dqh_type_deal = isset ($dealQuestion['dqh_type_deal']) ? $dealQuestion['dqh_type_deal']['question'] : '';
            $dqh_min_party = isset ($dealQuestion['dqh_min_party']) ? $dealQuestion['dqh_min_party']['question'] : '';
            $unit_for_all = $deal->unit_for_all;

            $isNewDeal = ($deal->created_at->eq($deal->updated_at)) ? true : false;

            $this->updateSubscribeUserDealActivate($request->id, $user->id);

            // если  created_at = updated_at - то это новая сделка, иначе - обновление
            if ($isNewDeal) {
                $deal->notify(new UserNewDealBuyMessage('Прошла модерацию', $user, $deal)); // отправит по сокетам оповещения для юзеров, что новая сделка
            } else {
                $deal->notify(new UserNewDealBuyMessage('Была изменена', $user, $deal)); // отправит по сокетам оповещения для юзеров на обновление
            }

            $deal->trading_status = OrganizationDeal::DEAL_TRADING_STATUS_WAITING_WINNER;// ожидание победителя

            if ($deal->planned_finish === null or (Carbon::parse($deal->planned_finish) > Carbon::now()->addDays(30))) {
				$deal->planned_finish = Carbon::now()->addDays(30);// дата окончания сделки
			}

            $deal->save();

            $urlToDeal = url('bids/' . $deal->id);

            /////////////////////////////////// Рассылка оповещений по тегам из сделки //////////////////////////////////////////////////

            // либо создать тэг, либо вернуть модель уже имеющегося
            if ($tag = (new TagsController())->localCreate($deal->name)){

                // сбрать организации для рассылки у ког есть тэг текущий, и организация подписана на оповещения и организация не владельца заявки
                $organizationToNotify = $tag->organizations()->has('channels')->with('owner', 'channels')->where('owner_id', '<>', $user->id)->get();

                // организации, имеющие рассылку
                foreach ($organizationToNotify as $curOrg) {

                    // каналы расылки организации
                    foreach ($curOrg->channels as $channel) {

                        switch ($channel->name) {
                            // оповещения по почте
                            case 'mail':
                                    $urlToUnsubscribe = url('/unsubscribe?mail=' .$curOrg->owner->email . "&uid=" . $curOrg->owner->unique_id);

                                    $deal->notify(new SendEmailModerateDealToTagSubscription(
                                        $curOrg->owner->email,
                                        $curOrg->owner->name,
                                        $urlToDeal,
                                        $deal->name,
                                        $dqh_specification,
                                        $dqh_volume,
                                        $unit_for_all,
                                        $dqh_type_deal,
                                        $dqh_min_party,
                                        $urlToUnsubscribe
                                    ));
                                break;

                            default:
                                # code...
                                break;
                        }
                    }
                }
            }

            ///////////////////////////////////////////////////////////////////////////////////////////////

            // !!!!!!!!!!!!!!!!   пока решено убрать рассылку по заданию от  2019-11-11 !!!!!!!!!!!!!!!!!!!!!!!!!!!!

            // $categoriesIds = $deal->categories()->categoriesNotRoot()->pluck('id')->toArray(); // было велено не учитывать категории первого уровня
            // // $categoriesIds = $deal->categories()->pluck('id')->toArray();

            // // рассылка оповещений о появлении нового заказа в их  категории
            // $organizationToNotify = Organization::with(['categories' => function ($query) use ($categoriesIds) {
            //     $query->whereIn('category_id', $categoriesIds);
            // }])->get();

            // foreach ($organizationToNotify as $key => $orgCats) {
            //     // пропускаем организацию разместившего заявку, зачем слать письмо самому себе или у какой оргинизации нет категорий,
            //     // или не создана организация или пользователь заблокирован
            //     if ($user->id === $orgCats->owner->id or count($orgCats->categories) < 1 or
            //                     $orgCats->owner->is_org_created !== 1 or $orgCats->owner->status !== User::USER_STATUS_APPROVE) {
            //         continue;
            //     }

            //     // шлем письмо те
            //    // if ($orgCats->owner->isProAccount()) {
            //         foreach ($orgCats->categories as $orgCat) {
            //             // если категория заявки есть в категории текущей в цикле организации
            //             if (in_array($orgCat->id, $categoriesIds)) {

            //                 if ($isNewDeal) {
            //                     $deal->notify(new DealCreateToCategoriesOrgSubscription(
            //                         $orgCats->owner->email,
            //                         $orgCats->owner->name,
            //                         $orgCat->name,
            //                         $urlToDeal,
            //                         $deal->name,
            //                         $dqh_specification,
            //                         $dqh_volume,
            //                         $unit_for_all,
            //                         $dqh_type_deal,
            //                         $dqh_min_party
            //                     ));
            //                 } else {
            //                     $deal->notify(new DealUpdateToCategoriesOrgSubscription(
            //                         $orgCats->owner->email,
            //                         $orgCats->owner->name,
            //                         $orgCat->name,
            //                         $urlToDeal,
            //                         $deal->name,
            //                         $dqh_specification,
            //                         $dqh_volume,

            //                         $unit_for_all,
            //                         $dqh_type_deal,
            //                         $dqh_min_party
            //                     ));
            //                 }

            //                 break;
            //             }
            //         }
            //     //}
            // }

            $deal->notify(new SendEmailModerate('Объявление опубликовано', 'Ваша заявка одобрена администратором.', $urlToDeal));

        } else { // если сделка на продажу SELL

            // добавим теги в БД и подпишем юзера на них
            $tags = $request->get('tags', '');
            $newTagsIds = (new TagsController())->localCreateTagsFromString($tags);
            $organization_id = $deal->organization_id;

            // подписать юзера на тэги, если он не подписан
            foreach($newTagsIds as $tagId){
                // либо создать тэг, либо вернуть модель уже имеющегося (тут он его вернет, потому что мы ранее проверили, что он есть)
                if ($tag = (new TagService())->getRowValue('id', $tagId)) {
                    $tag->organizations()->syncWithoutDetaching($organization_id);
                }

            }

            $urlToDeal = url('/#!sales/' . $deal->url);

            $deal->notify(new UserNewDealBuyMessage('Прошла модерацию', $user, $deal));
            $deal->notify(new SendEmailModerate('Объявление опубликовано', 'Ваша заявка одобрена администратором.', $urlToDeal));
        }

        if ($deal->finish == 0) {
            OrganizationDeal::where('id', $request->id)->update(
                [
                    'status' => OrganizationDeal::DEAL_STATUS_APPROVE,
                    'published_at' => Carbon::now(),
                ]
            );
        } else if ($deal->finish == 1) {
            OrganizationDeal::where('id', $request->id)->update(
                [
                    'status' => OrganizationDeal::DEAL_STATUS_APPROVE,
                    'finish' => 0,
                    'finished_at' => null,
                    'published_at' => Carbon::now(),
                ]
            );
        }

        $deal->moderator_id  = Auth::user()->id; // кто модератор

        $deal->save();

        return $this->successResponse();
    }

    public function moderateFails(Request $request)
    {

        $deals= $this->getDeals($request->id);
        $user = $deals->user()->first();
        OrganizationDeal::where('id', $deals->id)->update(
            [
                'status' => OrganizationDeal::DEAL_STATUS_BANNED,
                'finish' => 1,
                'finished_at' => Carbon::now(),
                'moderator_id'   => Auth::user()->id, // кто модератор
            ]
        );

        $subscriptionUser = $this->getSubscribeUserDeal($deals->id, $user->id);

        if ($subscriptionUser){
            $this->updateSubscribeUserDealFinished($subscriptionUser->id);
            // вернуть деньги , если только объявление еще не было в публикации, т.е. started_at ===null
            if($subscriptionUser->started_at === null){
               $this->updateBalance($deals->user_id, $subscriptionUser->cost); // вернуть деньги
            }
        }



        $deals->notify(new SendEmailModerate('Модерация отклонена', 'Ваша заявка отклонена администратором.',  url('bids/' . $deals->id)));

        return $this->successResponse();

    }

    /**
     * failModerationResponse
     *
     *  Отклик не прошел модерацию
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function failModerationResponse(Request $request)
    {

        try{

            $dealsMemberId = (int) $request->id;

            $dealsMember = OrganizationDealMember::find($dealsMemberId);

            if(!$dealsMember){
				return $this->errorResponse("Нет участника с id = " .  $dealsMemberId);
            }

            $dealsMember->trading_status =  OrganizationDeal::DEAL_TRADING_STATUS_BANNED;
            $dealsMember->moderator_id =  Auth::user()->id;
            $dealsMember->is_readed_owner_deal =  true; // хозяин заявки не должен получить уведомление о непрочитанном
            $dealsMember->save();

            $dealsMember->notify(new SendEmailModerateResponse('Отклик не прошел модерацию',
                                    $dealsMember->user()->email,
                                    $dealsMember->user()->name,
                                    $dealsMember->deal->name,
                                    $dealsMember->deal->id,
                                    ' Модерация не пройдена'));

            return $this->successResponse();

		}
		catch(Throwable $e){
			return $this->errorResponse($e->getMessage());
		}

    }

    /**
     * successModerationResponse
     *
     *  Отклик прошел модерацию
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function successModerationResponse(Request $request)
    {

        try{

            $dealsMemberId = (int) $request->id;

            $dealsMember = OrganizationDealMember::find($dealsMemberId);

            if(!$dealsMember){
				return $this->errorResponse("Нет участника с id = " .  $dealsMemberId);
            }

            $dealsMember->trading_status =  OrganizationDeal::DEAL_TRADING_STATUS_WAITING_WINNER;
            $dealsMember->is_readed_owner_deal =  false; // хозяин заявки не прочел новый неотмодерированный
            $dealsMember->moderator_id =  Auth::user()->id;
            $dealsMember->save();

            // Начислим балл в статистику, если она есть
            if($statistic = $dealsMember->organization()->first()->statistic()->first()){
                $statistic->increment('count_responses');
            }

            // письмо продавцу ,что отклик прошел модерацию
            $dealsMember->notify(new SendEmailModerateResponse('Отклик прошел модерацию',
                                    $dealsMember->user()->email,
                                    $dealsMember->user()->name,
                                    $dealsMember->deal->name,
                                    $dealsMember->deal->id,
                                    'Модерация пройдена'));

            // письмо покупателю ,что есть новый отклик по его заявке
            $dealsMember->notify(new SendEmailModerateNewResponse('Новый отклик',
                                    $dealsMember->deal->user->email,
                                    $dealsMember->deal->user->name,
                                    $dealsMember->deal->name,
                                    $dealsMember->deal->id,
                                    url('bids/' . $dealsMember->deal->id)));

            return $this->successResponse();

		}
		catch(Throwable $e){
			return $this->errorResponse($e->getMessage());
		}

    }

    /**
     * responseImageDelete - удалить картинку из отклика
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function responseImageDelete(Request $request, $id) {

        $file =  $this->responsesFileService->delete((int)$id);

        if($file instanceof ResponsesFile){
            return $this->successResponse(['id' => $file->id]);
        }
        else{
            return $this->errorResponse($file);
        }

    }


    private function getDeals($id)
    {
        return OrganizationDeal::where('id', $id)->first();
    }

    private function getSubscribeUserDeal ($deal_id, $userId) {
        $model=new UserSubscription();
        $userSubscription=$model->where('deal_id', $deal_id)->where('user_id', $userId)->orderBy('created_at', 'DESC')->first();
        return $userSubscription;
    }

    private function updateSubscribeUserDealFinished($subscrId) {
        UserSubscription::where('id', $subscrId)->first()
            ->update(
                [
                    'status' => Subscription::SUBSCRIPTION_STATUS_FINISHED,
                    'finished_at' => Carbon::now(),
                    'started_at' => Carbon::now(),
                    'moderator_id'   => Auth::user()->id, // кто модератор
                ]
            );
    }

    private function updateSubscribeUserDealActivate($deal_id, $userId) {
        UserSubscription::where('deal_id', $deal_id)->where('user_id', $userId)->where('status', Subscription::SUBSCRIPTION_STATUS_PAUSE)
            ->update(
                [
                    'status'=> Subscription::SUBSCRIPTION_STATUS_ACTIVE,
                    'started_at'=>Carbon::now(),
                ]
            );
    }

    private function updateBalance($user_id, $cost)
    {
        User::where('id', $user_id)->update(
            [
                'ballance' => DB::raw('ballance+' . $cost)
            ]
        );

        $user= User::where('id', $user_id)->first();

        // создадим запись в финансовых операциях
        $rowFinanceOperation = (new \App\Services\FinanceOperation\FinanceOperationService())->storeLocalPayment($user, Auth::user(), $cost, "Возврат средств по заявке, не прошедшей модерацию");

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

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {

        try{
			$id = (int) $id;
			if (!$deal = OrganizationDeal::with(['organization', 'members', 'categories', 'cities', 'user', 'questions', 'winner', 'files'])->find($id))
				throw new \App\Exceptions\NotFoundException();

            // $deal->category = $deals->categories->pluck('id');
            // $deal->images   = $this->getImagesDeal($deals->id);

			return $this->successResponse(
				AdminDealsItemFormatter::format($deal)
			);
		}
		catch(Throwable $e){
			return $this->errorResponse($e->getMessage());
		}



    //     $deals = OrganizationDeal::with([
    //         'organization',
    //         'user',
    //         'cities',
    //         'categories',
    //         'questions',
    //         'members',
    //         'winner',
    //         'files'
    //     ])->find($id);

    //     $deals->category = $deals->categories->pluck('id');
    //     $deals->images   = $this->getImagesDeal($deals->id);

    //     // получить слаги заголовков вопросов
    //     $questionsIds = $deals->questions->pluck('question_id')->toArray();
    //     $slugQuestionsHeaders = DealsQuestionsHeaderService::getArrFromIdsArray( $questionsIds)->keyBy('id')->toArray();

    //     $questions = $deals->questions->map(function ($itm, $key) use ($slugQuestionsHeaders) {
    //         return collect([
    //             'id' => $itm->id,
    //             'deal_id' => $itm->deal_id,
    //             'question_id' => $itm->question_id,
    //             'slug' => $slugQuestionsHeaders[$itm->question_id]['slug'],
    //             'name' => $itm->name,
    //             'question' => $itm->question,
    //         ]);
    //     });

    //     $deals->questionss = $questions->toArray() ;
    // //    // dd( $slugQuestionsHeaders);
    // //    dd( $deals->questions->toArray());
    //  //   dd( $deals->toArray());

    //     return $deals;
    }

   // DealUpdateRequest $request
    public function update(DealUpdateRequest $request, $id)
    {

            $deal = OrganizationDeal::find($request->id);

            if(!$deal){
				return $this->errorResponse("Нет объявления с id = " . $id);
            }

            $dealId = $deal->id;
            $isDealSell = ($deal->type_deal === OrganizationDeal::DEAL_TYPE_SELL) ? true : false;

            $deal->name = $request->get('name');
            $deal->description =  $request->get('description');
            $deal->deadline_deal = $this->getNullIsStringNullLetters( $request->get('deadline_deal', null)); // срок действия заявки
           // $planned_finish = $this->getNullIsStringNullLetters( $request->get('planned_finish', null)); // когда закрывать заявку

            //if ($deal->deadline_deal != null and (Carbon::parse($deal->deadline_deal) < Carbon::now()->addDays(30))) {


            // продажа
            if($isDealSell) {
                $deal->budget_to= $request->get('budget_to');
                $deal->planned_finish = null;
                $deal->tags = $request->get('tags', null);
            }
            else{ // покупка
                if ($deal->deadline_deal != null) {
                    $deal->planned_finish = $deal->deadline_deal; // планируемая дата окончания заявки
                }
                $deal->budget_from = $request->get('budget_from', 0);
				$deal->budget_to   = $request->get('budget_to', 0);
				$deal->budget_all   = $request->get('budget_all', 0);
				$deal->budget_with_nds   = $request->get('budget_with_nds', 0);
				$deal->is_fake   = $request->get('is_fake', 0);

                $deal->unit_for_unit = $this->getBlankStringIsStringNullLetters( $request->get('unit_for_unit', ''));
                $deal->unit_for_all  = $this->getBlankStringIsStringNullLetters( $request->get('unit_for_all', ''));
                $deal->count_unit_in_volume = $request->get('count_unit_in_volume', 0); // количество единиц в общем объеме (например объем 5 тонн - кол-во единиц 1000 (в килограммах ,это указано в unit_for_unit))
                $deal->procent 		        = $request->get('procent', 0); // наш процент от сделки (смотрим по таблице зависимости процента от бюджета сделки
                $deal->val_for_all 	        = $request->get('val_for_all', 0);  // количество единиц товара (штук, ящиков...)
                $deal->commission 	        = $request->get('commission', 0); // наша комиссия со сделки, в рублях
                $deal->date_of_event        = $this->getBlankStringIsStringNullLetters( $request->get('date_of_event', '')); // дата проведения сделки, тут строка в свободной форме

             //   $deal->trading_status = OrganizationDeal::DEAL_TRADING_STATUS_WAITING_WINNER;// ожидание победителя

                // вопросы к сделке
                $questions = $request->get('questions');

                foreach ($questions as $slug => $value) {
                    $question_id = $this->dealsQuestionsHeaderService->getRowValue('slug', $slug);
                    if($question_id === null){
                        continue;
                    }

                    $question_id = $question_id->id;
                    $curQuestion = [
                        'deal_id'     => $dealId,
                        'question_id' => $question_id,
                        'question'    => $value,
                    ];

                    // если есть старое значение, то обновить
                    $oldQuestion = $this->dealsQuestionsService->getRowIfRowValExists(['deal_id' => $dealId,  'question_id' => $question_id]);

                    if($oldQuestion){
                        $this->dealsQuestionsService->update($oldQuestion->id, $curQuestion);
                    }
                    else{
                        $this->dealsQuestionsService->create($curQuestion);
                    }

                }

            }

            $deal->moderator_id = Auth::user()->id; // кто модератор
            $deal->url = \App\Classes\Helpers\Translit::translitToUrl($deal->name) . '-' . $deal->id;// формириуем урл
            $deal->save();

			if ($request->get('cities')){
				$deal->cities()->sync($request->cities);
			}

			$deal->categories()->sync($request->categories);

            // продажа - загрузка файлов
            if ($isDealSell) {
                $files = $request->file('files');
                if($files){

                    // удалить файлы все что есть, для начала
                    // foreach($deal->files as $file){
                    //     $this->dealsFileService->delete($file->id);
                    // }
                    foreach ($files as $file) {
                        if($file !== null){
                            $toFileService = [
                                'file'   => $file,
                                'user_id'=> $deal->user_id,
                                'deal_id'=> $deal->id,
                            ];

                            $dealsFile = $this->dealsFileService->create($toFileService);

                            if( !($dealsFile instanceof DealsFile)){
                                return $this->errorResponse('Объявление создано, но файл не загружен :' . $dealsFile);
                            }
                        }
                    }
                }
            } else {
                // файл
                $file = $request->file;
                if($file){
                    // $this->dealsFileService->delete($file->id);
                    $toFileService = [
                        'file'   => $file,
                        'user_id'=> $deal->user_id,
                        'deal_id'=> $deal->id,
                    ];

                    $dealsFile = $this->dealsFileService->create($toFileService);

                    if( !($dealsFile instanceof DealsFile)){
                        return $this->errorResponse('Объявление создано, но файл не загружен :' . $dealsFile);
                    }
                }
            }

            $deal['questions'] = $deal->questions;
            $deal['files'] = $deal->files;

            return $this->successResponse($deal);
    }
		public function createNewTagsSubscriptionToNewTags(Request $request)
    {
    $string = explode(",", $request->get('tags'));
    $organization_id = (int) $request->get('organization_id');
    foreach($string as $arrTags)
      {
        //dd($arrTags);
        $tag = (new TagsController())->localCreate($arrTags);
        $tag->organizations()->syncWithoutDetaching($organization_id);
      }
      return $this->successResponse();
    }

    /**
     * @param Request $request
     *
     * Подписывает юзера на теги, которые есть в БД и на которые он еще не подписан
     */
    public function subscriptionToExistTagsAndReturnIsNotExist(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make( $input, [
            'tags' => 'required|string|min:1|max:500',
            'organization_id' => 'required|exists:organizations,id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages());
        }

        $string = $request->get('tags');
        $organization_id = (int) $request->get('organization_id');

        $arTagsExistAndNotExistInDB = (new TagsController())->getArrayExistAndNotExistTagsInDbFromString($string);

        // подписать юзера на тэги, если он не подписан
        foreach($arTagsExistAndNotExistInDB['exist'] as $tagName){
            // либо создать тэг, либо вернуть модель уже имеющегося (тут он его вернет, потому что мы ранее проверили, что он есть)
            if ($tag = (new TagsController())->localCreate($tagName)){
                $tag->organizations()->syncWithoutDetaching($organization_id);
            }

        }

        return $this->successResponse(implode(",", $arTagsExistAndNotExistInDB['not_exist']));
    }

    public function uploadImageDeal (Request $request) {
       $images=new ImagesDeals();
       $images->uploadFileAndSaveToDB($request->file, 1, $request->deal_id, config('b2b.images.resizeValMaxPx'));
       $imgDeal=$this->getImagesDeal($request->deal_id);

        return response()->json(['images'=>$imgDeal]);
    }

    private function getImagesDeal ($id) {
        $images=ImagesDeals::where('deal_id', $id)->get();

        $imgArray=[];

        if(sizeof($images)>0){
            foreach ($images as $i){
               array_push($imgArray, $i);
            }
        }


        return $imgArray;
    }

    public function deleteImage(Request $request) {

        $image=ImagesDeals::where('id', $request->id)->first();
        $deal_id=$image->deal_id;
        $path=$image->file_full_path;
        unlink(public_path($path));
        ImagesDeals::where('id', $request->id)->delete();
        return response()->json(['images'=>$this->getImagesDeal($deal_id)]);
    }

    /**
     * uploadFile
     *
     *  Загрузка файла
     * @param  mixed $request
     *
     * @return void
     */
    public function uploadFile (DealsFileStoreRequest $request) {

        $data = $request->all();

        $dealsFile = $this->dealsFileService->create($data);

        if($dealsFile instanceof DealsFile){
            return $this->successResponse(new DealsFileResource($dealsFile));
        }
        else{
            return $this->errorResponse($dealsFile);
        }
     }

     /**
      * getFile
      *
      *  Получить файл по его id
      *
      * @param  mixed $id
      *
      * @return void
      */
      public function getFile ($id) {
        $dealsFile = $this->dealsFileService->get($id);

        if($dealsFile instanceof DealsFile){
            return $this->successResponse(new DealsFileResource($dealsFile));
        }
        else{
            return $this->errorResponse($dealsFile);
        }
     }

     public function deleteFile($id) {

        $dealsFile =  $this->dealsFileService->delete($id);

        if($dealsFile instanceof DealsFile){
            return $this->successResponse(['id' => $dealsFile->id]);
        }
        else{
            return $this->errorResponse($dealsFile);
        }
     }


    /**
     * @param $id
     * @return mixed
     *Заявка не удаляется а уходит в архив
     * finish 1
     * status Archive
     */
    public function delete($id)
    {

        if (!$deal = OrganizationDeal::find($id) ){
        throw new \App\Exceptions\NotFoundException();
        }

        // Если продажа - удалить вообще
        if($deal->type_deal === OrganizationDeal::DEAL_TYPE_SELL){
            $deal->finish = 1;
            $deal->finished_at = Carbon::now();
            $deal->status = OrganizationDeal::DEAL_STATUS_ARCHIVE;
            $deal->moderator_id = Auth::user()->id; // кто модератор
            $deal->save();
            $deal->delete();
        } else {
            OrganizationDeal::where('id', $id)->update(
                [
                    'finish' => 1,
                    'finished_at' => Carbon::now(),
                    'status' => OrganizationDeal::DEAL_STATUS_ARCHIVE,
                    'moderator_id' => Auth::user()->id, // кто модератор
                ]
            );
        }
        return $this->successResponse();
    }


    /**
     * @param Request $request
     *
     * Создаем аккаунт, компанию, сделку.  Возращаем клиенту индентификатор сделки для переадресации на редактирование
     */
    public function storeDealManager(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make( $input, [
            'type_deal' => 'required|in:' . OrganizationDeal::DEAL_TYPE_SELL . ','. OrganizationDeal::DEAL_TYPE_BUY,
            'count_deal' => 'required|integer|min:1|max:10',
            'user_id' => 'sometimes|exists:users,id',
            'is_fake' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages());
        }

        $type_deal  =  $input['type_deal'];
        $count_deal =  $input['count_deal'];
        $user_id    =  $input['user_id'] ?? null;
        $is_fake    =  $input['is_fake'] ?? 0;

        $ids = false;

        if($user_id and ($user = User::where('id', $user_id)->first())){ // если привязка автогенеренных сделок к пользователю
            $ids = $this->storeDealsForUser($user, $type_deal, $count_deal, $is_fake);
        }
        else{ // автосоздание сделок с генерацией пользователя
            $ids = $this->storeAccount($type_deal, $count_deal);
        }

        if($ids === false){
            return $this->errorResponse("Создать сделки в количестве " .  $count_deal . " шт. - не получилось, обратитесь к разработчикам");
        }
        return $this->successResponse($ids);
    }

    private function storeAccount ($type_deal, $count_deal = 1) {

        $client = new ClientsController();

        $password = str_random(8);
        $user = new User();
        $user->name = ($type_deal === OrganizationDeal::DEAL_TYPE_SELL) ? 'Продавец' : 'Покупатель';
        $user->password = Hash::make($password);
        $user->email = $user->getLastEmailAutoAccount();
        $user->phone = '9999999999';
        $user->role   = User::ROLE_CLIENT;
        $user->status = User::USER_STATUS_APPROVE;
        $user->unique_id = $client->generateUniqueUserIdNumber();
        $user->is_org_created = 1;
        $user->account_is_created = Auth::user()->email; //User::ACCOUNT_CREATED_AUTO;
        $user->hide_email = 1;
        $user->save();

        $organization = $this->generateOrg();

        $this->createOrg($user, $organization);

        $ids  = [];
        while ($count_deal) {
            $ids[] = $this->dealsStore($user, $type_deal, [], 0);
            $count_deal--;
        }

       return $ids; //$this->dealsStore($user, $type_deal, [], 0);
    }

    private function createOrg($user, $organization)
    {
        if ($organization === null) { // MVP версия , без заполнения организации
            $organization = $user->company()->create();
        } else {
//            dd($organization['categories']);
            $cat = $organization['categories'];
            $organization = $user->company()->create($organization);
            $organization->categories()->sync($cat);

        }
        $user->organization()->associate($organization);  // Этот метод обновит отношения belongsTo() и установит внешний ключ на дочерней модели, в частности owner_id = users.id

        $user->save();
    }

    private function generateOrg () {
        $organization=[];

        $categorySecondLevelId = Category::CategoriesNotRoot()->first(); //id категории , не выше первого уровня
        if($categorySecondLevelId === null){
            $categorySecondLevelId = 1;
        }
        else{
            $categorySecondLevelId = $categorySecondLevelId->id;
        }

        $organization['categories']=[
            "0" => $categorySecondLevelId
        ];
        $organization['offices']=[];
        $organization['org_address']="Брянск";
        $organization['org_city_id']=108;
        $organization['org_description']="";
        $organization['org_director']="Фамилия";
        $organization['org_inn']="1234567890";
        $organization['org_kpp']="123123123";
        $organization['org_legal_form_id']=1;
        $organization['org_name']="Наименование";
        $organization['org_type']="buyer";
        $organization['phone']="9999999999";
        $organization['stores']=[];
        $organization['org_manager_post']="Генеральный директор";
        $organization['org_okved']="9999.9999";
        $organization['org_ogrn']="9999.9999";
        $organization['org_is_active']=true;
        $organization['org_registration_date']=now();

        return $organization;
    }


    private function storeDealsForUser($user, $type_deal, $count_deal = 1, $is_fake = 0) {

        while ($count_deal) {
            $ids[] = $this->dealsStore($user, $type_deal, [], 0, $is_fake);
            $count_deal--;
        }

        return $ids;
    }

    private function dealsStore($user, $type_deal, $images, $budget_to,  $is_fake = 0)
    {

        try{

            $managerId = Auth::user()->id;
            $subtype_deal =  ($type_deal === OrganizationDeal::DEAL_TYPE_SELL) ? OrganizationDeal::DEAL_SUBTYPE_NEW : OrganizationDeal::DEAL_SUBTYPE_NA;

// ============================== Платность услуги объявление о продаже ============================================================

            // проверка возможности получить услугу в принципе
//            if($type_deal === OrganizationDeal::DEAL_TYPE_SELL){
//                //$paymentInfo = $this->paymentService->paymentServiceIsPossible($request, Subscription::SUBSCRIPTION_PAYMENT_ONCE_DEAL_SELL , true);
//              //  $isDealSell = true;
//                //$isProAccount = $paymentInfo['is_pro_account'];
//
////                if($paymentInfo['is_possible'] === false){
////                    return $this->errorResponse($paymentInfo['message']);
////                }
//            }

            // организация юзера
            $organization = $user->organization();

            // сделка
            $deal = new OrganizationDeal();
            if($organization){
                $deal->organization_id = $organization->first()->id;
            }
            $deal->user_id 		= $user->id;
            $deal->type_deal 	= $type_deal; // тип сделки (сейчас: покупка - buy, продажа - sell)
            $deal->subtype_deal = $subtype_deal; // подтип сделки (сейчас: новое - new, бу - used, без подтипа - na)

            $isDealBuy = false; // заявка по покупку
            // если объявление о покупке, то это пока всегда 'na'
            if($type_deal === OrganizationDeal::DEAL_TYPE_BUY){
                $deal->subtype_deal = OrganizationDeal::DEAL_SUBTYPE_NA;
                $isDealBuy = true;
            }
            elseif($type_deal === OrganizationDeal::DEAL_TYPE_SELL){

                    $deal->payment_status = OrganizationDeal::DEAL_STATUS_PAYMENT_PAID;

            }

            $deal->name 		= 'Наименование сделки'; // название (заголовок) заявки
            $deal->description 	= 'Здесь должно быть описание сделки';  // описание заявки

            $deal->budget_to 	= $budget_to; // бюджет до (сейчас просто - бюджет)
            $deal->is_fake 	= $is_fake; // фейковая сделка или нет
            $deal->deadline_deal = null; // срок действия заявки
            $deal->manager_id = $managerId; // менеджер, создавший заявку
            $deal->planned_finish = Carbon::now()->addDays(30);// дата окончания сделки

            $deal->save();

            $categorySecondLevelId = Category::CategoriesNotRoot()->first(); //id категории , не выше первого уровня
            if($categorySecondLevelId === null){
                $categorySecondLevelId = 1;
            }
            else{
                $categorySecondLevelId = $categorySecondLevelId->id;
            }

            $categories=[
                '0'=>$categorySecondLevelId
            ];
            $deal->categories()->sync($categories);

            $cities=[
                "0"=>40
            ];
            $deal->cities()->sync($cities);

            // изображения

            if($images){
                foreach ($images as $image) {
                    if($image !== null){
                        $images = new ImagesDeals();
                        if($images->uploadFileAndSaveToDB($image, $deal->user_id, $deal->id, config('b2b.images.resizeValMaxPx')) === false){
                            return $this->errorResponse();
                        }
                    }
                }
            }


            // ============================== Платность услуги рассылки оповещений по эмайл ============================================================
            //!!!!!!  перенесено в App\Http\Controllers\Admin\DealsController    moderateSuccess()
            // если объявление о покупке, разошлем всем письма , кто в той же категории
//            if($isDealBuy){
//
//            }
//            elseif($isDealSell){
//                if($isProAccount){
//                    //$this->paymentService->newUserSubscription($paymentInfo['subscription_id'], $user->id, $deal->id, "Новая сделка о продаже. Pro аккаунт", null, null, $isProAccount);
//                }
//                else{
//                    //$user->ballance -=  $paymentInfo['cost_of_service'];// снимем сразу сумму за  услугу
//                    //$user->save();
//                    //$this->paymentService->newUserSubscription($paymentInfo['subscription_id'], $user->id, $deal->id, "Новая сделка о продаже", null, null, $isProAccount);
//                }
//
//            }
            // ==============================END  Платность услуги рассылки оповещений по эмайл ============================================================

            return $deal->id;

        }
        catch(Throwable $e){
            return $this->errorResponse($e->getMessage());
        }


    }


    /**
     * refundDealModerate
     *
     *  Вернуть на модерацию
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function refundDealModerate (Request $request) {
        OrganizationDeal::where('id', $request->id)->update(
            [
                'finish' => 0,
                'finished_at' => null,
                'status' => OrganizationDeal::DEAL_STATUS_MODERATION
            ]
        );

        return $this->successResponse();
    }
}
