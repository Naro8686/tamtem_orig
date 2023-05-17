<?php

namespace App\Formatter\Api\v1;

use App\Formatter\Formatter;
use Illuminate\Pagination\Paginator;
use App\Models\Org\OrganizationDeal;
use App\Services\DealsQuestionsHeader\DealsQuestionsHeaderService;

use Auth;

class AdminResponsesItemFormatter extends Formatter
{

    public static function format($item, $isResponceSeller = false)
    {

        try{

            $arToCollect = [
                'id' => $item->id,
                'organization_id' => $item->organization_id,
                'deal_name' => $item->deal->name,
                'trading_status' => $item->trading_status,
                'price_offer' => $item->price_offer,
                'is_shipping_included' => $item->is_shipping_included,
                'notice' => $item->notice,
                'created_at' => $item->created_at,
                'files' => $item->files->map(function ($itm, $key) {
                    return collect([
                        'id' => $itm->id,
                        'user_id' => $itm->user_id,
                        'name' => $itm->file_name,
                        'path' => $itm->file_full_path,
                    ]);
                }),
                'user_id' => $item->organizationWithUser->owner_id,
                'user_name' => $item->organizationWithUser->owner->name,
                'user_unique_id' => $item->organizationWithUser->owner->unique_id,
               
            ];
  
            if(isset($item->answers) and $isResponceSeller === true){

                // возьмем слаги для вопросов
                $questionsIds = $item->deal->questions->pluck('question_id')->toArray();

                $slugQuestionsHeaders = DealsQuestionsHeaderService::getArrFromIdsArray( $questionsIds)->keyBy('id')->toArray();

                if(count($slugQuestionsHeaders)> 0){

                    $arToCollect['answers'] = $item->answers->mapWithKeys(function ($itm, $key) use ($slugQuestionsHeaders) {

                        $question = $itm->question;
                        if(!isset($slugQuestionsHeaders[$question->question_id])){
                            return collect();
                        }
                        return collect( [$slugQuestionsHeaders[$question->question_id]['slug'] => [
                            'id' => $itm->id,
                            'deal_id' => $itm->deal_id,
                            'organization_id' => $itm->organization_id,
                            'question_id' => $itm->question_id,
                            'question' =>$question->question,
                            //'slug' => $slugQuestionsHeaders[$question->question_id]['slug'],
                            'is_agree' => $itm->is_agree,
                            'answer' => $itm->answer,
                        ]]);
                    });
                }
           
            }
            // $ret = collect($arToCollect)->reject(function ($item) {
            //     return is_null($item);
            // });
            $ret = collect($arToCollect);
            return  $ret;
        }
       	catch(\Exception $e){
            return ['result' => false, 'error' => $e->getMessage()];
		}
       
    }

    public static function formatPaginator($paginator, $isResponceSeller = false)
    {
        $count = $paginator->count();
        $hasMore = $paginator->hasMorePages();
        $items = $paginator->map(function($item, $key) use ($isResponceSeller) {
            return self::format($item, $isResponceSeller);
        });

        $retArray =  $paginator->toArray();
        $retArray['data'] = $items;

                    
     //   $user = Auth::user();
      //  $retArray['data']['cur_manager'] = $user ? $user->id : null;
        
        return  $retArray;

    }
}