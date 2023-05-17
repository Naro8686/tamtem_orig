<?php

namespace App\Http\Controllers\Client\Api\v1;

use App\Models\Org\OrganizationDeal;
use App\Models\User;
use App\Models\Payment\Subscription;
use App\Models\Payment\UserSubscription;
use App\Models\FinanceOperation;

use App\Traits\ApiControllerTrait;
use Carbon\Carbon;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Throwable;
use Auth;
use Validator;

class PaymentServicesController extends Controller
{
	use ApiControllerTrait;

	/**
	 *  Получить все сервисы, доступные для пользователя
	 *
	 * @return json
	 */
	public function getServiceAvailableForUser()
	{
		try{

			$user = Auth::guard('api')->user();

			if ($user and $user->status === User::USER_STATUS_APPROVE) {
				return $this->successResponse(SUBSCRIPTION::with(['parent'])->where('status', SUBSCRIPTION::SUBSCRIPTION_STATUS_ACTIVE)->whereIn('slug', SUBSCRIPTION::getServiceFromUserPossible())->paginate(15));
			}
		
			return $this->errorResponse(['message' => "Отказано в доступе"]);
		
		}
		catch(Throwable $e){
			throw new \App\Exceptions\NotFoundException($e->getMessage() . " in " . __CLASS__ . "." . __FUNCTION__);
		}
	}

	/**
	 *  Получить информацию по сервисам, доступную для не авторизованныйх юзеров
	 *
	 * @return json
	 */
	public function getServiceAvailableForAll()
	{
		try{
			$activeServices = SUBSCRIPTION::select('id','name', 'slug', 'description', 'duration_days', 'cost')->where('status', SUBSCRIPTION::SUBSCRIPTION_STATUS_ACTIVE)->whereIn('slug', SUBSCRIPTION::getServiceFromUserPossible())->paginate(15);
			return $this->successResponse($activeServices);
		}
		catch(Throwable $e){
			throw new \App\Exceptions\NotFoundException($e->getMessage() . " in " . __CLASS__ . "." . __FUNCTION__);
		}
	}

	/**
	 *  Получить все тарифы, доступные для пользователя
	 *
	 * @return json
	 */
	public function getTariffAvailableForUser() 
	{
		try{

			$user = Auth::guard('api')->user();

			if ($user and $user->status === User::USER_STATUS_APPROVE) {
				return $this->successResponse($user->getTariffAvailableForUser());
				//return $this->successResponse(SUBSCRIPTION::with(['parent'])->where('status', SUBSCRIPTION::SUBSCRIPTION_STATUS_ACTIVE)->whereIn('slug', SUBSCRIPTION::getTariffFromUserPossible())->paginate(15));
			}
		
			return $this->errorResponse(['message' => "Отказано в доступе"]);
		
		}
		catch(Throwable $e){
			throw new \App\Exceptions\NotFoundException($e->getMessage() . " in " . __CLASS__ . "." . __FUNCTION__);
		}
	}

	/**
	 *  Получить информацию по тарифам, доступную для не авторизованныйх юзеров
	 *
	 * @return json
	 */
	public function getTariffAvailableForAll() 
	{
		try{
			$activeTariffs = SUBSCRIPTION::select('id','name', 'slug', 'description', 'duration_days', 'cost')->where('status', SUBSCRIPTION::SUBSCRIPTION_STATUS_ACTIVE)->whereIn('slug', SUBSCRIPTION::getTariffFromUserPossible())->paginate(15);
			$activeTariffs->transform(function ($item, $key) {
				$item['is_promo'] = (in_array($item->slug, Subscription::getServiceFromUserPossibleActivation())) ? true : false;
				return $item;
			});
			return $this->successResponse($activeTariffs);
		}
		catch(Throwable $e){
			throw new \App\Exceptions\NotFoundException($e->getMessage() . " in " . __CLASS__ . "." . __FUNCTION__);
		}
	}

	// mvp tamtem
	// Доступен ли платный сервис  ===============================================================================================
	public function paymentServiceIsPossible(Request $request, $typeService, $isLocalResponse = false, $user = null, $costOfService = null)
	{

		$user = ($user !== null) ? $user : Auth::guard('api')->user(); 

		try{

			$arIsPossible = [
				'is_pro_account'	=> $user->isProAccount(), // если про-аккаунт, то доступны все платные услуги и опции
				'ballance' 			=> $user->ballance, // балланс юзера
				'is_possible'		=> false , // возможность оплаты или дальшейшего шага
				'message' 			=> '',
				'duration_days' 	=> 0,
			];
			
		//	$costOfService = Subscription::getСostOfServiceFromSlug($typeService); // стоимость сервиса по имени его слага

			$paymenService = Subscription::getServiceFromSlug($typeService); // получить сервис по слагу

			// нет сервиса с таким слагом
			if($paymenService === null){
				$arIsPossible['message'] = $typeService . " сервис отсутствует!";
				return ($isLocalResponse) ? $arIsPossible : $this->successResponse($arIsPossible); 
			}

			$arIsPossible['service_name'] = $paymenService->name;

			if(Subscription::isActiveServiceFromSlug($typeService) === false){
				$arIsPossible['message'] = $paymenService->name . "  - этот сервис в состоянии не активен!";
				return ($isLocalResponse) ? $arIsPossible : $this->successResponse($arIsPossible); 
			}

			$paymenService = $paymenService->toArray();
	//		dd(	$paymenService);

		//	$subscriptionId = Subscription::getIdOfServiceFromSlug($typeService);
			if($isLocalResponse) {
				$arIsPossible['subscription_id'] = $paymenService['id'];
			}
			$arIsPossible['cost_of_service'] = ($costOfService !== null ) ? $costOfService : $paymenService['cost'];
			$arIsPossible['started_at'] = $paymenService['started_at'];
			$arIsPossible['duration_days'] = $paymenService['duration_days'];
			$arIsPossible['type'] = $paymenService['type'];

	//		$arIsPossible['left_days_to_end'] = $paymenService['started_at']; // осталось дней до конца подписки

			// если Про - аккаунт
			if($arIsPossible['is_pro_account'] ){
				$arIsPossible['is_possible'] = true;
	//	$arIsPossible['message'] = "Для вас возможно все! У вас подписка на сервис: " . Subscription::getNameOfServiceFromSlug(Subscription::SUBSCRIPTION_PAYMENT_ALL_IN);
				$arIsPossible['message'] = "Для вас возможно все! У вас подписка на сервис: " .  $paymenService['name'];
				return ($isLocalResponse) ? $arIsPossible : $this->successResponse($arIsPossible);
			}

			// проверка, хватает ли денег на оплату
			if($arIsPossible['cost_of_service'] > $arIsPossible['ballance']){
				$arIsPossible['message'] = "У вас на счету недостаточно средств для оплаты";
				//$arIsPossible['message'] = "У вас на счету недостаточно средств для оплаты сервиса: " .  $paymenService['name'];
				return ($isLocalResponse) ? $arIsPossible : $this->successResponse($arIsPossible);
			}
			else{
				$arIsPossible['is_possible'] = true;
				$arIsPossible['message'] = "У вас достаточно средств для оплаты: " .   $paymenService['name'];
				return ($isLocalResponse) ? $arIsPossible : $this->successResponse($arIsPossible);
			}

		}
		catch(Throwable $e){
			throw new \App\Exceptions\NotFoundException($e->getMessage() . " in " . __CLASS__ . "." . __FUNCTION__);
		}
	}

	// mvp tamtem
	// активировать  платный сервис  ===============================================================================================
	public function activateSubscription(Request $request, $typeService, $isLocalResponse = false)
	{
		$user = Auth::guard('api')->user(); 

		try{

				$paymentService = Subscription::getServiceFromSlug($typeService); // получить сервис по слагу

				// нет сервиса с таким слагом
				if($paymentService === null){
						return  $this->errorResponse($typeService . " сервис отсутствует!"); 
				}

				if(!in_array($paymentService->slug, Subscription::getServiceFromUserPossibleActivation())){
						return  $this->errorResponse($typeService . " сервис не разрешен на активацию пользователем!");
				}

				$paymentUserService = UserSubscription::where('subscription_id', $paymentService->id)->where('user_id', $user->id)->first(); // получить сервис по слагу
				
				// нет такой подписки у юзера с таким слагом
				if($paymentUserService === null){
						return  $this->errorResponse("Сожалеем, у вас нет возможности активировать " . $paymentService->name . ". У вас нет такой подписки. Обратитесь к администратору.");
				}

				if($paymentUserService->status === Subscription::SUBSCRIPTION_STATUS_ACTIVE or $paymentUserService->status === Subscription::SUBSCRIPTION_STATUS_FINISHED ){
						return  $this->errorResponse($paymentService->name . " сервис уже активирован или окончил свое действие!"); 
				}

				$paymentUserService->started_at = Carbon::now();
				$paymentUserService->status     = Subscription::SUBSCRIPTION_STATUS_ACTIVE;
				$paymentUserService->save();

				return $this->successResponse(['id' => $paymentUserService->id]);

		}
		catch(Throwable $e){
				throw new \App\Exceptions\ NotFoundException($e->getMessage() . " in " . __CLASS__ . "." . __FUNCTION__);
		}
	}

	// mvp tamtem
	// купить  платный сервис  ===============================================================================================
	public function paymentSubscription(Request $request, $typeService, $isLocalResponse = false, $dealIdLocalResponse = null, $user = null, $costOfService = null)
	{
		$user = ($user !== null) ? $user : Auth::guard('api')->user(); 

		try{

			$returnMessageObj = null;

			$paymentService = Subscription::getServiceFromSlug($typeService); // получить сервис по слагу

			// нет сервиса с таким слагом
			if($paymentService === null){
				$returnMessageObj = ($isLocalResponse) ? false : $this->errorResponse($typeService . " сервис отсутствует!");
				return  $returnMessageObj; 
			}

			$isTariff = (in_array($paymentService->slug, Subscription::getTariffFromUserPossible())) ? true : false; // это тариф

			$dealId = null; // это тариф, если  null

			if(in_array($paymentService->slug, Subscription::getServiceFromUserPossibleActivation()) /*or !$isTariff*/){
				$returnMessageObj = ($isLocalResponse) ? false : $this->errorResponse($typeService . " не разрешено к приобретению!");
				return  $returnMessageObj; 
			}

			// для тарифов
			if($isTariff){
				$paymentUserService = UserSubscription::where('subscription_id', $paymentService->id)->where('user_id', $user->id)->where('status', Subscription::SUBSCRIPTION_STATUS_ACTIVE)->first(); // получить сервис по слагу

				if($paymentUserService !== null ){
					$returnMessageObj = ($isLocalResponse) ? false : $this->errorResponse("У вас уже подключен тариф " . $paymentService->name );
					return  $returnMessageObj; 
				}
			}
			else{

				if($isLocalResponse){
					$dealId = $dealIdLocalResponse;
				}
				else{
					$validator = Validator::make($request->all(), [
						'deal_id' => 'required|integer',
					]);
	
					if ($validator->fails()) {
						return $this->errorResponse($validator->messages());
					}
					$dealId = (int) $request->get('deal_id');
				}

				if(!$deal = OrganizationDeal::find($dealId) or $deal->type_deal !== OrganizationDeal::DEAL_TYPE_BUY){
					$returnMessageObj = ($isLocalResponse) ? false : $this->errorResponse("Нельзя купить подписку на объявление с id = ". $dealId );
					return  $returnMessageObj; 
				}

				// покупка контактов
				if($paymentService->slug === Subscription::SUBSCRIPTION_PAYMENT_ONCE_DEAL_BUY_GET_CONTACTS){
					$paymentUserService = UserSubscription::where('subscription_id', $paymentService->id)->where('user_id', $user->id)->where('deal_id', $dealId)->where('status', Subscription::SUBSCRIPTION_STATUS_ACTIVE)->first(); // получить сервис по слагу

					if($deal->winner_id !== $user->organization_id ){
						$returnMessageObj = ($isLocalResponse) ? false : $this->errorResponse("Вы не победитель с делке номер " . $dealId );
						return  $returnMessageObj; 
					}

					if($paymentUserService !== null ){
						$returnMessageObj = ($isLocalResponse) ? false : $this->errorResponse("У вас уже ранее куплены контакты по объявлению номер " . $dealId );
						return  $returnMessageObj; 
					}

					$idsDealsBuyContacts = $user->idsDealsBuyContacts(); // id объявлений с проплаченными контактами
					$commission = count($idsDealsBuyContacts) > 0 ? $deal->commission : 200;

					$costOfService = $commission;
				}
							
			}

			$paymentInfo = $this->paymentServiceIsPossible($request, $typeService , true, $user, $costOfService);
  // dd($paymentInfo);
			if($paymentInfo['is_possible'] === false){
				$returnMessageObj = ($isLocalResponse) ? false : $this->errorResponse($paymentInfo['message']);
				return  $returnMessageObj; 
			}

			// если это тариф
			if($isTariff){

				if($user->ballance < $paymentInfo['cost_of_service']){
					$returnMessageObj = ($isLocalResponse) ? false : $this->errorResponse('У вас на счету недостаточно средств для оплаты');
					return  $returnMessageObj; 
				}
				// если есть активный другой тариф, то завершаем текущий и покупаем новый
				$activeOtherTarif = $user->getTariffAvailableForUser()->where('is_active', true)->first();

				if($activeOtherTarif and isset($activeOtherTarif->ids_user_subscription) and count($activeOtherTarif->ids_user_subscription) > 0 ){
					$user->finishTariff($activeOtherTarif->ids_user_subscription[0]); // финишируем тариф
				}
			}

			$paymentInfo = $this->paymentServiceIsPossible($request, $typeService , true, $user, $costOfService);
			
			if($paymentInfo['is_possible'] === false){
				$returnMessageObj = ($isLocalResponse) ? false : $this->errorResponse($paymentInfo['message']);
				return  $returnMessageObj; 
			}
			
			$isProAccount = $paymentInfo['is_pro_account'];

			$id = $this->newUserSubscription($paymentService->id, $user->id, $dealId, $paymentService->name, $paymentService->duration_days, $paymentInfo['cost_of_service'], $isProAccount, Subscription::SUBSCRIPTION_STATUS_ACTIVE);

			if(!$isProAccount){
				if($user->ballance < $paymentInfo['cost_of_service']){
					$returnMessageObj = ($isLocalResponse) ? false : $this->errorResponse('Не достаточно средств для оплаты');
					return  $returnMessageObj; 
				}
				$user->ballance -=  $paymentInfo['cost_of_service'];// снимем сразу сумму за  услугу
				$user->save();

				// создадим запись в финансовых операциях
				$rowFinanceOperation = (new \App\Services\FinanceOperation\FinanceOperationService())->storeLocalPayment($user, null, -$paymentInfo['cost_of_service'], "Снятие средств за: " . $paymentService->name, $id, true);
				//dd($rowFinanceOperation);

				// оплатили и надо закрыть сделку ================================================
				if($deal->type_deal === OrganizationDeal::DEAL_TYPE_BUY){
					$deal->trading_status = OrganizationDeal::DEAL_TRADING_STATUS_FINISHED;
					//надо проставить всем участникам - что сделка по торгам завершена
					$deal->membersFromOrganizationMembersTable()->update(['trading_status' => OrganizationDeal::DEAL_TRADING_STATUS_FINISHED]);
					// надо закрыть сделку --------------------
					$deal->finish = true;
					$deal->finished_at = Carbon::now();
					$deal->status = OrganizationDeal::DEAL_STATUS_ARCHIVE; 
					$deal->trading_status = OrganizationDeal::DEAL_TRADING_STATUS_FINISHED; 
					$deal->save();
					$deal->finishForMembers();

					// Начислим балл в статистику, если она есть
					if($statistic = $user->organization()->first()->statistic()->first()){
						$statistic->increment('response_ballance', $paymentInfo['cost_of_service']);
					}

					// надо создать диалог по данной сделке между продавцом и покупателем и послеть первое сообщение
                    if(!$dialogId = DialogsController::newLocalDialog($deal->id, $deal->winner_id, $deal->organization_id)){
                        return $this->errorResponse('ошибка создания диалога');
                    }
                    if(!$messageId = DialogsController::sendLocal($dialogId, $deal->user, 'Мои контакты у Вас есть, можно обговорить детали')){
                        return $this->errorResponse('ошибка отправки сообщения продавцу');
					}

					if($rowFinanceOperation instanceof FinanceOperation){
						$rowFinanceOperation->user_subscription_id = $id;
						$rowFinanceOperation->deal_id = $deal->id;
						$rowFinanceOperation->slug = $typeService;
						$rowFinanceOperation->save();
					}
				}			
			}

			$returnMessageObj = ($isLocalResponse) ? ['id'=> $id] : $this->successResponse(['id' => $id]);
			return  $returnMessageObj; 

		}
		catch(Throwable $e){
				throw new \App\Exceptions\ NotFoundException($e->getMessage() . " in " . __CLASS__ . "." . __FUNCTION__);
		}
	}


	/**
	 *  Новая строка в таблицу подписок пользователей 
	 * newUserSubscription
	 *
	 * @param  mixed $subscription_id
	 * @param  mixed $user_id
	 * @param  mixed $deal_id
	 * @param  mixed $description
	 * @param  mixed $duration_days
	 * @param  mixed $cost
	 *
	 * @return void
	 */
	public function newUserSubscription($subscription_id, $user_id, $deal_id = null, $description = null, $duration_days = null, $cost = null, $isProAccount = false, $status = Subscription::SUBSCRIPTION_STATUS_PAUSE)
  {	
		try{

				$duration_days  = ($duration_days === null) ? Subscription::getDurationDaysFromId($subscription_id) : (int)$duration_days;
				$cost           = ($cost === null) ? Subscription::getСostOfServiceFromId($subscription_id) : $cost;
		
				if($isProAccount){
					$cost = 0;
				}

				$arToDB = [
					'subscription_id'   => $subscription_id,
					'user_id'           => $user_id,
					'deal_id'           => $deal_id,
					'description'       => $description,
					//  'started_at'        => Carbon::now(),  // - правильно наверное после модерации включать , а не сразу
					'duration_days'     => $duration_days,
					'status'            => $status,
					'cost'              => $cost,
				];
		
				if( $status === Subscription::SUBSCRIPTION_STATUS_ACTIVE){
					$arToDB['started_at'] = Carbon::now();
				}
				$ret = UserSubscription::create($arToDB );

				return $ret->id;
		}
		catch(Throwable $e){
				throw new \App\Exceptions\NotFoundException($e->getMessage() . " in " . __CLASS__ . "." . __FUNCTION__);
		}
  }

	
		// =============  Раздел управления тарифами ======================================================================
		
		
		/**
		 *  Получить все подписки
		 * 
		 * getSubscription
		 *
		 * @return json
		 */
		public function getSubscription()
		{
				try{

					$user = Auth::guard('api')->user();
//dd(SUBSCRIPTION::with(['parent'])->get()->toArray());
					if ($user and ($user->role === User::ROLE_MODERATOR or $user->role === User::ROLE_ADMINISTRATOR) and $user->status === User::USER_STATUS_APPROVE) {
							return $this->successResponse(SUBSCRIPTION::with(['parent'])->paginate(15));
					}
			
					return $this->errorResponse(['message' => "Отказано в доступе"]);
				
				}
				catch(Throwable $e){
					throw new \App\Exceptions\NotFoundException($e->getMessage() . " in " . __CLASS__ . "." . __FUNCTION__);
				}
		}

			/**
		 *  Получить подписку по ID
		 * 
		 * getSubscriptionFromId
		 *
		 * @return json
		 */
		public function getSubscriptionFromId($id)
		{
				try{

					$user = Auth::guard('api')->user();

					if ($user and $user->status === User::USER_STATUS_APPROVE) {
						return $this->successResponse(SUBSCRIPTION::with(['parent'])->where('id', $id)->get()->toArray());
					}
			
					return $this->errorResponse(['message' => "Отказано в доступе"]);
				
				}
				catch(Throwable $e){
					throw new \App\Exceptions\NotFoundException($e->getMessage() . " in " . __CLASS__ . "." . __FUNCTION__);
				}
		}


		/**
		 *  Получить все подписки по фильтру
		 * 
		 * getSubscriptionFromFilter
		 *
		 * @return json
		 */
		public function getSubscriptionFromFilter(Request $request)
		{
				try{

					$user = Auth::guard('api')->user();

					if ($user and $user->status === User::USER_STATUS_APPROVE) {
						
							$input = $request->all();

							$validator = Validator::make($input, [
									'name' => 'sometimes|min:5|max:190',
									'slug' => 'sometimes|min:5|max:190|exists:subscriptions,slug',
									'started_at'  => 'sometimes|date_format:Y-m-d|after_or_equal:' . Carbon::now(),
									'duration_days'  => 'sometimes|integer|min:1',
									'status'  => 'sometimes|in:' . Subscription::SUBSCRIPTION_STATUS_PAUSE . ','. Subscription::SUBSCRIPTION_STATUS_FINISHED . ','. Subscription::SUBSCRIPTION_STATUS_ACTIVE,
									'cost'  => 'sometimes|integer' , //regex:/^\d+(\.\d*)?$/',
									'parent_id'  => 'sometimes|exists:subscriptions,id',
							]);
			
							if ($validator->fails()) {
								return $this->errorResponse($validator->messages());
							}

							$arToRequest = [
								'name' 						=> $request->input('name', null),
								'slug' 						=> $request->input('slug', null),
								'started_at'			=> $request->input('started_at', null),
								'duration_days' 	=> $request->input('duration_days', null),
								'status' 					=> $request->input('status', null),
								'cost' 						=> $request->input('cost', null),
								'parent_id' 			=> $request->input('parent_id', null),
							];

							$query = SUBSCRIPTION::with(['parent']);

							foreach($arToRequest as $key =>$val){
									if ($val !== null ){
											$query->where($key, "=",  $val);
									}	
							}

							return $this->successResponse($query->paginate(15) );
					}

					return $this->errorResponse(['message' => "Отказано в доступе"]);
				
				}
				catch(Throwable $e){
					throw new \App\Exceptions\NotFoundException($e->getMessage() . " in " . __CLASS__ . "." . __FUNCTION__);
				}
		}

		
		
		/**
		 *  Создать тип подписки
		 * 
		 * storeSubscription
		 *
		 * @param  mixed $request
		 *
		 * @return void
		 */
		public function storeSubscription(Request $request)
		{
			try{

				$user = Auth::guard('api')->user();

				if ($user and ($user->role === User::ROLE_MODERATOR or $user->role === User::ROLE_ADMINISTRATOR) and $user->status === User::USER_STATUS_APPROVE) {

						$input = $request->all();

						$validator = Validator::make($input, [
								'name' => 'required|min:5|max:190',
								'slug' => 'required|min:5|max:190|unique:subscriptions',
								'description' => 'sometimes|min:5|max:999',
								'started_at'  => 'sometimes|date_format:Y-m-d|after_or_equal:' . Carbon::now(),
								'duration_days'  => 'sometimes|integer|min:1',
								'status'  => 'sometimes|in:' . Subscription::SUBSCRIPTION_STATUS_PAUSE . ','. Subscription::SUBSCRIPTION_STATUS_FINISHED . ','. Subscription::SUBSCRIPTION_STATUS_ACTIVE,
								'cost'  => 'sometimes|integer' , //regex:/^\d+(\.\d*)?$/',
								'type'	=> 'sometimes|in:' . Subscription::SUBSCRIPTION_TYPE_SERVICE . ','. Subscription::SUBSCRIPTION_TYPE_TARIFF, 
								'parent_id'  => 'sometimes|exists:subscriptions,id',
						]);
		
						if ($validator->fails()) {
							return $this->errorResponse($validator->messages());
						}

						$data = SUBSCRIPTION::create($input);
				
						return $this->successResponse($data);
				}
		
				return $this->errorResponse(['message' => "Отказано в доступе"]);
			
			}
			catch(Throwable $e){
					return $this->errorResponse($e->getMessage() . " in " . __CLASS__ . "." . __FUNCTION__);
			}
		}



		/**
		 * updateSubscription
		 *
		 * @param  mixed $request
		 *
		 * @return void
		 */
		public function updateSubscription(Request $request, $id){

			try{

				$user = Auth::guard('api')->user();

				if ($user and ($user->role === User::ROLE_MODERATOR or $user->role === User::ROLE_ADMINISTRATOR) and $user->status === User::USER_STATUS_APPROVE) {

						$input = $request->all();

						$validator = Validator::make($input, [
								'name' => 'sometimes|min:5|max:190',
								'slug' => 'sometimes|min:5|max:190|unique:subscriptions',
								'description' => 'sometimes|min:5|max:999',
								'started_at'  => 'sometimes|date_format:Y-m-d|after_or_equal:' . Carbon::now(),
								'duration_days'  => 'sometimes|integer|min:1',
								'status'  => 'sometimes|in:' . Subscription::SUBSCRIPTION_STATUS_PAUSE . ','. Subscription::SUBSCRIPTION_STATUS_FINISHED . ','. Subscription::SUBSCRIPTION_STATUS_ACTIVE,
								'cost'  => 'sometimes|integer' , //regex:/^\d+(\.\d*)?$/',
								'type'	=> 'sometimes|in:' . Subscription::SUBSCRIPTION_TYPE_SERVICE . ','. Subscription::SUBSCRIPTION_TYPE_TARIFF, 
								'parent_id'  => 'sometimes|exists:subscriptions,id',
						]);
						if ($validator->fails()) {
							return $this->errorResponse($validator->messages());
						}

						$data = SUBSCRIPTION::where('id', $id)->update($input);
				
						return $this->successResponse($data);
				}
								return $this->errorResponse(['message' => "Отказано в доступе"]);
			}
			catch(Throwable $e){
				throw new \App\Exceptions\NotFoundException($e->getMessage() . " in " . __CLASS__ . "." . __FUNCTION__);
			}
		}



		public function deleteSubscription($id){

			try{

					$user = Auth::guard('api')->user();

					if ($user and ($user->role === User::ROLE_MODERATOR or $user->role === User::ROLE_ADMINISTRATOR) and $user->status === User::USER_STATUS_APPROVE) {
							SUBSCRIPTION::destroy($id);
							return $this->successResponse();
					}
			
					return $this->errorResponse(['message' => "Отказано в доступе"]);
			}
			catch(Throwable $e){
				throw new \App\Exceptions\NotFoundException($e->getMessage() . " in " . __CLASS__ . "." . __FUNCTION__);
			}

		}
}
