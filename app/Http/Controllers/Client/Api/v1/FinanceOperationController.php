<?php

namespace App\Http\Controllers\Client\Api\v1;

// use App\Http\Resources\FinanceOperation\FinanceOperationCollection;
use App\Http\Resources\FinanceOperation\FinanceOperationResource;
use App\Traits\ApiControllerTrait;

use App\Http\Requests\Client\Api\v1\FinanceOperation\FinanceOperationStoreRequest;
use App\Http\Requests\Client\Api\v1\FinanceOperation\FinanceOperationUpdateRequest;
use App\Http\Requests\Client\Api\v1\FinanceOperation\FinanceOperationPaytureStoreRequest;
use App\Services\FinanceOperation\FinanceOperationService;
use Exception;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Client\Api\v1\PaymentServicesController;
use App\Http\Controllers\Client\Api\v1\DialogsController;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\FinanceOperation;
use App\Models\Org\OrganizationDeal;
use App\Models\Payment\Subscription;

use Auth;
use Carbon\Carbon;

use App\Classes\Payture\PaytureConfiguration;
use App\Classes\Payture\PaytureInPay;
use App\Classes\Payture\PaytureCheque;

class FinanceOperationController extends Controller
{
    
    use ApiControllerTrait;

   // private $typePaytureServer = PaytureConfiguration::ENV_DEVELOPMENT; // поменять , на продакшене  на    PaytureConfiguration::ENV_PRODUCTION; // 
    private $typePaytureServer = PaytureConfiguration::ENV_PRODUCTION; 

    /**
    * @var FinanceOperationService
    */
    private $financeOperationService;

    /**
    * @param FinanceOperationService $financeOperationService
    */
    public function __construct(FinanceOperationService $financeOperationService)
    {
        $this->financeOperationService = $financeOperationService;
    }

    /**
     *  Инициализируем сессию оплаты и редиректим на их страницу
     * 
    * @param FinanceOperationPaytureStoreRequest $request
    */
    public function paytureStore(FinanceOperationPaytureStoreRequest $request)
    { 
        /** @var User $user */
        $user = Auth::guard('api')->user();

        // создали уникальный идентификатор платежа
        $orderId = $this->generateUniqueOrderIdValue($user->unique_id);

        $textCheck = "Пополнение кошелька на ресурсе TamTem";
        // ----------  сделаем запись в БД о попытке оплатить --------------------------------------------------
        $amount = $request->input('amount');
        $data = [
            'payment_id' 		=> $orderId,
            'amount'            => $amount,// * 100,  // т.к. сумма в копейках
            'payment_system'    => FinanceOperation::PAYMENT_SYSTEM_PAYTURE,
            'status'    		=> FinanceOperation::PAYMENT_STATUS_WAITING,
            'text'    			=> "Попытка оплаты платежной системой PAYTURE",
        ];


        $productName =  "Payment to wallet Tamtem"; 
        // если оплата услуги, а не просто деньги на кошелек
        $isPaymenuSubscription = false;   
        if (true === $request->has('deal_id') && true === $request->has('slug')) {
            $isPaymenuSubscription = true;
            $data['deal_id']  = $request->get('deal_id');
            $data['slug']     = $request->get('slug');
            $productName = "Оплата покупки контактов";
        }

        $financeOperation = $this->financeOperationService->create($user, $data);

        if(!($financeOperation instanceof FinanceOperation)){
            return $this->errorResponse($financeOperation);
        }

        // ----------  теперь попытка послать платеж в систему payture ------------------------------------------
        $urlRedirectAfter = $request->getUri() . "?orderId=" . $orderId; // куда payture будет редиректить после оплаты

        $cheque = json_encode(
            [
                "Positions" => [ 
                    [
                        "Quantity"=> 1,
                        "Price"=> (int)$amount,
                        "Tax"=> 6, // налог
                        "Text"=> $textCheck, //Текст позиции покупки в чеке
                        "SupplierINN"=> "7842166070", //Текст позиции покупки в чеке
                        "PaymentMethodType"=> 1,
                        "PaymentSubjectType"=> 4,
                    ]
                ],
                "CheckClose"=> [
                    "TaxationSystem"=> 0
                ],
                "CustomerContact"=> $user->email,
                "Message"=> "Payment to TamTem", //Тема письма или строка в сообщении СМС
                // "AdditionalMessages"=> [
                //     [
                //         "Key"=> "Параметр1",
                //         "Value"=> "Значение1"
                //     ]
                // ]
            ]
        );

        $dataInit = [
            "SessionType" => "Pay",
            "OrderId" => $orderId,
            "Amount" => $amount * 100, //  т.е. слать надо в копейках
            "IP" => $_SERVER["REMOTE_ADDR"],
            "Product" => urlencode($productName),
            "Description" => urlencode($productName . ", user id=" . $user->id),
            "Total" => $amount, 
            "Url" => $urlRedirectAfter,
            "Cheque" => base64_encode($cheque),
        ];
        
        // установим пока, что это дев (тестирование)
        //PaytureConfiguration::setEnvironment($this->typePaytureServer);
        // ИНИЦИАЛИЗИРУЕМ СЕССИЮ ОПЛАТЫ
        $initResult = PaytureInPay::Init($dataInit);

        if ($initResult->Success and !isset($initResult->ErrCode)) {    
            // если установка сессии прошла нормально, то сгенерим ссылку и редиректим на сайт оплаты
            $redirectUrl = PaytureInPay::generatePayLink();
            $financeOperation->text = $financeOperation->text . " |redirectUrl=" . $redirectUrl;
            $financeOperation->save();
            return $this->successResponse(['url' => $redirectUrl]);
        }
        else{ 
            $financeOperation->text = $initResult->ErrCode;
            $financeOperation->status = FinanceOperation::PAYMENT_STATUS_ERROR;
            $financeOperation->save();
            return $this->errorResponse("Попытка соединения с платежным шлюзом не удалась, ошибка: " . $initResult->ErrCode);
        }
    }

    /**
     *  На Сервис оплаты перекинул сюда, мы проверим статус платежа
     * 
    * @param Request $request
    */
    public function paytureReturnFromPayture(Request $request)
    { 
//dd($request->all());
        $orderId = $request->input('orderId');
        if($orderId === null){
            return $this->errorResponse("Не известная ошибка в оплате, ошибка: " . json_encode($request->all()));
        }

        if (!$this->financeOperationService->isRowValueExists('payment_id', $orderId)) {
            return $this->errorResponse("В системе нет payment_id =  " . $orderId);
        }

        $financeOperation = $this->financeOperationService->getRowValue('payment_id', $orderId);

        if($financeOperation->status === FinanceOperation::PAYMENT_STATUS_PAID){
            return redirect('/');
        }

        // дев или продакшен
        //PaytureConfiguration::setEnvironment($this->typePaytureServer);
        // проверим статус платежа
        $payedResult = PaytureInPay::PayStatus($orderId);

        // статус чека
        $checkResult = PaytureCheque::Status([
                "Key"=>PaytureConfiguration::getMerchantKey(),
                "Password"=>PaytureConfiguration::getMerchantCheckPassword(),
                "OrderId"=>$orderId
        ]);

        $isCheckSended = false;
        if ($checkResult->Success and !isset($checkResult->ErrCode) and isset($checkResult->Cheques[0]) ) { 
            $isCheckSended = $checkResult->Cheques[0]->Sended;
        }

        $redirectUrl = "/lk/wallet";

        if ($payedResult->Success and !isset($payedResult->ErrCode) and isset($payedResult->State) and $payedResult->State === "Charged") { 

            $financeOperation->status = FinanceOperation::PAYMENT_STATUS_PAID;
            $financeOperation->text = "Пополнение кошелька прошло успешно";
            $financeOperation->cheque_sent = $isCheckSended;
            $financeOperation->user->ballance += $financeOperation->amount;
            $financeOperation->user->save();
            $financeOperation->save();

            // если надо оплатить заявку, то оплатить здесь
            if($financeOperation->deal_id !== null && $financeOperation->slug !== ''){

                if (!$deal = OrganizationDeal::find($financeOperation->deal_id)){
                    return $this->errorResponse('Не найдена организация с id=' . $financeOperation->deal_id);
                }
            
                if ($deal->winner_id === null or $deal->finish === 1 or $deal->trading_status !== OrganizationDeal::DEAL_TRADING_STATUS_WAITING_PAYMENT ) {
                    return $this->errorResponse('В сделке не выбран победитель или она завершена');
                } 
            
                if (!$winner = $deal->membersFromOrganizationMembersTable()->where('organization_id', $deal->winner_id)->first()){
                    return $this->errorResponse("В сделке " . $deal->id . " нет участника " . $deal->winner_id );
                }
 
                // user - победитель
                $winnerUser = $winner->user();
        
                // попытка оплаты контактов по сделке
                $paymentService = new PaymentServicesController();
                $resultPayment = $paymentService->paymentSubscription($request, Subscription::SUBSCRIPTION_PAYMENT_ONCE_DEAL_BUY_GET_CONTACTS, true, $deal->id, $winnerUser, round($deal->commission, 0, PHP_ROUND_HALF_DOWN));
                $user_subscription_id = isset($resultPayment['id']) ? $resultPayment['id'] : null;
                //dd( $resultPayment);

                //если ошибка в чеке
                if ($checkResult->Success !== true or isset($checkResult->ErrCode)) { 
                    $financeOperation->text .= "|cheque_error: " .$checkResult->ErrCode;
                }

                $financeOperation->deal_id = $deal->id;
                $financeOperation->slug = Subscription::SUBSCRIPTION_PAYMENT_ONCE_DEAL_BUY_GET_CONTACTS;
                $financeOperation->save();

                // проплачены ли победителем контакты сделки
                $idsDealsBuyContacts = $winnerUser->idsDealsBuyContacts(); // id объявлений с проплаченными контактами
                $isPayed = isset($idsDealsBuyContacts[$deal->id]);

                if($isPayed){

                    $deal->trading_status = OrganizationDeal::DEAL_TRADING_STATUS_FINISHED;

                    //надо проставить всем участникам - что сделка по торгам завершена
                    $deal->membersFromOrganizationMembersTable()->update(['trading_status' => OrganizationDeal::DEAL_TRADING_STATUS_FINISHED]);
                    $winner->is_readed_owner_response = false; // оплатили, значит откик в не прочитанные
                    $winner->save();
                    
                    // надо закрыть сделку --------------------
                    $deal->finish = true;
                    $deal->finished_at = Carbon::now();
                    $deal->status = OrganizationDeal::DEAL_STATUS_ARCHIVE; 
                    $deal->trading_status = OrganizationDeal::DEAL_TRADING_STATUS_FINISHED; 
                    $deal->save();
                    if($deal->type_deal === OrganizationDeal::DEAL_TYPE_BUY){
                        $deal->finishForMembers();
                    }
    
                    // надо создать диалог по данной сделке между продавцом и покупателем и послеть первое сообщение
                    if(!$dialogId = DialogsController::newLocalDialog($deal->id, $deal->winner_id, $deal->organization_id)){
                        return $this->errorResponse('ошибка создания диалога');
                    }
                    if(!$messageId = DialogsController::sendLocal($dialogId, $deal->user, 'Мои контакты у Вас есть, можно обговорить детали')){
                        return $this->errorResponse('ошибка отправки сообщения продавцу');
                    }

                    $financeOperation->user_subscription_id = $user_subscription_id;
                    $financeOperation->save();

                    $redirectUrl = "/lk/responses";
                }
            }
        }
        else{ 
            $financeOperation->text = $payedResult->ErrCode;
            $financeOperation->status = FinanceOperation::PAYMENT_STATUS_ERROR;
            $financeOperation->save();

            // если надо оплатить заявку, то оплатить здесь
            if($financeOperation->deal_id !== null && $financeOperation->slug !== ''){
                $redirectUrl = "/lk/responses";
                $redirectUrl .= "?error=" . urlencode ("Попытка оплаты отклика не удалась, ошибка: " . $payedResult->ErrCode);
            }
            else{
                $redirectUrl .= "?error=" . urlencode ("Попытка оплаты не удалась, ошибка: " . $payedResult->ErrCode);
            }
            //return $this->errorResponse("Попытка оплаты не удалась, ошибка: " . $payedResult->ErrCode);
        }

        Auth::login($financeOperation->user);
        return redirect($redirectUrl);
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
    * @param Request $request
    * @return FinanceOperationCollection
    */
    public function index(Request $request)
    { 
        $financeOperation = $this->financeOperationService->index($request);
    
        return $this->successResponse($financeOperation);
    }
    
    /**
    * @param int $id
    * @return FinanceOperationResource
    * @throws Exception
    */
    public function get(int $id)
    {
        $financeOperation = $this->financeOperationService->get($id);

        if($financeOperation instanceof FinanceOperation){
            return $this->successResponse(new FinanceOperationResource($financeOperation));
        }
        else{
            return $this->errorResponse($financeOperation);
        }
    }

    /**
    * @param FinanceOperationStoreRequest $request
    * @return FinanceOperationResource
    * @throws Exception
    */
    public function create(FinanceOperationStoreRequest $request)
    { 
        /** @var User $user */
        $user = Auth::guard('api')->user();

        $financeOperation = $this->financeOperationService->create($user, $request->all());

        if($financeOperation instanceof FinanceOperation){
            return $this->successResponse(new FinanceOperationResource($financeOperation));
        }
        else{
            return $this->errorResponse($financeOperation);
            //return $this->errorResponse("Не возможно создать запиь для текущей финансовой операции. Обратитесь к Администратору");
        }
    }


    /**
    * @param int $id
    * @param FinanceOperationUpdateRequest $request
    * @return FinanceOperationResource
    * @throws Exception
    */
    public function update(int $id, FinanceOperationUpdateRequest $request)
    {
        $financeOperation = $this->financeOperationService->update($id, $request->all());

        if($financeOperation instanceof FinanceOperation){
            return $this->successResponse(new FinanceOperationResource($financeOperation));
        }
        else{
            return $this->errorResponse($financeOperation);
        }
    }

    /**
    * @param int $id
    * @return JsonResponse
    * @throws Exception
    */
    public function delete(int $id)
    {
        $financeOperation =  $this->financeOperationService->delete($id);

        if($financeOperation instanceof FinanceOperation){
            return $this->successResponse(['id' => $financeOperation->id]);
        }
        else{
            return $this->errorResponse($financeOperation);
        }
    }
}
