<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinanceOperation extends Model
{
    use SoftDeletes;

    const PAYMENT_SYSTEM_SCORE     = 0; // оплата по счету
    const PAYMENT_SYSTEM_PAYTURE   = 1; // оплата через платежную систему PAYTURE картой
    const PAYMENT_SYSTEM_SITE      = 2; // зачисление через админку или как-либо иначе, через текущий сайт 

    const PAYMENT_STATUS_ERROR      = -1; // ошибка оплаты
    const PAYMENT_STATUS_WAITING    = 0; // ожидание оплаты
    const PAYMENT_STATUS_PAID       = 1; // оплачено

    
    protected $table = 'finance_operations';

    protected $casts = [
        'user_id' => 'integer',
        'status'  => 'integer',
        'payment_system'  => 'integer',
        'manager_id'  => 'integer',
        'deal_id'  => 'integer',
        'user_subscription_id'  => 'integer',

    ];

    protected $fillable = [
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
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manager()
    {
        return $this->belongsTo(\App\Models\User::class, 'manager_id');
    }
}
