<?php

namespace App\Models\Org;

use App\Traits\DataTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;
use App\Models\Payment\Subscription;
use App\Models\LogAdmin;
use App\Models\User;
use PDF;
use App\Models\ScoreDocs;
use Illuminate\Support\Facades\Auth;
use App\Services\DealsQuestionsHeader\DealsQuestionsHeaderService;

class OrganizationDeal extends Model
{
    use DataTable;
    use Notifiable;
    use SoftDeletes;

    const DEAL_TYPE_SELL = 'sell';  // продажа
    const DEAL_TYPE_BUY = 'buy';    // покупка

    const DEAL_SUBTYPE_NEW = 'new';  //новое
    const DEAL_SUBTYPE_USED = 'used';    // бу
    const DEAL_SUBTYPE_NA = 'na';    // без подтипа (для покупки , например)

    const DEAL_STATUS_MODERATION        = 'moderation'; // сделка на модерации
//    const DEAL_STATUS_APPROVE           = 'moderation'; // сделка проверена и разрешена к показу
    const DEAL_STATUS_APPROVE           = 'approve'; // сделка проверена и разрешена к показу - временно показываем заявки на модерации
    const DEAL_STATUS_ARCHIVE           = 'archive'; // сделка в архиве
    const DEAL_STATUS_BANNED            = 'banned';  // сделка заблокирована

    // статус оплаченности объявления
    const DEAL_STATUS_PAYMENT_PAID      = 'paid'; // размещение оплачено 
    const DEAL_STATUS_PAYMENT_NOT_PAID  = 'not_paid'; // размещение не оплачено и нуждается в оплате
    const DEAL_STATUS_PAYMENT_FREE      = 'free'; // размещение бесплатное0

    // статус торгов по сделке
    const DEAL_TRADING_STATUS_NA                = 'na'; // без статуса
    const DEAL_TRADING_STATUS_BANNED            = 'banned'; // отклик забанен
    const DEAL_TRADING_STATUS_MODERATION        = 'moderation'; // модерация отклика
    const DEAL_TRADING_STATUS_WAITING_WINNER    = 'waiting_winner'; // ожидание решения , кто победил
    const DEAL_TRADING_STATUS_WAITING_PAYMENT   = 'waiting_payment'; // ожидание оплаты
    const DEAL_TRADING_STATUS_FINISHED          = 'finished'; // торги закрыты и сделка оплачена



    protected $table = 'organizations_deals';
    protected $fillable = [
        'user_id',
        'type_deal',
        'subtype_deal',
        'name',
        'description',
        'pay_type_cash',
        'pay_type_noncash',
        'budget_from',
        'budget_to',
        'budget_all',
        'fast_deal',
        'favorite_only',
        'deadline_deal',
        'deadline_service',
        'question',
        'finished_at',
        'planned_finish',
        'is_need_kp',
        'manager_id', 
        'moderator_id', 

        'unit_for_unit',
        'unit_for_all',
        'count_unit_in_volume',
        'procent',
        'val_for_all',
        'commission',
        'date_of_event',
        'trading_status',              
    ];
    protected $dates = ['deleted_at', 'finished_at', 'published_at', 'planned_finish', 'winner_wating_payment_at'];
    static protected $sortable = ['published_at', 'id', 'name', 'organization_id', 'finish', 'status', 'fast_deal', 'favorite_only', 'budget_from', 'budget_to', 'created_at', 'finished_at',  'planned_finish'];

    // protected $appends = [
    //     'questions',
    // ];
    // /**
    //  * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    //  */
    // public function getQuestionsAttribute()
    // {
    //     return $this->questions()->get();
    // }

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
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     *  Get the tags associated with the given organization
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userViewDeal()
    {
        return $this->belongsToMany(\App\Models\User::class)->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function winner()
    {
        return $this->belongsTo(\App\Models\Org\Organization::class, 'winner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function cities()
    {
        return $this->belongsToMany(\App\Models\Kladr\City::class, 'organizations_deals_cities', 'deal_id', 'city_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(\App\Models\Category::class, 'organizations_deals_categories', 'deal_id', 'category_id');
    }
 
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function files()
    {
        return $this->hasMany(\App\Models\DealsFile::class, 'deal_id'); // один ко многим
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function myFiles($user_id)
    {
        return $this->hasMany(\App\Models\DealsFile::class, 'deal_id')->where('user_id', $user_id)->get(); // один ко многим
    }

    // /**
    //  * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
    //  */
    // public function questions()
    // {
    //     return $this->belongsToMany(\App\Models\DealQuestion::class, 'organizations_deals_questions', 'deal_id', 'question_id');
    // }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions()
    {
        return $this->hasMany(\App\Models\Org\OrganizationDealQuestion::class, 'deal_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questionsWithSlugs()
    {
        $questions = $this->questions;

        // возьмем слаги для вопросов
        $questionsIds = $questions->pluck('question_id')->toArray();
        $slugQuestionsHeaders = DealsQuestionsHeaderService::getArrFromIdsArray( $questionsIds)->keyBy('id')->toArray();

        $questionsWithSlugs =  $questions->mapWithKeys(function ($itm, $key) use ($slugQuestionsHeaders) {
            return collect( [$slugQuestionsHeaders[$itm->question_id]['slug'] => [
                'id' => $itm->id,
                'deal_id' => $itm->deal_id,
                'question_id' => $itm->question_id,
                'slug' => $slugQuestionsHeaders[$itm->question_id]['slug'],
                'name' => $itm->name,
                'question' => $itm->question,
            ]]);
        });

        return $questionsWithSlugs;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function members()
    {
        $members = $this->belongsToMany(\App\Models\Org\Organization::class, 'organizations_deals_members', 'deal_id', 'organization_id')->
                withPivot('trading_status', 'price_offer', 'is_shipping_included', 'notice', 'is_readed_owner_deal', 'is_readed_owner_response', 'created_at');
        
        return $members;
    }

    /**
     * @return collection
     */
    public function countResponsesAdminka()
    {
        $members = $this->members;
        
        $count_response_active_after_moderate = 0;
        $count_response_moderate = 0;

        foreach ($members as $member) {
            if($member->pivot->trading_status === self::DEAL_TRADING_STATUS_MODERATION){
                $count_response_moderate++;
            }
            if($member->pivot->trading_status === self::DEAL_TRADING_STATUS_WAITING_PAYMENT or $member->pivot->trading_status === self::DEAL_TRADING_STATUS_WAITING_WINNER){
                $count_response_active_after_moderate++;
            }
        }

        $countResponses = collect([
            'count_response' => $members->count(),
            'count_response_moderate' => $count_response_moderate,
            'count_response_active_after_moderate' => $count_response_active_after_moderate
        ]);

        return $countResponses;
    }

      /**
     * @return collection
     */
    public function countUnreadedResponsesOwnerDeal()
    {
        $members = $this->members;
        
        $count_unreaded_responses_owner_deal = 0;
        $count_response_active_after_moderate = 0;
   

        foreach ($members as $member) {
            if($member->pivot->trading_status === self::DEAL_TRADING_STATUS_MODERATION){
                $count_unreaded_responses_owner_deal++;
            }
            if($member->pivot->trading_status === self::DEAL_TRADING_STATUS_WAITING_PAYMENT or $member->pivot->trading_status === self::DEAL_TRADING_STATUS_WAITING_WINNER){
                $count_response_active_after_moderate++;
            }
        }

        $countResponses = collect([
            'count_response' => $members->count(),
            'count_response_moderate' => $count_unreaded_responses_owner_deal,
            'count_response_active_after_moderate' => $count_response_active_after_moderate
        ]);

        return $countResponses;
    }

    /**
     * countResponses - кол-во откликов на сайте
     *
     * @return collection
     */
    public function countResponses()
    {
        $members = $this->membersFromOrganizationMembersTable;
  
        $count_response_active_after_moderate = 0;
        $count_response_moderate = 0;

        foreach ($members as $member) {
            if($member['trading_status'] === self::DEAL_TRADING_STATUS_MODERATION){
                $count_response_moderate++;
            }
            if($member['trading_status'] === self::DEAL_TRADING_STATUS_WAITING_PAYMENT or $member['trading_status'] === self::DEAL_TRADING_STATUS_WAITING_WINNER){
                $count_response_active_after_moderate++;
            }
        }

        $countResponses = collect([
            'count_response' => $members->count(),
            'count_response_moderate' => $count_response_moderate,
            'count_response_active_after_moderate' => $count_response_active_after_moderate
        ]);

        return $countResponses;
    }

    
      
    /**
     * isUserMemberDeal
     *
     * @param  mixed $user
     *
     * @return boolean
     */
    public function isUserMemberDeal($user)
    {
        if($user && $user->is_org_created){
           // dd($user->organization_id);
            $members = $this->membersFromOrganizationMembersTable->where('organization_id', $user->organization_id)->first();
            if($members) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function membersFromOrganizationMembersTable()
    {
        return $this->hasMany(\App\Models\Org\OrganizationDealMember::class, 'deal_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function feedback()
    {
        return $this->hasMany(\App\Models\Feedback::class, 'deal_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers()
    {
        return $this->hasMany(\App\Models\Org\OrganizationDealAnswer::class, 'deal_id');
    }

    public function getAnswersByMember()
    {
        $answers = $this->answers()->with('question')->get();

        foreach($this->members as $key => $member) {
            $orgAnswers = [];

            foreach ($answers as $answer) {;
                if ($member->id == $answer->organization_id) {
                    $orgAnswers[] = [
                        'question_id' => $answer->question->id,
                        //'question' => ($answer->question_id) ? $answer->question->question : $this->question,
                        'answer' => $answer->answer,
                    ];
                }
            }

            $this->members[$key]['deal_answers'] = $orgAnswers;
        }

        return $this->members;
    }

    public function getAnswersAndQuestionsByMember($organization_id = null)
    { 

        $membersWithAnswers = OrganizationDealMember::with(['organizationWithUser', 'answers', 'answers.question', 'files'])->where('deal_id',  $this->id );
        if($organization_id){
            $membersWithAnswers =  $membersWithAnswers->where('organization_id', $organization_id);
        }
        $membersWithAnswers = $membersWithAnswers->get();
     //  dd( $membersWithAnswers->toArray());

        $arMembers = [];
        // возьмем слаги для вопросов
        $questionsIds = $this->questions->pluck('question_id')->toArray();
        $slugQuestionsHeaders = DealsQuestionsHeaderService::getArrFromIdsArray( $questionsIds)->keyBy('id')->toArray();
  
        foreach($membersWithAnswers as $key => $memberWithAnswers) {

            // отдавать организацию с юзером для покупателя, если только оплатил продавец
            $subscriptionToCurDeal = $memberWithAnswers->organizationWithUser->owner->subscriptionExtendedToLKFromSlug(Subscription::SUBSCRIPTION_PAYMENT_ONCE_DEAL_BUY_GET_CONTACTS, $memberWithAnswers->deal_id);
            $isPayedDeal = (count($subscriptionToCurDeal) > 0) ? true : false;

            $curMember = [
                'id' => $memberWithAnswers->id,
                'trading_status' => $memberWithAnswers->trading_status,
                'files' => $memberWithAnswers->files,
                'price_offer' => $memberWithAnswers->price_offer,
                'is_shipping_included' => $memberWithAnswers->is_shipping_included,
                'notice' => $memberWithAnswers->notice,
                'is_readed_owner_response' => $memberWithAnswers->is_readed_owner_response,
                'is_readed_owner_deal' => $memberWithAnswers->is_readed_owner_deal,
            ];

            if($isPayedDeal){
                $curMember['organization_with_user'] = $memberWithAnswers->organizationWithUser->toArray();
            }
            
            $curMember['answers'] = [];
           
            foreach ($memberWithAnswers->answers as $keyAnswer => $answer) { 
                $question_id = (isset($answer->question) and isset($answer->question->question_id)) ? $answer->question->question_id : null;
                if ($question_id === null) {
                    continue;
                }
                $slug = (count($slugQuestionsHeaders) > 0 and isset($slugQuestionsHeaders[$answer->question->question_id]['slug'])) ? $slugQuestionsHeaders[$answer->question->question_id]['slug'] :  $keyAnswer;
                $curMember['answers'][$slug] = [
                    'id' => $answer->id,
                    'deal_id' => $answer->deal_id,
                    'question_id' => $answer->question_id,
                  //  'slug' => $slugQuestionsHeaders[$answer->question_id]['slug'],
                    'is_agree' => $answer->is_agree,
                ];
            }
 
            $arMembers[$memberWithAnswers->id] =  $curMember;
        }
        return $arMembers;       
  
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function imagesDeals()
    {
        return $this->hasMany(\App\Models\Org\ImagesDeals::class, 'deal_id'); // один ко многим
    }

    public function favouritedFromUsers()
    {
        return $this->belongsToMany(\App\Models\User::class, 'user_selected_deals','deal_id',  'user_id' );
    }

    public function paymentServices()
    {
        return $this->hasMany(\App\Models\Payment\UserSubscription::class, 'deal_id');
    }

    public function paymentActiveServices()
    {
        return $this->paymentServices()->where('status' , '=', \App\Models\Payment\Subscription::SUBSCRIPTION_STATUS_ACTIVE)->orderBy('started_at', 'DESC');
    }

    public function paymentNotFinishedServices()
    {
        return $this->paymentServices()->where('status' , '<>', \App\Models\Payment\Subscription::SUBSCRIPTION_STATUS_FINISHED)->orderBy('started_at', 'DESC');
    }

    public function finishPaymentActiveService()
    {   
        return $this->paymentActiveServices()
                    ->where('status' , '<>', \App\Models\Payment\Subscription::SUBSCRIPTION_STATUS_FINISHED)
                    ->update(['status' =>  \App\Models\Payment\Subscription::SUBSCRIPTION_STATUS_FINISHED, 'finished_at' => Carbon::now()]);
    }

    public function finishPaymentAllService()
    {   
        return $this->paymentNotFinishedServices()
                    ->where('status' , '<>', \App\Models\Payment\Subscription::SUBSCRIPTION_STATUS_FINISHED)
                    ->update(['status' =>  \App\Models\Payment\Subscription::SUBSCRIPTION_STATUS_FINISHED, 'finished_at' => Carbon::now()]);
    }

    public function finishForMembers()
    {
        return $this->membersFromOrganizationMembersTable()
                    ->where('trading_status' , '<>', self::DEAL_TRADING_STATUS_FINISHED)
                    ->update(['trading_status' =>  self::DEAL_TRADING_STATUS_FINISHED]);
    }


    public function score ($unique_id, $summ, $userCreateScore) {
        $user = User::where('unique_id', $unique_id)->with('organization')->first();

        $monthes = [
            1 => 'января', 2 => 'Февраля', 3 => 'марта', 4 => 'апреля',
            5 => 'мая', 6 => 'июня', 7 => 'июля', 8 => 'августа',
            9 => 'сентября', 10 => 'октября', 11 => 'ноября', 12 => 'декабря'
        ];
        $dt = strtotime(Carbon::now()->format('d.m.Y H:i:s'));
        $dt_str = date('j ' . $monthes[date('n', $dt)] . ' Y', $dt);
        $str_total_price_score = $this->mb_ucfirst($this->num2str($summ));

        $numberScore = $this->getNumberScore($unique_id);

        $this->saveScore($user, $numberScore, $dt_str, $summ, $userCreateScore);

        setlocale(LC_ALL, 'ru_RU.UTF-8');
        $pdf = new PDF();
        $pdf::setOptions(['dpi' => 150, 'defaultFont' => 'Times-Roman']);
        $pdf::setPaper('A4', 'landscape');
        $fileName = $unique_id . '_' . $numberScore . '.pdf';
        $pdf::loadView('admin.score.score', ['user' => $user, 'unique_id' => $unique_id, 'numberScore' => $numberScore,
            'dt_str' => $dt_str, 'str_summ' => $str_total_price_score, 'summ' => $summ])->save($fileName);

        $data=[
            "unique_id"=>$unique_id,
            "summ"=>$summ

        ];

        $log = [
            'method' => __METHOD__,
            'data' => json_encode($data),
            'user_id' => $userCreateScore->id
        ];
        LogAdmin::create($log)->save();

        return $fileName;
    }

    private function getNumberScore($unique_id)
    {
        $score = ScoreDocs::where('unique_id', $unique_id)->orderBy('created_at', 'desc')->first();
        if (empty($score))
            return 1;

        return $score->number_score + 1;
    }
    private function saveScore($user, $numberScore, $dt_score, $summ, $userCreateScore)
    {
        $model = new ScoreDocs();
        $model->owner_id = $user->id;
        $model->organization_id = $user->organization->id;
        $model->unique_id = $user->unique_id;
        $model->number_score = $numberScore;
        $model->dt_score = $dt_score;
        $model->summ = $summ;
        $model->user_id = $userCreateScore->id;
        $model->save();
    }


    private function mb_ucfirst($word, $charset = "utf-8")
    {
        return mb_strtoupper(mb_substr($word, 0, 1, $charset), $charset) . mb_substr($word, 1, mb_strlen($word, $charset) - 1, $charset);
    }

    private function num2str($num)
    {
        $nul = 'ноль';
        $ten = [
            ['', 'один', 'два', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'],
            ['', 'одна', 'две', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'],
        ];
        $a20 = ['десять', 'одиннадцать', 'двенадцать', 'тринадцать', 'четырнадцать', 'пятнадцать',
            'шестнадцать', 'семнадцать', 'восемнадцать', 'девятнадцать'];
        $tens = [2 => 'двадцать', 'тридцать', 'сорок', 'пятьдесят', 'шестьдесят', 'семьдесят', 'восемьдесят', 'девяносто'];
        $hundred = ['', 'сто', 'двести', 'триста', 'четыреста', 'пятьсот', 'шестьсот', 'семьсот', 'восемьсот', 'девятьсот'];
        $unit = [ // Units
            ['копейка', 'копейки', 'копеек', 1],
            ['рубль', 'рубля', 'рублей', 0],
            ['тысяча', 'тысячи', 'тысяч', 1],
            ['миллион', 'миллиона', 'миллионов', 0],
            ['миллиард', 'милиарда', 'миллиардов', 0],
        ];
        //
        $num = str_replace(',', '.', $num);
        list($rub, $kop) = explode('.', sprintf("%015.2f", floatval($num)));
        $out = [];
        if (intval($rub) > 0) {
            foreach (str_split($rub, 3) as $uk => $v) { // by 3 symbols
                if (!intval($v))
                    continue;
                $uk = sizeof($unit) - $uk - 1; // unit key
                $gender = $unit[$uk][3];
                list($i1, $i2, $i3) = array_map('intval', str_split($v, 1));
                // mega-logic
                $out[] = $hundred[$i1]; # 1xx-9xx
                if ($i2 > 1)
                    $out[] = $tens[$i2] . ' ' . $ten[$gender][$i3]; # 20-99
                else $out[] = $i2 > 0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
                // units without rub & kop
                if ($uk > 1)
                    $out[] = $this->morph($v, $unit[$uk][0], $unit[$uk][1], $unit[$uk][2]);
            } //foreach
        } else $out[] = $nul;
        $out[] = $this->morph(intval($rub), $unit[1][0], $unit[1][1], $unit[1][2]); // rub
        $out[] = $kop . ' ' . $this->morph($kop, $unit[0][0], $unit[0][1], $unit[0][2]); // kop
        return trim(preg_replace('/ {2,}/', ' ', join(' ', $out)));
    }

    /**
     * Склоняем словоформу
     */
    private function morph($n, $f1, $f2, $f5)
    {
        $n = abs(intval($n)) % 100;
        if ($n > 10 && $n < 20)
            return $f5;
        $n = $n % 10;
        if ($n > 1 && $n < 5)
            return $f2;
        if ($n == 1)
            return $f1;
        return $f5;
    }
		public function routeNotificationForSlack()
		{
			return 'https://hooks.slack.com/services/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';
		}
}
