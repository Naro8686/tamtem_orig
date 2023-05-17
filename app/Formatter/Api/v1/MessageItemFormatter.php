<?php
/**
 * Created by black40x@yandex.ru
 * Date: 29/11/2018
 */

namespace App\Formatter\Api\v1;


use App\Formatter\Formatter;
use Illuminate\Pagination\Paginator;

class MessageItemFormatter extends Formatter
{

    public static function format($item)
    {
        
        $retCollect =  collect([
            'id' => $item->id,
            'date_create' => $item->created_at,

            'user' => [
                'id' => $item->user->id,
                'name' => $item->user->name,
                'org_name' => $item->user->organization->org_name,
              //  'logo' => $item->user->organization->logo
                'photo' => $item->user->photo

            ],
            'message' => $item->message,
            'status'  => $item->status,
            'type'  => $item->type
        ])->reject(function ($item) {
            return is_null($item);
        });
    
        $retCollect['files'] = $item->files()->select('id', 'file_full_path', 'file_name')->get()->toArray();

        return $retCollect;
    }

    public static function formatPaginatorExtented($paginator, $organization, $dealInfo)
    {
        try{
            $count = $paginator->count();
            $opponentUserId = $organization->owner->id;
            $hasMore = $paginator->hasMorePages();   
            $items = $paginator->map(function($item, $key)  use($opponentUserId) {
            if($item->user->id === $opponentUserId){ // помечаем прочитанными толко сообщения от другого, а не свои
                    $item->markAsReaded();
                }    
                return self::format($item);
            });
   
            $returnMessage = [
                'count' => $count,
                'hasMore' => $hasMore,
                'deal' => [
                    'id' => $dealInfo['deal_id'],
                    'name' => $dealInfo['deal_name'],
                ],
                'items' => $items,
                'organization' => [
                    'id' => $organization->id,
                    'name' => $organization->org_name,
                    'logo' => $organization->logo,
                ],
                'user' => [
                    'id' => $organization->owner->id,
                    'name' => $organization->owner->name,
                    'photo' => $organization->owner->photo,
                ]
            ];

            return $returnMessage;
        }
        catch(\Exception $e){       
            return ['result' => false, 'error' => $e->getMessage()];
        }
    }

    public static function formatPaginator($paginator) {}
}