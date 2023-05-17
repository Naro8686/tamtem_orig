<?php

namespace App\Http\Controllers\Client\Api\v1;

use App\Formatter\Api\v1\DealsItemFormatter;
use App\Http\Requests\Client\Api\v1\Deal\DealStoreRequest;
use App\Http\Requests\Client\Api\v1\Deal\DealUpdateRequest;
use App\Http\Requests\Client\Api\v1\Deal\DealTakeRequest;

use App\Models\DealQuestion;
use App\Models\Org\OrganizationDeal;

use App\Models\User;
use App\Models\Payment\Subscription;
use App\Models\Payment\UserSubscription;
use App\Models\DealsFile;
use App\Models\ResponsesFile;
use App\Models\Org\ImagesDeals;
use App\Models\Org\OrganizationDealMember;

use App\Http\Controllers\Client\Api\v1\DialogsController;
use App\Notifications\DealCreate;
use App\Notifications\DealUpdate;
use App\Notifications\SendSlackNewResponseForManager;
use App\Notifications\SendSlackNewDealCreate;
use App\Notifications\SendSlackNewDealSell;
use App\Notifications\SendSlackChooseWinner;
use App\Notifications\DealNeedNewWinner;

use App\Notifications\DealSetWinner;
use App\Notifications\DealWinner;
use App\Notifications\DealWinnerResponse;
use App\Notifications\UserNewDealBuyMessage;

use App\Repositories\Filters\FilterDealsRepository;
use App\Traits\ApiControllerTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Client\Api\v1\PaymentServicesController;
use App\Http\Controllers\Client\Api\v1\Organization\TagController;

use App\Services\DealsFile\DealsFileService;
use App\Services\ResponsesFile\ResponsesFileService;
use App\Services\DealsQuestionsHeader\DealsQuestionsHeaderService;
use App\Services\OrganizationDealQuestion\OrganizationDealQuestionService;
use App\Services\OrganizationDealAnswer\OrganizationDealAnswerService;

use Illuminate\Support\Facades\Validator;

use Auth;
use Throwable;
use DB;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendToManagers;

use App\Formatter\Api\v1\DealsResponsesCurrentUserItemFormatter;

class DealController extends Controller
{
	use ApiControllerTrait;

	public $paymentService = null;
	public $dealsFileService = null;
	public $responsesFileService = null;
    public $dealsQuestionsService = null;
    public $dealsQuestionsHeaderService = null;
 	/**
     * Create a new controller instance.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct()
    {
        $this->paymentService = new PaymentServicesController();
		$this->dealsFileService    = new DealsFileService();
		$this->responsesFileService    = new ResponsesFileService();
		$this->dealsQuestionsService    = new OrganizationDealQuestionService();
        $this->dealsQuestionsHeaderService    = new DealsQuestionsHeaderService();
    }

	// mvp tamtem
	// список всех сделлок вообще  ===============================================================================================
	public function dealsList(Request $request)
	{
		//$user = Auth::guard('api')->user(); // todo удалить при чистке
		
		$filters = $request->all();

		$categories = $request->input('categories', null);
		$countries 	= $request->input('countries', null);
		$regions 	= $request->input('regions', null);  
		$cities 	= $request->input('cities', null); 
		$finish 	= $request->input('finish', null);
		$typeDeal 	= $request->input('type_deal', null);
		$userId 	= $request->input('user_id', null);
		if (!in_array($typeDeal, [OrganizationDeal::DEAL_TYPE_SELL, OrganizationDeal::DEAL_TYPE_BUY])){
			$typeDeal = null;
		}
		$subtypeDeal 	= $request->input('subtype_deal', null);
		if (!in_array($subtypeDeal, [OrganizationDeal::DEAL_SUBTYPE_NA, OrganizationDeal::DEAL_SUBTYPE_NEW, OrganizationDeal::DEAL_SUBTYPE_USED])){
			$subtypeDeal = null;
		}

		$status 	= $request->input('status', null);
		if (!in_array($status, [OrganizationDeal::DEAL_STATUS_MODERATION,
								OrganizationDeal::DEAL_STATUS_APPROVE,  
								OrganizationDeal::DEAL_STATUS_ARCHIVE,   
								OrganizationDeal::DEAL_STATUS_BANNED])){
			$status = null;
		}

		
		$payment_status 	= $request->input('payment_status', null);
		if (!in_array($payment_status, [OrganizationDeal::DEAL_STATUS_PAYMENT_PAID,
										OrganizationDeal::DEAL_STATUS_PAYMENT_NOT_PAID,  
										OrganizationDeal::DEAL_STATUS_PAYMENT_FREE])){
			$payment_status = null;
		}

		try{
			// dd(	FilterDealsRepository::filter($categories, $countries, $regions,  $cities, false, false, false, $finish , $typeDeal, 
			// false, false , false, false, false, false,false, $subtypeDeal, $status, $userId   )->get()->toArray());
			return $this->successResponse(
				DealsItemFormatter::formatPaginator(
					// FilterDealsRepository::getUserDeals($user)->simplePaginate() // todo удалить при чистке
					FilterDealsRepository::filter($categories, $countries, $regions,  $cities, false, false, false, $finish , $typeDeal, 
								false, false , false, false, false, false,false, $subtypeDeal, $status, $userId, $payment_status   )->simplePaginate()
				)
			);

		}
		catch(Throwable $e){
			return $this->errorResponse($e->getMessage());
		}
	}

	// mvp tamtem
	// список сделок текущего юзера  ===============================================================================================
	public function dealsListCurrentUser(Request $request)
	{
		$user = Auth::guard('api')->user();

		$filters = $request->all();

		$categories = $request->input('categories', null);
		$countries 	= $request->input('countries', null);
		$regions 	= $request->input('regions', null);  
		$cities 	= $request->input('cities', null); 
		$finish 	= $request->input('finish', null);
		$typeDeal 	= $request->input('type_deal', null);
		if (!in_array($typeDeal, [OrganizationDeal::DEAL_TYPE_SELL, OrganizationDeal::DEAL_TYPE_BUY])){
			$typeDeal = null;
		}
		$subtypeDeal 	= $request->input('subtype_deal', null);
		if (!in_array($subtypeDeal, [OrganizationDeal::DEAL_SUBTYPE_NA, OrganizationDeal::DEAL_SUBTYPE_NEW, OrganizationDeal::DEAL_SUBTYPE_USED])){
			$subtypeDeal = null;
		}
		
		$status 	= $request->input('status', null);
		if (!in_array($status, [OrganizationDeal::DEAL_STATUS_MODERATION,
								OrganizationDeal::DEAL_STATUS_APPROVE,  
								OrganizationDeal::DEAL_STATUS_ARCHIVE,   
								OrganizationDeal::DEAL_STATUS_BANNED])){
			$status = null;
		}
		      
		$payment_status 	= $request->input('payment_status', null);
		if (!in_array($payment_status, [OrganizationDeal::DEAL_STATUS_PAYMENT_PAID,
										OrganizationDeal::DEAL_STATUS_PAYMENT_NOT_PAID,  
										OrganizationDeal::DEAL_STATUS_PAYMENT_FREE])){
			$payment_status = null;
		}

		try{
		
			return $this->successResponse(
				DealsItemFormatter::formatPaginator(
					FilterDealsRepository::getUserDeals($user, $categories, $countries, $regions, $regions, $finish, $typeDeal, $subtypeDeal, $status, $payment_status )->simplePaginate()				
				)
		);

		}
		catch(Throwable $e){
			return $this->errorResponse($e->getMessage());
		}
	}

	public function dealsQuestions()
	{
		return $this->successResponse(
		   DealQuestion::all(['id', 'name', 'question'])
		);
	}

	// mvp tamtem
	// сздать сделку в зависимости от ее типа, sell, buy  ===============================================================================================
	public function dealsStore(DealStoreRequest $request)
	{

		try{

			$user = Auth::guard('api')->user();

		    $type_deal = $request->get('type_deal');
			$subtype_deal = $request->get('subtype_deal', OrganizationDeal::DEAL_SUBTYPE_NA);

			//проверим, есть ли у юзера телефон 
			if(! $user->phoneExist()){
				return $this->errorResponse("Вы не можете размещать заявки, пока не заполните номер телефона в своем личном кабинете!");
			}

			$isDealSell = ($type_deal === OrganizationDeal::DEAL_TYPE_SELL) ? true : false;
			$isProAccount = false;
			// ============================== Платность услуги объявление о продаже ===========================================
			//> проверка возможности получить услугу в принципе
			// if($isDealSell){
			// 	$paymentInfo = $this->paymentService->paymentServiceIsPossible($request, Subscription::SUBSCRIPTION_PAYMENT_ONCE_DEAL_SELL , true);
			// 	$isProAccount = $paymentInfo['is_pro_account'];

			// 	if($paymentInfo['is_possible'] === false){
			// 		return $this->errorResponse($paymentInfo['message']);
			// 	}
			// }
			//< ==============================END Платность услуги ============================================================

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

			$deal->name 		 	= $request->get('name'); // название (заголовок) заявки
			$deal->description 	 	= $this->getNullIsStringNullLetters( $request->get('description', null));  // описание заявки
			$deal->tags 		 	= $request->get('tags'); // теги

			$deal->payment_status = OrganizationDeal::DEAL_STATUS_PAYMENT_PAID;
			// если объявление о покупке, то это пока всегда 'na'
			// if(!$isDealSell){
			// 	// $deal->subtype_deal = OrganizationDeal::DEAL_SUBTYPE_NA;
			// 	// $deal->budget_from  = $request->get('budget_from', 0);
			// 	// $deal->budget_to 	= $request->get('budget_to', 0); // бюджет до (сейчас просто - бюджет)

			// 	// $deal->date_of_event = $this->getBlankStringIsStringNullLetters( $request->get('date_of_event', ''));
			// 	// $deal->unit_for_unit = $this->getBlankStringIsStringNullLetters( $request->get('unit_for_unit', ''));
				
			// }
			// else{ // объявление о продаже
			// 	if($isProAccount){
			// 		$deal->payment_status = OrganizationDeal::DEAL_STATUS_PAYMENT_PAID;
			// 	}
			// 	else{
			// 		$deal->payment_status = OrganizationDeal::DEAL_STATUS_PAYMENT_PAID;
			// 	}
			// }
			
			// покупка
			if (!$isDealSell){

				$deal->deadline_deal 	= $this->getNullIsStringNullLetters( $request->get('deadline_deal', null)); // срок действия заявки
				$deal->unit_for_all  	= $this->getBlankStringIsStringNullLetters( $request->get('unit_for_all', ''));
				$deal->budget_from   	= $request->get('budget_from', 0);
				$deal->budget_to     	= $request->get('budget_to', 0);
				$deal->unit_for_unit 	= $this->getBlankStringIsStringNullLetters( $request->get('unit_for_unit', ''));
				$deal->budget_with_nds  = $request->get('budget_with_nds', 0);

				if ($deal->deadline_deal != null and (Carbon::parse($deal->deadline_deal) < Carbon::now()->addDays(30))) {
					$deal->planned_finish = $deal->deadline_deal; // планируемая дата окончания заявки
				}
				else{
					$deal->planned_finish = Carbon::now()->addDays(30);
				}

				$deal->date_of_event        = $this->getBlankStringIsStringNullLetters($request->get('date_of_event', 'не указано')); // дата проведения сделки, тут строка в свободной форме
			}
			
			// $deal->count_unit_in_volume = $request->get('count_unit_in_volume', 0); // количество единиц в общем объеме (например объем 5 тонн - кол-во единиц 1000 (в килограммах ,это указано в unit_for_unit))
			// $deal->procent 		        = $request->get('procent', 0); // наш процент от сделки (смотрим по таблице зависимости процента от бюджета сделки
			// $deal->val_for_all 	        = $request->get('val_for_all', 0);  // количество единиц товара (штук, ящиков...)
			// $deal->commission 	        = $request->get('commission', 0); // наша комиссия со сделки, в рублях

			//$deal->is_need_kp 	= $request->get('is_need_kp', 0); // надо ли коммерческое предложение требовать с другой стороны
			//$deal->deadline_service = $request->get('deadline_service' , null); // срок сбора предложений

			$deal->save();

			$deal->url = \App\Classes\Helpers\Translit::translitToUrl($deal->name) . '-' . $deal->id;// формириуем урл
			$deal->save();

			$deal->categories()->sync($request->categories);

			$deal->cities()->sync($request->cities);

			// покупка
			if (!$isDealSell){

				// вопросы к сделке от размещающего покупателя ===========================================
				$questions = $request->get('questions');
				$questions['dqh_specification']       = $deal->description;
				$questions['dqh_doc_confirm_quality'] = (isset($questions['dqh_doc_confirm_quality'])) ? $questions['dqh_doc_confirm_quality'] : 'не указано';
				$questions['dqh_type_deal'] 		  = (isset($questions['dqh_type_deal'])) ? $questions['dqh_type_deal'] : '';

				foreach ($questions as $slug => $value) {

					$question_id = $this->dealsQuestionsHeaderService->getRowValue('slug', $slug);
					if($question_id === null){
						continue;
					}

					$question_id = $question_id->id;
					$curQuestion = [
						'deal_id'     => $deal->id, 
						'question_id' => $question_id,
						'question'    => $value,
					];

					// если есть старое значение, то обновить
					$oldQuestion = $this->dealsQuestionsService->getRowIfRowValExists(['deal_id' => $deal->id,  'question_id' => $question_id]);

					if($oldQuestion){
						$this->dealsQuestionsService->update($oldQuestion->id, $curQuestion);
					}
					else{
						$this->dealsQuestionsService->create($curQuestion);
					}
				}

				// файл
				$file = $request->file('file');
				if($file){	

					$toFileService = [
						'file'   => $file,
						'user_id'=> $deal->user_id,
						'deal_id'=> $deal->id,
					];
			
					// если сам юзер загружает файл или админ за него
					if($user->id === (int)$deal->user_id or $user->isAdmin()){
						$dealsFile = $this->dealsFileService->create($toFileService);
			
						if( !($dealsFile instanceof DealsFile)){
							return $this->errorResponse('Объявление создано, но файл не загружен :' . $dealsFile);
						}
					}
				}

				// изображения
				$images = $request->file('images');
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
			}
			else{ // продажа

				// файлы
				// изображения
				$files = $request->file('files');
				if($files){		
					foreach ($files as $file) {
						if($file !== null){	
							$toFileService = [
								'file'   => $file,
								'user_id'=> $deal->user_id,
								'deal_id'=> $deal->id,
							];
					
							// если сам юзер загружает файл или админ за него
							if($user->id === (int)$deal->user_id or $user->isAdmin()){
								$dealsFile = $this->dealsFileService->create($toFileService);
					
								if( !($dealsFile instanceof DealsFile)){
									return $this->errorResponse('Объявление создано, но файл не загружен :' . $dealsFile);
								}
							}
						}
					}
				}
			}

		

			// ============================== Платность услуги, снимем деньги за объявление ============================================================
			//!!!!!!  рассылка писем о продаже перенесено в App\Http\Controllers\Admin\DealsController    moderateSuccess()
			if($isDealSell){
				// if($isProAccount){				
				// 	$this->paymentService->newUserSubscription($paymentInfo['subscription_id'], $user->id, $deal->id, $paymentInfo['service_name'] . ". Pro аккаунт", null, null, $isProAccount);
				// }
				// else{
				// 	$user->ballance -=  $paymentInfo['cost_of_service'];// снимем сразу сумму за  услугу
				// 	$user->save();
				// 	$user_subscription_id = $this->paymentService->newUserSubscription($paymentInfo['subscription_id'], $user->id, $deal->id, $paymentInfo['service_name'], null, null, $isProAccount);
				// 	// создадим запись в финансовых операциях
				// 	$rowFinanceOperation = (new \App\Services\FinanceOperation\FinanceOperationService())->storeLocalPayment($user, null, -$paymentInfo['cost_of_service'], "Снятие средств за подачу объявления: " . $paymentInfo['service_name'], $user_subscription_id);
				// }
			}
			// ==============================END  Платность услуги рассылки оповещений по эмайл ============================================================

			$deal->notify(new DealCreate()); // письмо , что юзер создал новый заказ и он проходит модерацию
			if ($deal->type_deal === 'sell')
			{
				$deal->notify(new SendSlackNewDealSell);
			}	
			else 
			{
				$deal->notify(new SendSlackNewDealCreate);
			}	
		// перенесено в App\Http\Controllers\Admin\DealsController    moderateSuccess()
		//	$deal->notify(new UserNewDealBuyMessage('added', $user, $deal)); // отправит по сокетам оповещения для юзеров

			return $this->successResponse([
				'id' => $deal->id
			 ]);

		}
		catch(Throwable $e){
			return $this->errorResponse($e->getMessage());
		}
	}

	// mvp tamtem
	// редактировать сделку  ================================================================================================================
	public function dealsUpdate(DealUpdateRequest $request, $id)
	{

		try{

			$user = Auth::guard('api')->user();
			$id  = (int) $id;

			//проверим, есть ли у юзера телефон 
			if(! $user->phoneExist()){
				return $this->errorResponse("Вы не можете редактировать заявку, пока не заполните номер телефона в своем личном кабинете!");
			}


			$deal = OrganizationDeal::where('id', '=', $id)->first();

			if(!$deal){
				return $this->errorResponse("Нет объявления с id = " . $id);
			}
			// если это сделка юзера  то давать редактировать, иначе лесом слать!!
			if((int)$user->id !==  (int) $deal->user()->first()->id){
				return $this->errorResponse("Вы не можете редактировать заявку, вы ее не создавали");
			}

			$isDealSell = ($deal->type_deal === OrganizationDeal::DEAL_TYPE_SELL) ? true : false;
			$isNeedPayDealSell = ($isDealSell and ($deal->status === OrganizationDeal::DEAL_STATUS_ARCHIVE or $deal->status === OrganizationDeal::DEAL_STATUS_BANNED or $deal->finish === true)) ? true : false;
			$isProAccount = false;
			// ============================== Платность услуги объявление о продаже ============================================================
			//> проверка возможности получить услугу в принципе и если заявка забанена или финишировала, то надо платить
			if($isNeedPayDealSell){
				$paymentInfo = $this->paymentService->paymentServiceIsPossible($request, Subscription::SUBSCRIPTION_PAYMENT_ONCE_DEAL_SELL , true);
				$isProAccount = $paymentInfo['is_pro_account'];

				if($paymentInfo['is_possible'] === false){
					return $this->errorResponse($paymentInfo['message']);
				}
			}
			//< ==============================END Платность услуги ============================================================

			$prev_name = $deal->name;
			$prev_description = $deal->description;

			$images = $request->images; // получили фотки
			$type_deal = $request->get('type_deal');

			if($type_deal !== $deal->type_deal){
				return $this->errorResponse("Вы не можете изменять тип сделки !");
			}

			$subtype_deal = $request->get('subtype_deal', OrganizationDeal::DEAL_SUBTYPE_NA);
			$deal->subtype_deal = $subtype_deal; // подтип сделки (сейчас: новое - new, бу - used, без подтипа - na)
			// если объявление о покупке, то это пока всегда 'na'
			if(!$isDealSell){
				$deal->subtype_deal = OrganizationDeal::DEAL_SUBTYPE_NA;
				$deal->budget_from = $request->get('budget_from', 0);
				$deal->budget_to 	= $request->get('budget_to', 0); // бюджет до (сейчас просто - бюджет)

		//		$deal->date_of_event = $this->getBlankStringIsStringNullLetters( $request->get('date_of_event', ''));
				$deal->unit_for_unit = $this->getBlankStringIsStringNullLetters( $request->get('unit_for_unit', ''));
			}
			else{
				$deal->payment_status = OrganizationDeal::DEAL_STATUS_PAYMENT_PAID;
			}

			// // проверим, может ли юзер давать объявление о продаже
			// if($type_deal === OrganizationDeal::DEAL_TYPE_SELL and ! ($user->is_org_created)){
			// 	return $this->errorResponse("Вы не можете давать объявления о продаже, пока не зарегистрируете организацию!");
			// }

			// организация юзера
			//$organization = $user->organization();

			// сделка
			//$deal->type_deal 	= $type_deal; // тип сделки (сейчас: покупка - buy, продажа - sell)
			$deal->name 		= $request->get('name'); // название (заголовок) заявки
			$deal->description 	= $this->getNullIsStringNullLetters( $request->get('description', null));  // описание заявки
			//$deal->budget_to 	= $request->get('budget_to', 0); // бюджет до (сейчас просто - бюджет)
			$deal->deadline_deal = $this->getNullIsStringNullLetters( $request->get('deadline_deal', null)); // срок действия заявки

			if ($deal->deadline_deal != null and (Carbon::parse($deal->deadline_deal) < Carbon::now()->addDays(30))) {
				$deal->planned_finish = $deal->deadline_deal; // планируемая дата окончания заявки
			}

			$deal->is_need_kp 	= $request->get('is_need_kp', 0); // надо ли коммерческое предложение требовать с другой стороны
			// // если поменяли текст или картунку загрузили - отправляем на модерацию
			// if($prev_name !== $deal->name or  $prev_description !== $deal->description or $images !== null){
			// 	$deal->status = OrganizationDeal::DEAL_STATUS_MODERATION;
			// }

			$deal->status = OrganizationDeal::DEAL_STATUS_MODERATION;
			$deal->finish = false;

			$deal->save();

			if ($request->get('cities')){
				$deal->cities()->sync($request->cities);
			}

			$deal->categories()->sync($request->categories);

			// файл
			$file = $request->file;
			if($file){	
				
				$toFileService = [
					'file'   => $file,
					'user_id'=> $deal->user_id,
					'deal_id'=> $deal->id,
				];

				// если сам юзер загружает файл или админ за него
				if($user->id === (int)$deal->user_id or $user->isAdmin()){
					$dealsFile = $this->dealsFileService->create($toFileService);

					if( !($dealsFile instanceof DealsFile)){
						return $this->errorResponse('Объявление создано, но файл не загружен :' . $dealsFile);
					}
				}
			}


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


			// ============================== Платность услуги, снимем деньги за объявление ============================================================
			//!!!!!!  рассылка писем о продаже перенесено в App\Http\Controllers\Admin\DealsController    moderateSuccess()
			if($isNeedPayDealSell){
				if($isProAccount){				
					$this->paymentService->newUserSubscription($paymentInfo['subscription_id'], $user->id, $deal->id, $paymentInfo['service_name'] . ". Pro аккаунт. Восстановление сделки", null, null, $isProAccount);
				}
				else{
					$user->ballance -=  $paymentInfo['cost_of_service'];// снимем сразу сумму за  услугу
					$user->save();
					$user_subscription_id = $this->paymentService->newUserSubscription($paymentInfo['subscription_id'], $user->id, $deal->id, $paymentInfo['service_name'] . ". Восстановление сделки", null, null, $isProAccount);
					// создадим запись в финансовых операциях
					$rowFinanceOperation = (new \App\Services\FinanceOperation\FinanceOperationService())->storeLocalPayment($user, null, -$paymentInfo['cost_of_service'], "Снятие средств за: " . $paymentInfo['service_name'] . ". Восстановление сделки", $user_subscription_id);
				}
			}
			// ==============================END  Платность услуги рассылки оповещений по эмайл ============================================================


			// перенесено в App\Http\Controllers\Admin\DealsController    moderateSuccess()
			$deal->notify(new UserNewDealBuyMessage('goto_moderation_with_update', $user, $deal)); // отправит по сокетам оповещения для юзеров
		//	$deal->notify(new UserNewDealBuyMessage('updated', $user, $deal)); // отправит по сокетам оповещения для юзеров
			$deal->notify(new DealUpdate());

			return $this->successResponse(
				//[$deal]
			);

		}
		catch(Throwable $e){
			return $this->errorResponse($e->getMessage());
		}
	
	}


	// mvp tamtem
	// покать сделку по ее id   ==========================================================================================================
	public function dealsShow($id)
	{
		
		try{

			$id = (int) $id;
			$user = Auth::guard('api')->user();

			if (!$deal = OrganizationDeal::with(['organization', 'categories', 'cities', 'user', 'questions', 'files'])->find($id)){
				throw new \App\Exceptions\NotFoundException();
			}
			// //dd($user->organization_id);
			// dd($deal->isUserMemberDeal($user));
//			dd($deal->toArray());
		
			// если юзер не авторизован и заказ завершен - не показывать
			if(!$user and (
                $deal->status === OrganizationDeal::DEAL_STATUS_ARCHIVE or
                $deal->trading_status === OrganizationDeal::DEAL_TRADING_STATUS_FINISHED)
            or $deal->status === OrganizationDeal::DEAL_STATUS_BANNED){
				return $this->errorResponse();
			}

			$isDealMy = false; // моя ли сделка
			// если показать мою сделку
			if ($user && $deal->organization_id == $user->organization_id) {	
				$deal['members'] = $deal->getAnswersAndQuestionsByMember();
				$isDealMy = true;
			}
			elseif($deal->isUserMemberDeal($user)){ // если пользователь  - участник сделки
				$deal['members'] = $deal->getAnswersAndQuestionsByMember($user->organization_id);	//	 dd($deal->toArray());
				// if(isset($deal['members']['trading_status']) === OrganizationDeal::DEAL_TRADING_STATUS_BANNED){
				// 	unset($deal['members']);
				// }
			}
			else{ // добавим просмотр к объявлению, если смотрел не сам
				$deal->increment('count_views');
			}

			// если залогинен и сделка чужая
			if (!$isDealMy and $user){
				//вернуть первые 2 слова из названия сделки
				$tag = TagController::getFirstWordsBeforeTheComma($deal->name);
				// закрепить тэг за юзером, как интересующий
				if($tagId = (new TagController())->storeTag($tag)){
					$user->company->tags()->syncWithoutDetaching($tagId);
				}

				// добавим в просмотренные юзером
				$user->userViewDeal()->syncWithoutDetaching($deal->id);
			}
			
			return $this->successResponse(
				DealsItemFormatter::format($deal)
			);
		}
		catch(Throwable $e){
			return $this->errorResponse($e->getMessage());
		}
	}

	// mvp tamtem
	// покать сделку по ее url   ==========================================================================================================
	public function dealsFromUrlShow($url)
	{
		
		try{
			$v = Validator::make(['url' => $url], [
				'url' => 'required|exists:organizations_deals,url',
			]);
	
			if ($v->fails())
			{
				return response()->json(['result' => false, 'error' => $v->errors()]);
			}

			$user = Auth::guard('api')->user();

			if (!$deal = OrganizationDeal::with(['organization', 'categories', 'cities', 'user', 'questions', 'files'])->where('url', '=', $url)->first()){
				throw new \App\Exceptions\NotFoundException();
			}
		
			// если юзер не авторизован и заказ завершен - не показывать
			if(!$user and ($deal->status === OrganizationDeal::DEAL_STATUS_ARCHIVE or $deal->trading_status === OrganizationDeal::DEAL_TRADING_STATUS_FINISHED)){
				return $this->errorResponse();
			}

			$isDealMy = false; // моя ли сделка
			$isTypeDealBuy = ($deal->typeDeal === OrganizationDeal::DEAL_TYPE_BUY) ? true : false;
			$isDealActive  = ($deal->status === OrganizationDeal::DEAL_STATUS_APPROVE) ? true : false;

			// если показать мою сделку
			if ($user && $deal->organization_id == $user->organization_id) {	
				// если это покупка
				if ($isTypeDealBuy){
					$deal['members'] = $deal->getAnswersAndQuestionsByMember();
				}
				$isDealMy = true;
			}
			elseif($isTypeDealBuy === true && $deal->isUserMemberDeal($user) && $isDealActive === true){ // если пользователь  - участник сделки и это покупка
				$deal['members'] = $deal->getAnswersAndQuestionsByMember($user->organization_id);	//	 dd($deal->toArray());
			}
			elseif($isDealActive === true){ // добавим просмотр к объявлению, если смотрел не сам
				$deal->increment('count_views');
			}
			else{
				return $this->errorResponse('Deal is not active');
			}

			// // если залогинен и сделка чужая
			// if (!$isDealMy and $user){
			// 	//вернуть первые 2 слова из названия сделки
			// 	$tag = TagController::getFirstWordsBeforeTheComma($deal->name);
			// 	// закрепить тэг за юзером, как интересующий
			// 	if($tagId = (new TagController())->storeTag($tag)){
			// 		$user->company->tags()->syncWithoutDetaching($tagId);
			// 	}

			// 	// добавим в просмотренные юзером
			// 	$user->userViewDeal()->syncWithoutDetaching($deal->id);
			// }
			
			return $this->successResponse(
				DealsItemFormatter::format($deal)
			);
		}
		catch(Throwable $e){
			return $this->errorResponse($e->getMessage());
		}
	}

	// mvp tamtem
	// удалить картинку по ее id   ===========================================================================================================
	public function dealsImageDelete(Request $request)
	{
		try{

			$id = $request->input('id', null);

			if($id === null){
				return $this->errorResponse("Отсутствует id");
			}
		
			$id = (int) $id;

			$user = Auth::guard('api')->user();
			$image  = ImagesDeals::findOrFail($id);
			
			// если id текущего юзера = id юзера, чья картинка, то удалять даем
			$idUserOwnerImage = $image->deal()->first()->user()->first()->id;
			if($idUserOwnerImage !== $user->id){
				$this->errorResponse("Нельзя удалить изображение, загруженное не вами!!!");
			}

			// удаляем изображение
			if(!$image->deleteImageDeal($id)){
				$this->errorResponse("Ошибка удаления изображения. Обратитесь в службу техподдержки или попробуйте повторить операцию позднее.");
			}

			// удалим строку из базы
			$image->delete();
	
			// if($images->uploadFileAndSaveToDB($image, $deal->user_id, $deal->id, config('b2b.images.resizeValMaxPx')) === false){
			return $this->successResponse();

		}
		catch(Throwable $e){
			return $this->errorResponse($e->getMessage());
		}

	}

	
	/**
	 * dealsTake
	 *
	 *  заявка на участие в сделке (Отклик)
	 * 
	 * @param  mixed $request
	 * @param  mixed $id
	 *
	 * @return void
	 */
	public function dealsTake(DealTakeRequest $request, $id	)
	{
		$user = Auth::guard('api')->user();
		$id = (int) $id;

		// для отклика должна быть создана организация у откликающегося
		if(! $user->isOrgCreated()){
			return $this->errorResponse("Нельзя откликнуться, если у вас не создана организация");
		}

		$organization_id  = $user->organization_id;

		if (!$deal = OrganizationDeal::find($id)){
			return $this->errorResponse("Нет сделки с id = " . $id);
		}

		$questionsWithSlugs = $deal->questionsWithSlugs()->toArray();
		$dealId = $deal->id;

		// нельзя откликаться на свою заявку и на объяву о продаже
		if ($deal->organization_id == $user->organization_id){
			return $this->errorResponse("Нельзя откликаться на свою же сделку");
		}

		// нельзя участвовать, в сделке, если уже участвуешь
		if ($deal->members()->where('organization_id', $organization_id)->first()){

			// если отклик был забанен , то его удаляем вместе с ответами, чтобы откликнуться дать еще раз
			$member = OrganizationDealMember::where('deal_id', $dealId)->where('organization_id', $user->organization_id)->first();
			if($member->trading_status ===OrganizationDeal::DEAL_TRADING_STATUS_BANNED){
				$deal->members()->detach($member->organization_id);
				$member->answers()->delete();

			}
			else{
				return $this->errorResponse("Вы уже участвуете в сделке. Нельзя сделать это еще раз");
			}
		}

		// Старт транзакции!
		DB::beginTransaction();

		try {

			// участвуем с сделке
			$deal->members()->attach($user->organization_id, [
				'trading_status' => OrganizationDeal::DEAL_TRADING_STATUS_MODERATION,
				'price_offer' => $request->get('price_offer', 0),
				'is_shipping_included' => $request->get('is_shipping_included', false),
				'notice' => $request->get('notice') ?? "",
				]);
			
			$memberId = OrganizationDealMember::where('deal_id', $dealId)->where('organization_id', $user->organization_id)->first()->id;

			//записываем ответы на вопросы
			$questions = $request->get('questions'); //dd($questions);
			$organizationDealAnswerService    = new OrganizationDealAnswerService();
			
			foreach ($questions as $slug => $value) {
				$question_id = $questionsWithSlugs[$slug]['id'];
				if($question_id === null){
					continue;
				}

				$curAnswer = [
					'member_id'    => $memberId, 
					'deal_id'     => $dealId, 
					'organization_id' => $organization_id ,
					'question_id' => $question_id,
					'is_agree'    => $value,
				];

				// если есть старое значение, то обновить
				$oldAnswer = $organizationDealAnswerService->getRowIfRowValExists(['deal_id' =>  $dealId,  'organization_id' => $organization_id,  'question_id' => $question_id]);

				if($oldAnswer){
					$organizationDealAnswerService->update($oldAnswer->id, $curAnswer);
				}
				else{
					$organizationDealAnswerService->create($curAnswer);
				}

			}	

	
			// изображения
			$images = $request->images;
			if($images){		
				foreach ($images as $image) {

					$toFileService = [
						'file'   => $image,
						'user_id'=> $user->id,
						'response_id'=> $memberId,
					];
			
					// если сам юзер загружает файл или админ за него
					$responseFile = $this->responsesFileService->create($toFileService);
			
					if( !($responseFile instanceof ResponsesFile)){
						DB::rollback();
						return $this->errorResponse('Объявление создано, но файл не загружен :' . $responseFile);
					}
				}
			}
		} 
		catch(ValidationException $e)
		{
			// Откат и редирект на страницу с ошибкой
			DB::rollback();
			return $this->errorResponse($e->getErrors());
		}
		catch(\Throwable $e)
		{
			// Откат
			DB::rollback();
			return $this->errorResponse($e->getMessage());
		}

		// Если всё хорошо - фиксируем
		DB::commit();
		$deal->notify(new SendSlackNewResponseForManager);
		return $this->successResponse(['response_id' => $memberId]);
	}

	
	/**
	 * dealsSetWinner - выбор победителя в сделке
	 * 
	 * @param  mixed $request
	 * @param  mixed $id
	 *
	 * @return void
	 */
	public function dealsSetWinner(Request $request, $id)
	{
		$user = Auth::guard('api')->user();
		$id = (int)$id;

		if ((!$deal = OrganizationDeal::find($id)) || ($user->organization_id != $deal->organization_id)){
			throw new \App\Exceptions\NotFoundException();
		}
	
		if (!$request->get('winner_id') || $deal->winner_id != null){
			return $this->errorResponse('Победитель уже выбран ранее');
		} 
	
		if (!$winner = $deal->membersFromOrganizationMembersTable()->where('id', $request->get('winner_id'))->first()){
			return $this->errorResponse();
		}

		// проставили участнику - ожидание оплаты
		$winner->trading_status = OrganizationDeal::DEAL_TRADING_STATUS_WAITING_PAYMENT;
		$winner->is_readed_owner_response = false;
		$winner->save();

		$deal->winner_id = OrganizationDealMember::find($request->get('winner_id'))->organization_id;
		$deal->trading_status = OrganizationDeal::DEAL_TRADING_STATUS_WAITING_PAYMENT;
		$deal->save();

		// user - победитель
		$winnerUser = $winner->user();

		
		$idsDealsBuyContacts = $winnerUser->idsDealsBuyContacts(); // id объявлений с проплаченными контактами
		$commission = count($idsDealsBuyContacts) > 0 ? $deal->commission : 200;

		// Начислим балл в статистику, если она есть
		if($statistic = $user->organization()->first()->statistic()->first()){
			$statistic->increment('count_set_winners');
		}

		// попытка оплаты контактов по сделке
		$resultPayment = $this->paymentService->paymentSubscription($request, Subscription::SUBSCRIPTION_PAYMENT_ONCE_DEAL_BUY_GET_CONTACTS, true, $id, $winnerUser, round($commission, 0, PHP_ROUND_HALF_DOWN));
//  dd( $resultPayment);

		// проплачены ли победителем контакты сделки
		$idsDealsBuyContacts = $winnerUser->idsDealsBuyContacts(); // id объявлений с проплаченными контактами
		$isPayed = isset($idsDealsBuyContacts[$deal->id]);

		if($isPayed){

			$deal->trading_status = OrganizationDeal::DEAL_TRADING_STATUS_FINISHED;

			//надо проставить всем участникам - что сделка по торгам завершена
			$deal->membersFromOrganizationMembersTable()->update(['trading_status' => OrganizationDeal::DEAL_TRADING_STATUS_FINISHED]);
			$winner->is_readed_owner_response = false; // оплатили, значит откик в не прочитанные
			$winner->save();

			// надо закрыть сделку
			if($this->dealsFinish($request, $deal->id, true) === false){
				return $this->errorResponse('ошибка при закрытии сделки');
			}

			// надо послать победителю письмо, что его выбрали и все оплачено
			$deal->notify(new DealWinner("Прошла оплата получения контактов сделки в размере " .  round($commission, 0, PHP_ROUND_HALF_DOWN) . 
								" руб. из вашего кошелька. " . PHP_EOL . PHP_EOL . " Теперь вы можете общаться с вашим партнером по сделке."));

			// надо послать победителю письмо, что его выбрали и все оплачено
			$deal->notify(new DealWinnerResponse( url('bids/' . $deal->id), round($commission, 0, PHP_ROUND_HALF_DOWN)));


			// надо создать диалог по данной сделке между продавцом и покупателем и послеть первое сообщение
			if(!$dialogId = DialogsController::newLocalDialog($deal->id, $deal->winner_id, $deal->organization_id)){
				return $this->errorResponse('ошибка создания диалога');
			}
			if(!$messageId = DialogsController::sendLocal($dialogId, $user, 'Мои контакты у Вас есть, можно обговорить детали')){
				return $this->errorResponse('ошибка отправки сообщения продавцу');
			}
 
		}
		else{ // если неоплачено

			// надо послать победютелю письмо, чтот надо оплатить, у него сутки на это и т.п.
			$deal->notify(new DealWinner("Чтобы получить контакты по сделке, необходимо оплатить " .  round($commission, 0, PHP_ROUND_HALF_DOWN) . 
								" руб. в течение 72 часов, начиная с текущего момента. " . 
								"При отсутствии оплаты, <b>вы будете удалены из участников сделки</b>. " . 
								"Пополните свой кошелек в личном кабинете." . 
								"<p>Информируем Вас о гарантии, которую мы предоставляем:</p>" .
								"<ul style='text-align: left'><li>Во-первых, Вы платите только за результат, а именно после выбора покупателем Вас и Вашего предложения.</li><br><li>Во-вторых, Мы гарантируем возврат денежных средств - при не состоявшейся сделке по вине заказчика, не просто «на словах», а на основании договора публичной оферты, с которым Вы согласились при регистрации.</li><br><li>В-третьих, оплату контакта вы сможете совершить как через расчетный счет, так и посредством дебетовой карты.</li></ul>" .
								"<p>Спасибо Вам за уделенное внимание, возможно у Вас появились вопросы?<br>Позвоните по номеру - +7 930 720 23 00 готовы на них ответить.</p>" .
								"<p><a style='color: #ffffff; display: block; padding: 10px 15px; border-radius: 32px; background-color: #2fc06e; font-family: Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 600; line-height: 15px; text-decoration: none;text-align: center; width: 200px; margin: 0 auto;' href=" . url('bids/' . $deal->id). ">Оплатить заказ</a></p>"
							));

			//	Mail::to(config('b2b.email.callme'))->send(new SendToManagers( '+7' . $request->get('phone')));
			$messageToManagers = "Выявлен победитель сделки №" . $deal->id . " (" . $deal->name .") - это пользователь: " .  $winnerUser->name . ", с телефоном: +7" .  $winnerUser->phone . 
							 ". Он не оплатил. Срочно позвоните ему, чтобы он оплатил покупку контактов по этой сделке в размере " 
							 . round($commission, 0, PHP_ROUND_HALF_DOWN) . " руб.";

			Mail::to(config('b2b.email.managers'))->send(new SendToManagers( (string) $messageToManagers));

			// проставить время, до которого можно оплатить
			$deal->winner_wating_payment_at = Carbon::now()->addHours(336);
		}

		$deal->save();

		// нотификация продавцу, что выбран такой -то победитель
	 	$deal->notify(new DealSetWinner());
		$deal->notify(new SendSlackChooseWinner);
		return $this->successResponse();
	}

	// mvp tamtem
	//Завершение сделки - по сути это отмечаем  на удаление  =============================================================================
 	public function dealsFinish(Request $request, $id, $isLocalRequest = false)
	{

		try{

			if (!$isLocalRequest){

				$user = Auth::guard('api')->user();

				if ((!$deal = OrganizationDeal::find($id)) || ($user->organization_id != $deal->organization_id))
					throw new \App\Exceptions\NotFoundException();

				// если это сделка юзера  то давать редактировать, иначе лесом слать!!
				if((int)$user->id !==  (int) $deal->user()->first()->id){
					return $this->errorResponse("Вы не можете редактировать объявление, вы ее не создавали");
				}
		
				// если заява уже завершена, то удаляем ее 
				if ($deal->finish){
					$deal->delete();
					return $this->successResponse('Заявка удалена');
					//return $this->errorResponse('Заявка уже помещена в завершенные');
				}


			}
			else{ // локальный запрос
				if (!$deal = OrganizationDeal::find($id)){
					return false;
				}
			}

			$deal->finish = true;
			$deal->finished_at = Carbon::now();
			$deal->status = OrganizationDeal::DEAL_STATUS_ARCHIVE; 
			if($deal->type_deal === OrganizationDeal::DEAL_TYPE_BUY){
				$deal->trading_status = OrganizationDeal::DEAL_TRADING_STATUS_FINISHED; 
				// $deal->winner_id = null; 
				// $deal->winner_wating_payment_at = null; 
			}

			$deal->save();

			// ============================== Платность услуги объявление о продаже ============================================================
			if($deal->type_deal === OrganizationDeal::DEAL_TYPE_SELL){
					$deal->finishPaymentAllService();
			}
			// ==============================END Платность услуги объявление о продаже ============================================================
			if($deal->type_deal === OrganizationDeal::DEAL_TYPE_BUY){
				$deal->finishForMembers();
			}

			$deal->notify(new UserNewDealBuyMessage('deleted', $user, $deal)); // отправит по сокетам оповещения для юзеров

			if (!$isLocalRequest) {
				return $this->successResponse();
			}
			else{
				return true;
			}

		}
		catch(Throwable $e){
			return $this->errorResponse($e->getMessage());
		}
		
	}
	
	/**
	 * finishNotPayedResponsesAfterSpecifiedHours - выбрали поставщика и он не заплатил, удаляем его
	 *
	 * @return void
	 */
	public function finishNotPayedResponsesAfterSpecifiedHours()
	{

			$dealsWaitingPaymentResponses = OrganizationDeal::where('finish' , '=', false)->with('membersFromOrganizationMembersTable')
				->whereDate('winner_wating_payment_at' , '<' , Carbon::now()) // вышла крайняя дата оплаты
				->whereNotNull('winner_id')
				->where('trading_status', '=', OrganizationDeal::DEAL_TRADING_STATUS_WAITING_PAYMENT)
				->orderBy('created_at', 'DESC');


			foreach ($dealsWaitingPaymentResponses->get() as $key => $deal) {

				 $curWinnerId = $deal->winner_id;

				 // если победитель, то проставляем ему banned ибо не заплатил
				 foreach($deal->membersFromOrganizationMembersTable as $curMember){
					if($curMember->organization_id === $curWinnerId){ // если победитель, то проставляем ему banned ибо не заплатил
						$curMember->trading_status = OrganizationDeal::DEAL_TRADING_STATUS_BANNED;
						$curMember->is_readed_owner_response = true;
						$curMember->save();
					}
				 }

				// письмо продавцу, чтобы выбирал другого поставщика
				$deal->notify(new DealNeedNewWinner());
			}	

			// проставим сделке статус ожидания выбора, снимем время оплаты и удалим победителя
			$counntUpdated = $dealsWaitingPaymentResponses->update([
				'trading_status' => OrganizationDeal::DEAL_TRADING_STATUS_WAITING_WINNER,
				'winner_id' => null,
				'winner_wating_payment_at' => null,
			]);

			return $counntUpdated;
	}

	/**
	 * finishDealsAfterPlannedFinish - закрываем сделки старше planned_finish (она расчитывается или указывается при создании сделки)
	 *
	 * @return void
	 */
	public function finishDealsAfterPlannedFinish()
	{
		$nowDate = Carbon::now();

		// сделки истекли -закрыть
		$dealsToDelete = OrganizationDeal::where('finish' , '=', false)
			->whereDate('planned_finish' , '<=' , $nowDate)
			->where('status' , '=' , OrganizationDeal::DEAL_STATUS_APPROVE)
			->orderBy('published_at', 'DESC');

		// закроем сделку для участников
		foreach ($dealsToDelete->get() as $key => $deal) {
			$deal->finishForMembers();	
		}	
			 
		//закроем сами основные сделки 
		return $dealsToDelete->update(['finish' => true, 'finished_at' => $nowDate, 'status' => OrganizationDeal::DEAL_STATUS_ARCHIVE]);
			
	}
	/**
	 * finishDealsAfterPlannedFinish - закрываем сделки старше planned_finish (она расчитывается или указывается при создании сделки)
	 *
	 * @return void
	 */

	/**
	 * needRefreshDealsBeforeSpecifiedDays - если до окончания заказа указанное количество дней, то его необходимо продлить - шлем письмо менеджерам
	 *
	 * @param  integer $beforeDays
	 *
	 * @return void
	 */
	public function needRefreshDealsBeforeSpecifiedDays($beforeDays = 5)
	{
		$nowDate = Carbon::now();

		// массив id сделок , у которых до их окончания осталось 5 дней
		$idsDealsTo5DaysBeforeDelete = OrganizationDeal::where('finish' , '=', false)
				->where(DB::raw('DATE_FORMAT(planned_finish,"%Y-%m-%d")'), '=', $nowDate->addDays($beforeDays)->format('Y-m-d'))
				->where('status' , '=' , OrganizationDeal::DEAL_STATUS_APPROVE)
				->orderBy('published_at', 'DESC')
				->pluck('id')->toArray();

		if(count($idsDealsTo5DaysBeforeDelete)>0){

			$messageToManagers = 'Заказы с номерами: ' . implode(",", $idsDealsTo5DaysBeforeDelete) . " надо продлить.";
			$title = 'Необходимо продлить заказ';
			$subject = 'Отделу сопровождения надо продлить заказ';
			Mail::to(config('b2b.email.managers'))->send(new SendToManagers( (string) $messageToManagers, $title, $subject));
		}

		return true;
			
	}

	public function runDeleteDealsBeforeToDateWorker()
	{

		try{

			//выбрали поставщика и он не заплатил, удаляем его
			$this->finishNotPayedResponsesAfterSpecifiedHours();

			//если до окончания заказа указанное количество дней, то его необходимо продлить - шлем письмо менеджерам
			$this->needRefreshDealsBeforeSpecifiedDays(5);

			// TODO закрываем сделки старше planned_finish (она расчитывается или указывается при создании сделки)
			$this->finishDealsAfterPlannedFinish();
		
			// остановим подписки
			$SubsciptionToFinished = UserSubscription::where('status' , '<>', Subscription::SUBSCRIPTION_STATUS_FINISHED)
										 ->whereRaw('duration_days <> 0')
										 ->whereRaw('started_at < (now() - interval `duration_days` day)')
										 ->orderBy('started_at', 'DESC')
										 ->update(['status' => Subscription::SUBSCRIPTION_STATUS_FINISHED, 'finished_at' => Carbon::now()]);
										 
		}
		catch(Throwable $e){
			return $this->errorResponse($e->getMessage());
		}
		
	}

	// mvp tamtem
	// список всех удаленных заявок вообще  ===============================================================================================
	public function deletedDealsList(Request $request)
	{
		$user = Auth::guard('api')->user(); // todo удалить при чистке
		$userId = $user->id;
		$status = OrganizationDeal::DEAL_STATUS_ARCHIVE;

		if($user->role === User::ROLE_ADMINISTRATOR){
			$userId = null;
			$status = $request->input('status', OrganizationDeal::DEAL_STATUS_ARCHIVE);
		}
		
		if (!in_array($status, [OrganizationDeal::DEAL_STATUS_MODERATION,
								OrganizationDeal::DEAL_STATUS_APPROVE,  
								OrganizationDeal::DEAL_STATUS_ARCHIVE,   
								OrganizationDeal::DEAL_STATUS_BANNED])){
			$status = OrganizationDeal::DEAL_STATUS_ARCHIVE;
		}

		try{

			$ret =  $this->successResponse(
				DealsItemFormatter::formatPaginator(
					FilterDealsRepository::filter(false, false, false,  false, false, false, false, 1 , null, 
								false, false , false, false, false, false,false, null, $status, $userId  )->simplePaginate()
				)
			);

			return $ret;
		}
		catch(Throwable $e){
			return $this->errorResponse($e->getMessage());
		}
	}

	// mvp tamtem
	// восстановить заявку  ===============================================================================================
	public function restoreDeal(Request $request, $id)
	{
		try{

			$user = Auth::guard('api')->user();
			$id  = (int) $id;

			$deal = OrganizationDeal::where('id', '=', $id)->first();
			$type_deal =$deal->type_deal;

			if(!$deal){
				return $this->errorResponse("Нет объявления с id = " . $id);
			}

			if($user->role !== User::ROLE_ADMINISTRATOR){
				// если это сделка юзера  то давать восстановить, иначе лесом слать!!
				if((int)$user->id !==  (int) $deal->user()->first()->id){
					return $this->errorResponse();
				}
			}
			$isDealSell = false;
			// ============================== Платность услуги объявление о продаже ============================================================
			//> проверка возможности получить услугу в принципе
			if($type_deal === OrganizationDeal::DEAL_TYPE_SELL){
				$paymentInfo = $this->paymentService->paymentServiceIsPossible($request, Subscription::SUBSCRIPTION_PAYMENT_ONCE_DEAL_SELL , true);
				$isDealSell = true;
				$isProAccount = $paymentInfo['is_pro_account'];
				if($paymentInfo['is_possible'] === false){
					return $this->errorResponse($paymentInfo['message']);
				}
			}
			//< ==============================END Платность услуги объявление о продаже ============================================================

			$deal->count_views = 0;
			$deal->finished_at = null;
			$deal->finish = false;
			$deal->status = OrganizationDeal::DEAL_STATUS_MODERATION;
			$deal->planned_finish = Carbon::now()->addDays(30);

			$deal->save();

			// ============================== Платность услуги объявление о продаже ============================================================
			if($isDealSell){
				if($isProAccount){
					$this->paymentService->newUserSubscription($paymentInfo['subscription_id'], $user->id, $deal->id, $paymentInfo['service_name'] . ". Восстановленная из архива. Pro аккаунт", null, null, $isProAccount);
				}
				else{
					$user->ballance -=  $paymentInfo['cost_of_service'];// снимем сразу сумму за  услугу
					$user->save();
					$user_subscription_id = $this->paymentService->newUserSubscription($paymentInfo['subscription_id'], $user->id, $deal->id, $paymentInfo['service_name'] . ". Восстановленная из архива.", null, null, $isProAccount);
					// создадим запись в финансовых операциях
					$rowFinanceOperation = (new \App\Services\FinanceOperation\FinanceOperationService())->storeLocalPayment($user, null, -$paymentInfo['cost_of_service'], "Снятие средств за: " . $paymentInfo['service_name'] . ". Восстановленная из архива.", $user_subscription_id);
				}

			}

			return $this->successResponse();

		}
		catch(Throwable $e){
			return $this->errorResponse($e->getMessage());
		}
	}

	
	/**
	 * Получить список откликов текущего пользователя
	 *  
	 * @param  mixed $request
	 *
	 * @return void
	 */
	public function getResponsesForCurrentUser(Request $request)
	{
		$user = Auth::guard('api')->user();
		
		try{
		   
			// для отклика должна быть создана организация у откликающегося
			if(! $user->isOrgCreated()){
				return $this->errorResponse("Чтобы начать работать с откликами, нужно сперва создать организацию");
			}

			$organization_id  = $user->organization_id;

			$responses = OrganizationDealMember::with(['deal', 'files'])->where('organization_id',  $organization_id )
				->where('trading_status', "<>", OrganizationDeal::DEAL_TRADING_STATUS_NA)
				->where('trading_status', "<>", OrganizationDeal::DEAL_TRADING_STATUS_BANNED)
			//	->where('trading_status', "<>", OrganizationDeal::DEAL_TRADING_STATUS_MODERATION)
				->with('deal.questions', 'deal.questions.questionHeader', 'deal.files')
				;

			// отбросим те, которые завершены и не мои
			$toPaginate = $responses->get();
			$toPaginate = $toPaginate->map(function($item, $key) use ($organization_id){
				if($item->trading_status === OrganizationDeal::DEAL_TRADING_STATUS_FINISHED and ($item->deal->winner_id === null or $item->deal->winner_id !==  $organization_id)){
					$item = null;
				}
				return $item;
			})->reject(function ($item) {
				    return is_null($item);
			})->values();

			if (!$toPaginate or $toPaginate->count() === 0){
				return $this->successResponse($toPaginate);
               // return $this->errorResponse("У вас нет откликов");
			}

		//	$paginateCollection = $this->paginateCollection($toPaginate,  $request->input('per_page', 10), $request->input('page', 1), ['path' => $request->url(), 'query' => $request->query()]);
			$paginateCollection = $this->paginateCollectionNotArrayToPaginator($toPaginate,  $request->input('per_page', 10), $request->input('page', 1), ['path' => $request->url(), 'query' => $request->query()]);

			return $this->successResponse(DealsResponsesCurrentUserItemFormatter::formatPaginator($paginateCollection));
			//return $this->successResponse(DealsResponsesCurrentUserItemFormatter::formatPaginator($responses->paginate($request->input('per_page', 10))));
       
		}
		catch(Throwable $e){
			return $this->errorResponse($e->getMessage());
		}
	}

	/**
	 * finishResponseCurrentUser - выйти из своего отклика (удалить его)
	 *
	 * @param  mixed $request
	 * @param  integer $id
	 * @param  mixed $isLocalRequest
	 *
	 * @return void
	 */
	public function finishResponseCurrentUser(Request $request, $id, $isLocalRequest = false)
	{


		$user = Auth::guard('api')->user();
		$id = (int) $id;

		try{
		   
			$organization_id  = $user->organization_id;
			$response = OrganizationDealMember::where('id', '=',  $id)->where('organization_id', '=', $organization_id )->with('answers', 'deal')->first();

			if (!$response){
				return $this->errorResponse($response);
			}

			// если победитель, но не оплачено, то завершить и известить  покупателя, что надо выбрать другого победителя
			if($response->deal->winner_id === $organization_id){
				if($response->deal->trading_status === OrganizationDeal::DEAL_TRADING_STATUS_WAITING_PAYMENT){
					$response->deal->trading_status = OrganizationDeal::DEAL_TRADING_STATUS_WAITING_WINNER; 
				}
				$response->deal->winner_id = null; 
				$response->deal->winner_wating_payment_at = null; 
				$response->deal->save();
				// письмо продавцу, чтобы выбирал другого поставщика
				$response->deal->notify(new DealNeedNewWinner());
			}

			// удалить ответы 
			foreach($response->answers as $answer){
				$answer->delete();
			}

			// удалить файлы
			foreach($response->files as $file){
				$this->responsesFileService->delete($file->id);
			}
			
			// удалить участие в сделке 
			$deleedResponses = $response->delete();

			return $this->successResponse();
       
		}
		catch(Throwable $e){
			return $this->errorResponse($e->getMessage());
		}
	
	}
}
