<?php

namespace App\Formatter\Api\v1;

use App\Formatter\Formatter;
use Illuminate\Pagination\Paginator;
use App\Models\Org\OrganizationDeal;
use App\Services\DealsQuestionsHeader\DealsQuestionsHeaderService;

use Auth;

class AdminDealsItemFormatter extends Formatter
{

    public static function format($item)
    {

        try{
            
            $user = Auth::user();
            $curUserId = $user ?  $user->id : null;

            $type_deal = $item->type_deal; // тип сделки

            // возьмем слаги для вопросов
            $questionsIds = $item->questions->pluck('question_id')->toArray();
            $slugQuestionsHeaders = DealsQuestionsHeaderService::getArrFromIdsArray( $questionsIds)->keyBy('id')->toArray();

            $arToCollect = [
                'cur_user' =>  $user ? $user->id : null,
                'id' => $item->id,
                'date_create' => $item->created_at,
                'date_update' => $item->updated_at,
                'date_published' => $item->published_at,
                'name' => $item->name,
                'type_deal' => $type_deal,
                'subtype_deal' => $item->subtype_deal,
                'description' => $item->description,
                'count_views' => $item->count_views,
                //'pay_type_cash' => $item->pay_type_cash,  //todo удалить после чистки
                //'pay_type_noncash' => $item->pay_type_noncash,  //todo удалить после чистки
                'budget_from' => $item->budget_from, 
                'budget_to' => $item->budget_to,
                'budget_all' => $item->budget_all,
                'budget_with_nds' => $item->budget_with_nds,
                'is_fake' => $item->is_fake,
                'url' => $item->url,
                'tags' => $item->tags,
                //'fast_deal' => $item->fast_deal, //todo удалить после чистки
                //'favorite_only' => $item->favorite_only, //todo удалить после чистки
                'deadline_deal' => $item->deadline_deal, 
                //'deadline_service' => $item->deadline_service, //todo удалить после чистки
                'question' => $user ? $item->question : null,
            
                'finish' => $item->finish,
                'finished_at' => $item->finished_at,
                'moderator_id'=> $item->moderator_id,
                'planned_finish' => $item->planned_finish,

                'status' => $item->status,
                'is_need_kp' => $item->is_need_kp,
                'payment_status' => $item->payment_status,
              //  'winner' => ($user && $item->winner_id == $user->organization_id) ? true : null,
                'winner_id' => $item->winner_id,
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
                    ]);
                }),
                'imagesDeals' => $item->imagesDeals->map(function ($itm, $key) {
                    return collect([
                        'id' => $itm->id,
                        'path' => $itm->file_full_path,
                    ]);
                }),

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
                'questions' => $item->questions->mapWithKeys(function ($itm, $key) use ($slugQuestionsHeaders) {
                    return collect([$slugQuestionsHeaders[$itm->question_id]['slug'] => [
                        'id' => $itm->id,
                        'deal_id' => $itm->deal_id,
                        'question_id' => $itm->question_id,
                        'slug' => $slugQuestionsHeaders[$itm->question_id]['slug'],
                        'name' => $itm->name,
                        'question' => $itm->question,
                    ]]);
                }),
                'members' => (!$user || !$item->relationLoaded('members')) ? null : $item->members->map(function ($itm, $key) {
                    return collect([
                        'organization' => OrganizationItemFormatter::format($itm),
                        'answers' => isset($itm['deal_answers']) ? $itm['deal_answers'] : null,
                    ])->reject(function ($item) {
                        return is_null($item);
                    });
                }),
                
            ];

            if($item->user->is_org_created){
                $arToCollect['organization'] = (!$item->organization) ? null : [
                    'id' => $item->organization->id,
                    'name' => $item->organization->org_name,
                    'org_products' => $item->organization->org_products,
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
            
            $arToCollect['owner'] = [
                'id' => $item->user->id,
                'name' => $item->user->name,
                'unique_id' => $item->user->unique_id,
                'is_org_created' => $item->user->is_org_created,
                'photo' => $item->user->photo,
                'email' => $item->user->email,
                'phone' => $item->user->phone,
            ];

            $arToCollect['files'] =  $item->files->map(function ($itm, $key) {
                return collect([
                    'id' => $itm->id,
                    'user_id' => $itm->user_id,
                    'name' => $itm->file_name,
                    'path' => $itm->file_full_path,
                ]);

            });

            if($type_deal ===  OrganizationDeal::DEAL_TYPE_BUY ){
        
                $arToCollect['unit_for_unit']           =  $item->unit_for_unit; // единица измерения за единицу товара (кг, литр)
                $arToCollect['unit_for_all'] 	        =  $item->unit_for_all; // единица измерения за полный объем товара (кг, литр)
                $arToCollect['count_unit_in_volume'] 	=  $item->count_unit_in_volume; // количество единиц в общем объеме (например объем 5 тонн - кол-во единиц 1000 (в килограммах ,это указано в unit_for_unit))
                $arToCollect['procent'] 		        =  $item->procent; // наш процент от сделки (смотрим по таблице зависимости процента от бюджета сделки
                $arToCollect['val_for_all'] 	        =  $item->val_for_all; // количество единиц товара (штук, ящиков...)
                $arToCollect['commission'] 	            =  $item->commission; // наша комиссия со сделки, в рублях
                $arToCollect['date_of_event']           =  $item->date_of_event; // дата проведения сделки, тут строка в свободной форме
    
                $countResponses = $item->countResponsesAdminka();
                $arToCollect['count_response'] = $countResponses['count_response'];
                $arToCollect['count_response_moderate'] = $countResponses['count_response_moderate'];
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
        // $count = $paginator->count();
        // $hasMore = $paginator->hasMorePages();
        $items = $paginator->map(function($item, $key) {
            return self::format($item);
        });

        $retArray =  $paginator->toArray();
        $retArray['data'] =$items;
        return  $retArray;

    }
}