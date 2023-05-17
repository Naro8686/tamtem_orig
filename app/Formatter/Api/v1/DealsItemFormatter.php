<?php

namespace App\Formatter\Api\v1;

use App\Formatter\Formatter;
use Illuminate\Pagination\Paginator;

use App\Models\Org\OrganizationDeal;
use App\Models\Payment\Subscription;
use App\Models\Payment\UserSubscription;
use App\Services\DealsQuestionsHeader\DealsQuestionsHeaderService;

use Auth;

class DealsItemFormatter extends Formatter
{

    public static function format($item)
    {

        try{
            
            $user = Auth::guard('api')->user();
            $curUserId = $user ?  $user->id : null;

            // ============================== Платность услуги показа контактов в объявлении о куплю (вопле) ========================================
            $isLogined = ($user) ? true : false;

            if($isLogined){
                //$paymentService = new PaymentServicesController();
                $isProAccount = $user->isProAccount();
            // $paymentInfo = $paymentService->paymentServiceIsPossible(null, Subscription::SUBSCRIPTION_PAYMENT_ONCE_DEAL_BUY_GET_CONTACTS , true);
                //dd($isProAccount);
                $idsDealsBuyContacts = $user->idsDealsBuyContacts(); // id объявлений с проплаченными контактами
            //  dd($idsDealsBuyContacts);
            }
            // ==============================END  Платность услуги показа контактов в объявлении о куплю (вопле) =====================================

            $type_deal = $item->type_deal; // тип сделки
            //dd($item->imagesDeals);
            $isPayed = isset($idsDealsBuyContacts[$item->id]);

            if ($type_deal === OrganizationDeal::DEAL_TYPE_BUY){
                // возьмем слаги для вопросов
                $questionsIds = $item->questions->pluck('question_id')->toArray();
                $slugQuestionsHeaders = DealsQuestionsHeaderService::getArrFromIdsArray( $questionsIds)->keyBy('id')->toArray();
                 // сколько активных (после модерации откликов)
                $countResponses = $item->countResponses();
                $count_response_active_after_moderate = $countResponses['count_response_active_after_moderate'];
            }

            // универсальные данные для продажи и покупки
            $arToCollect = [
                //   'cur_user' =>  $user ? $user->id : null,
                'id' => $item->id,
                'date_create' => $item->created_at,
                'date_update' => $item->updated_at,
                'date_published' => $item->published_at,
                'is_payed' => $isPayed,
                'name' => $item->name,
                'type_deal' => $type_deal,
                'subtype_deal' => $item->subtype_deal,
                'description' => $item->description,
                'count_views' => $item->count_views,
                'url' => $item->url,
                'is_fake' => $item->is_fake,
                'deadline_deal' => $item->deadline_deal,           
                'finish' => $item->finish,
                'finished_at' => $item->finished_at,
                'planned_finish' => $item->planned_finish,
                'status' => $item->status,
                'payment_status' => $item->payment_status,
            //    'winner' => ($user && $item->winner_id == $user->organization_id) ? true : null,
                'trading_status' => $item->trading_status,
                'date_of_event' => $item->date_of_event,

                'categories' => $item->categories->map(function ($itm, $key) {
                    $catIcon = \App\Repositories\CategoryRepository::getRootParentFromId($itm->id);
                    return collect([
                        'id' => $itm->id,
                        'name' => $itm->name,
                        'cl_icon' => $catIcon->cl_icon,
                        'cl_background' => $catIcon->cl_background,
                    ]);
                }),
                'cities' => $item->cities->map(function ($itm, $key) {
                    return collect([
                        'id' => $itm->id,
                        'name' => $itm->name,
                        'region_id' => $itm->region->id,
                        'region_name' => $itm->region->name,
                    ]);
                }),
                'imagesDeals' => $item->imagesDeals->map(function ($itm, $key) {
                    return collect([
                        'id' => $itm->id,
                        'path' => $itm->file_full_path,
                    ]);
                }),
                
                'files' => $item->files->map(function ($itm, $key) {
                        return collect([
                            'id' => $itm->id,
                            'user_id' => $itm->user_id,
                            'name' => $itm->file_name,
                            'path' => $itm->file_full_path,
                        ]);
                }),
                // 'myFiles' => $item->myFiles($item->user_id)->map(function ($itm, $key) {
                //         return collect([
                //             'id' => $itm->id,
                //             'user_id' => $itm->user_id,
                //             'name' => $itm->file_name,
                //             'path' => $itm->file_full_path,
                //         ]);

                // }),
                'favourited' =>  $curUserId ? 
                                (
                                    ($item->favouritedFromUsers->search(function ($item, $key) use ( $curUserId) {
                                        return $item->id === $curUserId;
                                    }) !== false) ? true: false
                                ) : 
                                false,
                // 'favouritedFromUsers' => $item->favouritedFromUsers->map(function ($itm, $key) {
                //     return collect([
                //         'id' => $itm->id,
                //     ]);
                // }),
             //   'questions' => (!$user || !$item->relationLoaded('questions')) ? null : $item->questions->mapWithKeys(function ($itm, $key) use ($slugQuestionsHeaders) {


            ];

            if ($type_deal ===  OrganizationDeal::DEAL_TYPE_BUY){

                $arBuy = [
                    'budget_from' => $item->budget_from,
                    'budget_to' => $item->budget_to,
                    'budget_all' => $item->budget_all,
                    'budget_with_nds' => $item->budget_with_nds,
                    'question' => $user ? $item->question : null,
                    'is_need_kp' => $item->is_need_kp,
                    'winner_id' => $item->winner_id,
                    'unit_for_unit' => $item->unit_for_unit,
                    'val_for_all' => $item->val_for_all,
                    'unit_for_all' => $item->unit_for_all,
                    'commission' => (isset($idsDealsBuyContacts) and count($idsDealsBuyContacts)) > 0 ? $item->commission : 200,
                    'count_response_active_after_moderate' => $count_response_active_after_moderate,
                    'questions' => (!$item->relationLoaded('questions')) ? null : $item->questions->mapWithKeys(function ($itm, $key) use ($slugQuestionsHeaders) {
                        return collect( [$slugQuestionsHeaders[$itm->question_id]['slug'] => [
                            'id' => $itm->id,
                            'deal_id' => $itm->deal_id,
                            'question_id' => $itm->question_id,
                            'slug' => $slugQuestionsHeaders[$itm->question_id]['slug'],
                            'name' => $itm->name,
                            'header' => $itm->questionHeader->name,
                            'question' => $itm->question,
                        ]]);
                    }),
                ];
                $arToCollect = array_merge($arToCollect, $arBuy);
            }
//dd($item->toArray());

            // если юзер - участник сделки - покажем его ответы
            $isUserMemberDeal = ($item->isUserMemberDeal($user) === true) ? true : false;
            if($isUserMemberDeal){
                $members = $item->members;
                foreach($members as $key => $member){

                    if(isset($member['files'])){
                        $arToCollect['files_my_response'] = $member['files']->map(function ($itm, $key) {
                            return collect([
                                'id' => $itm->id,
                                'user_id' => $itm->user_id,
                                'name' => $itm->file_name,
                                'path' => $itm->file_full_path,
                            ]);
                        });
                    }
                    else{
                        $arToCollect['files_my_response'] = collect([]);
                    }

                    // если юзер участник сделки , и его отклик Не забанен, то показываем его ответы
                    $arToCollect['response_id'] = $member['id'];
                    $arToCollect['trading_status_my_response'] = $member['trading_status'];
                    if(isset($member['answers']) and $member['trading_status'] !== OrganizationDeal::DEAL_TRADING_STATUS_BANNED){ 
                        $arToCollect['my_answers'] = $member['answers'];
                        
                        $arToCollect['price_offer'] = $member['price_offer'];
                        $arToCollect['is_shipping_included'] = $member['is_shipping_included'];
                        $arToCollect['notice'] = $member['notice'];
                    }
                }
            }
//dd($item->toArray());
            // если объявление о покупке и его id в списке id которые проплатил юзер или это свое же объявление  или же просто объявление о продаже - контакты ПОКАЗАТЬ
            if( $isLogined === true  AND
                (
                    ($type_deal ===  OrganizationDeal::DEAL_TYPE_BUY and ($isPayed or $item->user->id === $curUserId))  
                     or $type_deal ===  OrganizationDeal::DEAL_TYPE_SELL or $item->user->isAdmin()
 //                    or $isProAccount === true
                )
            ){
                //$arToCollect['trading_status'] = $item->trading_status;
                // $arToCollect['date_of_event'] = $item->date_of_event;

                if($item->user->is_org_created){
                    $arToCollect['organization'] = (!$item->organization) ? null : [
                        'id' => $item->organization->id,
                        'name' => $item->organization->org_name,
                        'org_products' => $item->organization->org_products,
                        'org_city_id' => $item->organization->org_city_id,
                        'org_city_name' => $item->organization->org_address,
                        'org_inn' => $item->organization->org_inn,
                        'org_manager_post' => $item->organization->org_manager_post,
                        'org_okved' => $item->organization->org_okved,
                        'org_ogrn' => $item->organization->org_ogrn,
                        'org_is_active' => $item->organization->org_is_active,
                        'org_registration_date' => $item->organization->org_registration_date,
                        'image_1' => $item->organization->image_1,
                    ];
                }
                else{
                    $arToCollect['user_categories'] =  $item->organization->categories->map(function ($itm, $key) {
                        $catIcon = \App\Repositories\CategoryRepository::getRootParentFromId($itm->id);
                        return collect([
                            'id' => $itm->id,
                            'name' => $itm->name,
                            'cl_icon' => $catIcon->cl_icon,
                            'cl_background' => $catIcon->cl_background,
                        ]);
                    });
                }
              
                if($item->user->id === $curUserId){
                    // $arToCollect['members'] = (!$user || !$item->relationLoaded('members')) ? null : $item->members->map(function ($itm, $key) {
                    //     return collect([
                    //         'organization' => OrganizationItemFormatter::format($itm),
                    //         'answers' => isset($itm['deal_answers']) ? $itm['deal_answers'] : null,
                    //     ])->reject(function ($item) {
                    //         return is_null($item);
                    //     });
                    // });


                    $winnerId = $item->winner_id; // организация - победитель торгов             

                    $members = $item->members; 
                    $count_unreaded_responses = 0;

                    foreach($members as $key => $member){

                        $curTradingStatus = $member['trading_status'];
                        if(isset($member->pivot) and $member->pivot->is_readed_owner_deal === 0){
                            $count_unreaded_responses ++ ;
                        }
                        elseif(isset($member->is_readed_owner_deal) and $member->is_readed_owner_deal === 0){
                            $count_unreaded_responses ++ ;
                        }

                        if(isset($member['organization_with_user']) and isset($member['organization_with_user']['id'])  
                                and ((int)$winnerId !== (int)$member['organization_with_user']['id'])){
                            unset($members[$key]['organization_with_user']);
                        }
                        if($curTradingStatus !== null and ($curTradingStatus === OrganizationDeal::DEAL_TRADING_STATUS_NA or 
                                                           $curTradingStatus === OrganizationDeal::DEAL_TRADING_STATUS_BANNED or 
                                                           $curTradingStatus === OrganizationDeal::DEAL_TRADING_STATUS_MODERATION )){ // если юзер не оплатил или еще как-то выбыл из борьбы
                            unset($members[$key]);
                        }
                    }

                    $arToCollect['members'] = $members;
                    $arToCollect['count_unreaded_responses'] = $count_unreaded_responses;
                }

                $arToCollect['owner'] = [
                    'id' => $item->user->id,
                    'name' => $item->user->name,
                    // 'unique_id' => $item->user->unique_id,
                    'is_org_created' => $item->user->is_org_created,
                    'photo' => $item->user->photo,
                    'email' => $item->user->email,
                    'phone' => $item->user->phone,
                ];

                // $arToCollect['files'] =  $item->files->map(function ($itm, $key) {
                //     return collect([
                //         'id' => $itm->id,
                //         'user_id' => $itm->user_id,
                //         'name' => $itm->file_name,
                //         'path' => $itm->file_full_path,
                //     ]);

                // });
                // $arToCollect['myFiles'] = $item->myFiles($item->user_id)->map(function ($itm, $key) {
                //         return collect([
                //             'id' => $itm->id,
                //             'user_id' => $itm->user_id,
                //             'name' => $itm->file_name,
                //             'path' => $itm->file_full_path,
                //         ]);

                // });


            }

            if(!isset($arToCollect['members'])) {
                $arToCollect['members'] = [];
            }
            $ret = collect($arToCollect)->reject(function ($item) {
                return is_null($item);
            });

            return  $ret;
        }
       	catch(\Exception $e){
            return ['result' => false, 'error' => $e->getMessage()];
		}
       
    }

    public static function formatPaginator($paginator)
    {
        $count = $paginator->count();
        $hasMore = $paginator->hasMorePages();
        $items = $paginator->map(function($item, $key) {
            return self::format($item);
        });

        return [
            'count' => $count,
            'hasMore' => $hasMore,
            'items' => $items,
        ];
    }

}