<?php

namespace App\Services\FinanceOperation;

use App\Models\FinanceOperation;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;
use Auth;

class FinanceOperationService
{
  
    /**
     * Проверка, сучествует ли колонка со значением
    * @param Request $request
    * @return Collection
    */
    public function isRowValueExists($row, $val) {
        return FinanceOperation::where($row, $val)->exists();
    }

    /**
     * @param int $id
     * @return FinanceOperation
     * @throws Exception
     */
    public function getRowValue($row, $val)
    {
        /** @var FinanceOperation $financeOperation */
        $financeOperation = FinanceOperation::where($row, '=', $val)->first();
        return $financeOperation;
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function index(Request $request)
    {
        $query = FinanceOperation::query();

        $user = Auth::guard('api')->user();

        if($user->IsAdmin()){
            $query->when(true === $request->has('text') &&  3 <= mb_strlen($request->input('text')), function (Builder $query) use ($request) {
                $query->where('text', 'LIKE', "%{$request->input('text')}%");
            });
    
            $query->when(true === $request->has('payment_id') &&  3 <= mb_strlen($request->input('payment_id')), function (Builder $query) use ($request) {
                $query->where('payment_id', 'LIKE', "%{$request->input('payment_id')}%");
            });
    
            $query->when(true === $request->has('user_id'), function (Builder $query) use ($request) {
                $query->where('user_id', '=', $request->input('user_id'));
            });
    
            $query->when(true === $request->has('payment_system'), function (Builder $query) use ($request) {
                $query->where('payment_system', '=', $request->input('payment_system'));
            });
    
            $query->when(true === $request->has('status'), function (Builder $query) use ($request) {
                $query->where('status', '=', $request->input('status'));
            });
    
            $query->when(true === $request->has('manager_id'), function (Builder $query) use ($request) {
                $query->where('manager_id', '=', $request->input('manager_id'));
            });

            $query->when(true === $request->has('date_from') and true === $request->has('date_to'), function (Builder $query) use ($request) {
                $dateFrom = Carbon::createFromFormat('Y-m-d', $request->input('date_from'))->setTime(0,0)->toDateTimeString();
                $dateTo   = Carbon::createFromFormat('Y-m-d', $request->input('date_to'))->setTime(23,59)->toDateTimeString();
                $query->whereBetween('created_at', [$dateFrom, $dateTo]);
            });
        }
        else{
            $query->where('user_id', '=', $user->id);
            $query->when(true === $request->has('payment_system'), function (Builder $query) use ($request) {
                $query->where('payment_system', '=', $request->input('payment_system'));
            });

            $query->when(true === $request->has('status'), function (Builder $query) use ($request) {
                $query->where('status', '=', $request->input('status'));
            });

            $query->when(true === $request->has('date_from') and true === $request->has('date_to'), function (Builder $query) use ($request) {
                $dateFrom = Carbon::createFromFormat('Y-m-d', $request->input('date_from'))->setTime(0,0)->toDateTimeString();
                $dateTo   = Carbon::createFromFormat('Y-m-d', $request->input('date_to'))->setTime(23,59,59)->toDateTimeString();
                $query->whereBetween('created_at', [$dateFrom, $dateTo]);
            });
        }

        $query->orderBy('created_at', 'desc');

        return $query->select([ 'id',  
                                'user_id', 
                                'payment_id', 
                                'amount', 
                                'payment_system', 
                                'status', 
                                'text', 
                                'manager_id', 
                                'deal_id',
                                'slug',
                                'user_subscription_id',
                                'created_at', 
                                'updated_at'])->paginate(15);
    }

    /**
     * @param int $id
     * @return FinanceOperation
     * @throws Exception
     */
    public function get(int $id)
    {
        $user = Auth::guard('api')->user();
        
        /** @var FinanceOperation $financeOperation */
        $financeOperation = FinanceOperation::where('id', '=', $id);

        if(!$user->IsAdmin()){ // если обычный юзер
            $financeOperation->where('user_id', '=', $user->id);
        }

        $financeOperation = $financeOperation->first();

        if (null === $financeOperation) {
            $financeOperation = "Операция {$id} не найдена или не доступна для вас";
        }

        return $financeOperation;
    }

    /**
     * @param array $data
     * @return FinanceOperation
     */
    public function create(User $user, array $data)
    {

        $userId = $manager_id = $isOk = null;
        // если не передали user_id - это сам юзер платит
        if(!isset($data['user_id']) and isset($data['manager_id'])){
            $financeOperation = "Менеджер #{$data['manager_id']} , вы не указали пользователя" ;
        }
        elseif(!isset($data['user_id'])){
            $userId = $user->id;
            $manager_id = null;
            $isOk = true;
        }
        elseif(isset($data['user_id']) and (isset($data['manager_id']) or $data['manager_id'] === null)){
            $userId = $data['user_id'];
            $manager_id = $data['manager_id'];
            $isOk = true;
        }
        else{
            $financeOperation = "Не указан менеджер";
        }

        if($isOk){

            $financeOperation = new FinanceOperation();
                
            $financeOperation->user_id           = $userId;
            $financeOperation->payment_id        = $data['payment_id'];
            $financeOperation->amount            = $data['amount'];
            $financeOperation->payment_system    = $data['payment_system'];
            $financeOperation->status            = $data['status'];
            $financeOperation->text              = $data['text'];
            $financeOperation->manager_id        = $manager_id;
            $financeOperation->deal_id           = isset($data['deal_id']) ? $data['deal_id'] : null;
            $financeOperation->slug              = isset($data['slug']) ? $data['slug'] : '';
            $financeOperation->user_subscription_id  = isset($data['user_subscription_id']) ? $data['user_subscription_id'] : null;

            $financeOperation->save();
        }

        return $financeOperation;
    }

    /**
     * @param int $id
     * @param array $data
     * @return FinanceOperation
     * @throws Exception
     */
    public function update(int $id, array $data)
    {
        $financeOperation = $this->get($id);

        if($financeOperation instanceof FinanceOperation){

            if( $financeOperation->status === FinanceOperation::PAYMENT_STATUS_PAID){
                $financeOperation = "Статуc текущей операции уже: Оплачено";
            }

            $financeOperation->status  = $data['status'];
            if(isset($data['text'])) $financeOperation->text = $data['text'];
            if(isset($data['manager_id'])) $financeOperation->manager_id = $data['manager_id'];
            if(isset($data['deal_id'])) $financeOperation->deal_id = $data['deal_id'];
            if(isset($data['slug'])) $financeOperation->slug = $data['slug'];
            if(isset($data['user_subscription_id'])) $financeOperation->user_subscription_id = $data['user_subscription_id'];

            $financeOperation->save();
        }

        return $financeOperation;
    }

    /**
     * @param int $id
     * @throws Exception
     */
    public function delete(int $id)
    {
        $financeOperation = $this->get($id);

        if($financeOperation instanceof FinanceOperation){
            $financeOperation->delete();
        }
        return $financeOperation;
    }

    /**
     * storeLocalPayment - запись в финансовые операции на беке
     *
     * @param  User $toUser - кому приход/расход
     * @param  integer $managerUser - кто провел операцию
     * @param  integer $amount - кол-во денег
     * @param  string $text - описание операции
     *
     * @return boolean or string or object
     */
    public function storeLocalPayment(User $toUser, $managerUser = null, $amount = 0, $text = '', $user_subscription_id = null, $isReturnObjectFinOp = false)
    { 

        // создадим запись в финансовых операциях
        $data = [
            'user_id'           =>  $toUser->id,
            'payment_id' 		=>  $this->generateUniqueOrderIdValue($toUser->unique_id),
            'amount'            => (int)$amount,
            'payment_system'    => FinanceOperation::PAYMENT_SYSTEM_SITE,
            'status'    		=> FinanceOperation::PAYMENT_STATUS_PAID,
            'text'    			=> $text,
            'manager_id'    	=> ($managerUser === null) ? null : $managerUser->id,
            'user_subscription_id'    	=> $user_subscription_id,
        ];
        // dd($data);
        $financeOperation = $this->create($toUser, $data);

        if(!($financeOperation instanceof FinanceOperation)){
            return "Не возможно создать запить в FinanceOperation   func(" .  __FUNCTION__ . ")";
        }

        if($isReturnObjectFinOp === true){
            return $financeOperation;
        }

        return true;
    }
   
    /**
    * @param string $prefix
    * @return string
    */
    public function generateUniqueOrderIdValue($prefix = null) {
        
        $orderId = md5(uniqid(rand(), true));
        $orderId = ($prefix !== null) ? $prefix . "_" . $orderId : $orderId;

        if ($this->isRowValueExists('payment_id', $orderId)) {
            return $this->generateUniqueOrderIdValue($prefix);
        }
 
        return $orderId;
    }
    
}
