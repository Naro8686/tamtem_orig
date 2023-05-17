<?php

namespace App\Formatter\Api\v1;

use App\Formatter\Formatter;

use App\Models\Org\OrganizationDeal;

use App\Models\Payment\Subscription;

use App\Services\DealsQuestionsHeader\DealsQuestionsHeaderService;

use Auth;

class DealsResponsesCurrentUserItemFormatter extends Formatter
{

    public static function format($item)
    {

        try{
            
            $user = Auth::guard('api')->user();
          //  $curUserId = $user ?  $user->id : null;

            // ============================== Платность услуги показа контактов ========================================
                $isProAccount = $user->isProAccount();
                $idsDealsBuyContacts = $user->idsDealsBuyContacts(); // id объявлений с проплаченными контактами
            // ==============================END  Платность услуги показа контактов =====================================

            // если сделка завершена и выйграл не текущий юзер,то не показывать
            // if($item->trading_status ===  OrganizationDeal::DEAL_TRADING_STATUS_FINISHED and ($item->deal->winner_id !== $user->organization->id)){
            //     return null;
            // }
            
            $type_deal = $item->deal->type_deal; // тип сделки

            $isPayed = isset($idsDealsBuyContacts[$item->deal->id]);

            // возьмем слаги для вопросов
            $questionsIds = $item->deal->questions->pluck('question_id')->toArray();
            $slugQuestionsHeaders = DealsQuestionsHeaderService::getArrFromIdsArray( $questionsIds)->keyBy('id')->toArray();

            $arToCollect = [
                'id' => $item->id,
                'trading_status' => $item->trading_status,
                'price_offer' => $item->price_offer,
                'trading_status' => $item->trading_status,
                'is_shipping_included' => $item->is_shipping_included,
                'notice' => $item->notice,
                'files' => $item->files->map(function ($itm, $key) {
                    return collect([
                        'id' => $itm->id,
                        'user_id' => $itm->user_id,
                        'name' => $itm->file_name,
                        'path' => $itm->file_full_path,
                    ]);
                }),
                'is_readed_owner_response' => $item->is_readed_owner_response,
                'is_readed_owner_deal' => $item->is_readed_owner_deal,
                'date_create' => $item->created_at,
                'date_update' => $item->updated_at,


                'deal' =>[
                    'id' => $item->deal->id,
                    'name' => $item->deal->name,
                    'is_payed' => $isPayed,
                    'type_deal' => $type_deal,
                    'date_of_event' => $item->deal->date_of_event,
                    'winner_id' => $item->deal->winner_id,
                    'winner_wating_payment_at' => $item->deal->winner_wating_payment_at,
//                    'description' => $item->description,
//
                    'commission' => count($idsDealsBuyContacts) > 0 ? $item->deal->commission : 200,
                    'slug_payment_type' => Subscription::SUBSCRIPTION_PAYMENT_ONCE_DEAL_BUY_GET_CONTACTS,

                    'unit_for_unit' => $item->deal->unit_for_unit, 
                    'unit_for_all' => $item->deal->unit_for_all, 
                    'budget_from' => $item->deal->budget_from, 
                    'budget_to' => $item->deal->budget_to,
                    'budget_all' => $item->deal->budget_all,
                    'budget_with_nds' => $item->deal->budget_with_nds,
                    'is_fake' => $item->deal->is_fake,
                    'date_published' => $item->deal->published_at,
                    'questions' => $item->deal->questions->mapWithKeys(function ($itm, $key) use ($slugQuestionsHeaders) {
                        return collect( [$slugQuestionsHeaders[$itm->question_id]['slug'] => [
                            'id' => $itm->id,
                            'deal_id' => $itm->deal_id,
                            'question_id' => $itm->question_id,
                            'slug' => $slugQuestionsHeaders[$itm->question_id]['slug'],
                            'name' =>" $itm->name",
                            'header' => $itm->questionHeader->name,
                            'question' => $itm->question,
                        ]]);
                    }),                    
                    'files' => $item->deal->files->map(function ($itm, $key) {
                        return collect([
                            'id' => $itm->id,
                            'user_id' => $itm->user_id,
                            'name' => $itm->file_name,
                            'path' => $itm->file_full_path,
                        ]);
                    }),
                ],               
            ];
//dd($arToCollect);
            // если объявление о покупке и его проплатил юзер
            if($type_deal ===  OrganizationDeal::DEAL_TYPE_BUY and $isPayed){

                if($item->deal->user->is_org_created){
                    $arToCollect['deal']['organization'] = (!$item->deal->organization) ? null : [
                        'id' => $item->deal->organization->id,
                        'name' => $item->deal->organization->org_name,
                        'org_products' => $item->deal->organization->org_products,
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
              
                $arToCollect['deal']['owner'] = [
                    'id' => $item->deal->user->id,
                    'name' => $item->deal->user->name,
                    // 'unique_id' => $item->user->unique_id,
                    //'is_org_created' => $item->user->is_org_created,
                    'photo' => $item->deal->user->photo,
                    'email' => $item->deal->user->email,
                    'phone' => $item->deal->user->phone,
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

        // $items = $items->reject(function ($item) {
        //     return is_null($item);
        // });
        // $count = ($items->count());
        return [
            'count' => $count,
            'hasMore' => $hasMore,
            'items' => $items,
        ];
    }

}