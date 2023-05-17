<?php

namespace App\Models;

use App\Models\Org\Organization;
use App\Models\Payment\Subscription;
use App\Models\FinanceOperation;
use App\Models\Org\OrganizationDeal;
use App\Models\Org\OrganizationDealMember;
use App\Traits\DataTable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use DataTable;
    use SoftDeletes;
    // ToDo: make seeds with permissions
    const ROLE_ADMINISTRATOR = 'administrator';
    const ROLE_MODERATOR = 'moderator';
    const ROLE_CLIENT = 'client';
    const ROLE_CLIENT_WORKER = 'client_worker';
    const USER_STATUS_REGISTRED = 'register';
    const USER_STATUS_APPROVE = 'approve';
    const USER_STATUS_ARCHIVE = 'archive';
    const USER_STATUS_BANNED = 'banned';


    const ACCOUNT_CREATED_USER = 'user';
    const ACCOUNT_CREATED_AUTO = 'auto';
    const ACCOUNT_CREATED_FORM = 'form';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'status', 'is_org_created', 'unique_id', 'phone', 'photo', 'hide_email', 'account_is_created'
    ];
    static protected $sortable = ['id', 'name', 'email', 'role', 'status', 'organization_id', 'unique_id'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'register_confirm_token', 'api_token', 'reset_password_token', 'created_at', 'deleted_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization()
    {
        return $this->belongsTo(\App\Models\Org\Organization::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->hasOne(\App\Models\Org\Organization::class, 'owner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deals()
    {
        return $this->hasMany(\App\Models\Org\OrganizationDeal::class, 'user_id');
    }

    /**
     *  Get the tags associated with the given organization
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userViewDeal()
    {
        return $this->belongsToMany(\App\Models\Org\OrganizationDeal::class)->withTimestamps();
    } 

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function news()
    {
        return $this->hasMany(\App\Models\News::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userfeed()
    {
        return $this->hasMany(\App\Models\Feedback::class, 'owner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function moderfeed()
    {
        return $this->hasMany(\App\Models\Feedback::class, 'moder_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function financeOperation()
    {
        return $this->hasMany(\App\Models\FinanceOperation::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logSearch()
    {
        return $this->hasMany(\App\Models\LogSearch::class, 'user_id');
    }

    /**
     * @param $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeClient($query)
    {
        return $query->where('role', self::ROLE_CLIENT);
    }

    /**
     * @param $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdministrator($query)
    {
        return $query->where('role', self::ROLE_ADMINISTRATOR);
    }

     /**
     *
     * @return boolean
     */
    public function scopeIsAdmin()
    {
        if($this->role === self::ROLE_ADMINISTRATOR){
            return true;
        }
        return false;
    }

    /**
     *
     * @return boolean
     */
    public function scopeIsOrgCreated()
    {
        if($this->is_org_created === 1){
            return true;
        }
        return false;
    }

    /**
     * Подписан юзер на рассылку почты о новых сделках или нет
     * @return boolean
     */
    public function scopeIsNoticeSubscribed()
    {
        if($this->company->channels()->count() > 0){
            return true;
        }
        return false;
    }

     /**
     *
     * @return boolean
     */
    public function scopeIsAcceptWorkWithAdminka()
    {
        if($this->role === self::ROLE_ADMINISTRATOR or $this->role === self::ROLE_MODERATOR){
            return true;
        }
        return false;
    }

    /**
     * @param $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeModerator($query)
    {
        return $query->where('role', self::ROLE_MODERATOR);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeWorkers($query)
    {
        return $query->where('role', self::ROLE_CLIENT_WORKER)->orWhere('role', self::ROLE_CLIENT);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeAdministrators($query)
    {
        return $query->where('role', self::ROLE_MODERATOR)->orWhere('role', self::ROLE_ADMINISTRATOR);
    }

    /**
     * Create api access token
     */
    public function rollApiKey()
    {
        do {
            $this->api_token = str_random(60);
        } while ($this->where('api_token', $this->api_token)->exists());
        $this->save();
    }

    /**
     * Create confirm access token
     */
    public function rollConfirmKey()
    {
        do {
            $this->register_confirm_token = str_random(60);
        } while ($this->where('register_confirm_token', $this->register_confirm_token)->exists());
        $this->save();
    }

    /**
     * Get user permissions names
     * @return array
     */
    public function getPermissionsNames()
    {
        $permissions = $this->permissions;
        $names = [];
        foreach ($permissions as $permission) {
            $names[] = $permission->name;
        }
        unset($this->permissions);
        return $names;
    }

    /**
     * Get full user permission list
     * @return array
     */
    public function getPermissionsStruct()
    {
        $allPermissions = Permission::get();
        $permissions = $this->permissions;
        $struct = [];
        $default = false;
        if ($this->organization && $this->organization->org_type == Organization::ORG_TYPE_BUYER) {
            $default = true;
        }
        foreach ($allPermissions as $permission) {
            $element = explode('.', $permission->name);
            $struct[$element[0]][@$element[1]] = $default;
        }
        foreach ($permissions as $permission) {
            $element = explode('.', $permission->name);
            $struct[$element[0]][@$element[1]] = true;
        }
        unset($this->permissions);
        return $struct;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favourites()
    {
        return $this->belongsToMany(\App\Models\Org\OrganizationDeal::class, 'user_selected_deals', 'user_id', 'deal_id');
    }

    public function banned()
    {
        if ($this->status === self::USER_STATUS_BANNED) {
            return true;
        }
        return false;
    }

    /**
     *  Есть ли у юзера телефон
     */
    public function phoneExist()
    {
        if ($this->phone === null or $this->phone === '') {
            return false;
        }
        return true;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function subscriptions()
    {
        return $this->hasMany(\App\Models\Payment\UserSubscription::class, 'user_id');
    }

    // /**
    //  * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
    //  */
    // public function activeSubscriptions()
    // {
    //   //  return $this->subscriptions()->where('status', '=', Subscription::SUBSCRIPTION_STATUS_ACTIVE);
    //     return $this->subscriptions()->where('status', '=', Subscription::SUBSCRIPTION_STATUS_ACTIVE);
    // }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function activeSubscriptionsExtended()
    {

        $activeSubscriptions = $this->subscriptions()->where('status', '=', Subscription::SUBSCRIPTION_STATUS_ACTIVE)->with(['subscription'])->get();

        foreach ($activeSubscriptions as $key => $activeSubscription) {

            if ($activeSubscription->subscription->status !== Subscription::SUBSCRIPTION_STATUS_ACTIVE or
                ($activeSubscription->subscription->parent !== null and $activeSubscription->subscription->parent->status !== Subscription::SUBSCRIPTION_STATUS_ACTIVE)) {

                $activeSubscriptions->forget($key);

            }
        }

        return $activeSubscriptions;
        // return $this->activeSubscriptions()->with(['subscription']);
    }

    /**
     *  Подпика - подарок (вертаем только Pro)
     *
     * activeSubscriptionsExtendedToLK
     *
     * @return void
     */
    public function subscriptionExtendedToLKFromSlug($slug = Subscription::SUBSCRIPTION_PAYMENT_ALL_IN_3_DAYS, $dealId = null)
    {

        $activeSubscriptions = $this->subscriptions()->select('id', 'subscription_id', 'deal_id', 'description', 'started_at', 'duration_days', 'status')->with(['subscription'])->where('status', SUBSCRIPTION::SUBSCRIPTION_STATUS_ACTIVE);
        $retSubscription = [];
        if (mb_strtolower($slug) === 'all') {

            foreach ($activeSubscriptions->get() as $activeSubscription) {
                if (in_array($activeSubscription->subscription->slug, SUBSCRIPTION::getTariffFromUserPossible())) {
                    $retSubscription[] = $activeSubscription->toArray();
                }
            }
            //  return  $activeSubscriptions->whereIn('slug', SUBSCRIPTION::getTariffFromUserPossible())->get();
        } else {

            // если надо проверить , купил ли подписку юзер именно по какой-то сделке
            if($dealId !== null){ 
                return $activeSubscriptions->where('deal_id', $dealId)->get()->toArray();
            }

            $activeSubscriptions = $activeSubscriptions->get(); //dd($activeSubscriptions->toArray());

            foreach ($activeSubscriptions as $key => $activeSubscription) {

                if ($activeSubscription->subscription->slug === $slug) {

                    $retSubscription[] = $activeSubscription->toArray();
                }
            }
        }

        return $retSubscription;
    }

    /**
     *  Получить все тарифы, доступные для пользователя
     *
     * getSubscription
     *
     * @return json
     */
    public function getTariffAvailableForUser()
    {

        $activeTariffs = SUBSCRIPTION::with(['parent'])->where('status', SUBSCRIPTION::SUBSCRIPTION_STATUS_ACTIVE)->whereIn('slug', SUBSCRIPTION::getTariffFromUserPossible())->paginate(15);
        $idsUserActiveSubscription = $this->subscriptions()->select('subscription_id')->where('status', '=', Subscription::SUBSCRIPTION_STATUS_ACTIVE)->distinct()->pluck('subscription_id')->toArray();
        $userWhereActivationSubscriptionFinished = $this->subscriptions()->select('subscription_id')->whereIn('status', [Subscription::SUBSCRIPTION_STATUS_ACTIVE, Subscription::SUBSCRIPTION_STATUS_FINISHED])->with(['subscription' => function ($query) {
            $query->whereIn('slug', Subscription::getServiceFromUserPossibleActivation());
        }])->get()->toArray();
        $idsUserWhereActivationSubscriptionFinished = [];
        foreach ($userWhereActivationSubscriptionFinished as $userWhereActivationSubscriptionFinishedCur) {
            if ($userWhereActivationSubscriptionFinishedCur['subscription'] !== null) {
                $idsUserWhereActivationSubscriptionFinished [$userWhereActivationSubscriptionFinishedCur['subscription']['id']] = $userWhereActivationSubscriptionFinishedCur['subscription']['id'];
            }
        }

        $activeTariffs->transform(function ($item, $key) use ($idsUserActiveSubscription, $idsUserWhereActivationSubscriptionFinished) {

            $item['is_active'] = (in_array($item->id, $idsUserActiveSubscription)) ? true : false;

            $item['is_possible_activate'] = (in_array($item->slug, Subscription::getServiceFromUserPossibleActivation()) and
                !in_array($item->id, $idsUserWhereActivationSubscriptionFinished)) ? true : false;

            $item['is_promo'] = (in_array($item->slug, Subscription::getServiceFromUserPossibleActivation())) ? true : false;

            $item['ids_user_subscription'] = $this->subscriptions()->select('id')->where('subscription_id', '=', $item->id)->where('status', '=', Subscription::SUBSCRIPTION_STATUS_ACTIVE)->distinct()->pluck('id')->toArray();

            return $item;
        });
        $activeTariffs->sortByDesc('is_active');
        return $activeTariffs;
    }

    /**
     *  Финиширует купленный тариф
     * 
     * @param $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function finishTariff($id)
    {
        $tariff =  $this->subscriptions()->where('id', '=', $id)
            ->where('status' , '<>', Subscription::SUBSCRIPTION_STATUS_FINISHED)
            ->orderBy('started_at', 'DESC')
            ->update(['status' => Subscription::SUBSCRIPTION_STATUS_FINISHED, 'finished_at' => Carbon::now()]);

        return  $tariff;
    }

    /**
     *  Если Про аккаунт - куплены все услуги
     *
     * @return boolean
     */
    public function isProAccount()
    {//dd($this->activeSubscriptionsExtended()->toArray());

        $activeSubscriptions = $this->activeSubscriptionsExtended();
//dd($this->activeSubscriptionsExtended()->get()->toArray());
//dd($activeSubscriptions ->toArray());
        foreach ($activeSubscriptions as $key => $activeSubscription) {
            if (
                (
                    $activeSubscription->subscription->slug === Subscription::SUBSCRIPTION_PAYMENT_ALL_IN and
                    $activeSubscription->subscription->status === Subscription::SUBSCRIPTION_STATUS_ACTIVE
                )
                or
                (
                    $activeSubscription->subscription->parent !== null and
                    $activeSubscription->subscription->parent->slug === Subscription::SUBSCRIPTION_PAYMENT_ALL_IN and
                    $activeSubscription->subscription->parent->status === Subscription::SUBSCRIPTION_STATUS_ACTIVE
                )) {
                return true;
            }
        }

        return false;
    }

    // список id сделок с проплаченными контактами, получаем его по активным подпискам юзера
    public function idsDealsBuyContacts()
    {
        // $activeSubscriptions = $this->activeSubscriptionsExtended()->get();
        $activeSubscriptions = $this->activeSubscriptionsExtended();
        $idsDealsBuyContacts = [];
        foreach ($activeSubscriptions as $key => $activeSubscription) {
            if ($activeSubscription->subscription->slug === Subscription::SUBSCRIPTION_PAYMENT_ONCE_DEAL_BUY_GET_CONTACTS) {
                $idsDealsBuyContacts[$activeSubscription->deal_id] = $activeSubscription->deal_id;
            }
        }

        return $idsDealsBuyContacts;
    }

    // количество сделок с проплаченными контактами, получаем его по активным подпискам юзера
    public function countDealsBuyContacts()
    {
        return count($this->idsDealsBuyContacts());
    }

    
    /**
     * Могу ли я показать контакты юзера с указанным $id текущему юзеру
     *
     * @param  mixed $id
     *
     * @return void
     */
    public function isMustViewContactsUserFromId($id)
    {

        $retVal = false;

        // хочу посмтореть свои контакты
        if($this->id === (int)$id){
            return true;
        }

        if($user = User::find($id)){ 

            // если я купил контакты другого юзера
            $idsDealsBuyContacts = $this->idsDealsBuyContacts();
            if($user->deals()->whereIn('id', $idsDealsBuyContacts)->first()){
                $retVal = true;
            }

            // если юзер, которого хочу посмотреть купил мои контакты
            $idsDealsBuyContacts = $user->idsDealsBuyContacts();
            if($this->deals()->whereIn('id', $idsDealsBuyContacts)->first()){
                $retVal = true;
            }
        }

        return  $retVal;
    }

    public function getLastEmailAutoAccount()
    {
        return 'auto_' . microtime(true) . '@tamtem.ru';
        // $user = $this->where('account_is_created', User::ACCOUNT_CREATED_AUTO)->orderBy('id', 'desc')->count();

        // if ($user==0) {
        //     $email = 'auto_1@tamtem.ru';
        // } else {

        //     $count = $user + 1;
        //     $email = 'auto_' . $count . '@tamtem.ru';
        // }

        // return $email;
    }

    /**
     * getDealsWaitingPayment - список не оплаченных сделок
     *
     * @return void
     */
    public function getDealsWaitingPayment()
    {
        $organizationId = $this->organization_id; 
        //dd($organizationId);
        $dealsNotPayedIds = \App\Models\Org\OrganizationDealMember::where('organization_id', '=', $organizationId)->where('trading_status', '=', OrganizationDeal::DEAL_TRADING_STATUS_WAITING_PAYMENT)->pluck('deal_id')->toArray();
        //dd($dealsNotPayedIds);
        $dealsWaytingPayment = \App\Models\Org\OrganizationDeal::whereIn('id', $dealsNotPayedIds)
                    ->where('status', '=', OrganizationDeal::DEAL_STATUS_APPROVE)
                    ->where('finish', '<>', 1)
                    ->where('winner_id', '=',  $organizationId)
                    ->where('trading_status', '=', OrganizationDeal::DEAL_TRADING_STATUS_WAITING_PAYMENT)
                    ->orderBy('created_at', 'ASC');

        return $dealsWaytingPayment;
        // dd($dealsWaytingPayment);
        // $memberIsWaytingPayment = \App\Models\Org\OrganizationDealMember::with('deal')->where('organization_id', '=', $organizationId)->where('trading_status', '=', OrganizationDeal::DEAL_TRADING_STATUS_WAITING_PAYMENT);
        // dd($memberIsWaytingPayment->get());

        // return $memberIsWaytingPayment;
    }
}