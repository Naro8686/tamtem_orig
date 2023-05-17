<?php

namespace App\Http\Controllers\Client\Api\v1;

use App\Formatter\Api\v1\DealsItemFormatter;
use App\Formatter\Api\v1\NewsItemFormatter;
use App\Formatter\Api\v1\OrganizationItemFormatter;
use App\Repositories\Filters\FilterDealsRepository;
use App\Repositories\Filters\FilterNewsRepository;
use App\Repositories\Filters\FilterOrganizationRepository;
use App\Traits\ApiControllerTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \App\Models\Org\OrganizationDeal;
use App\Rules\CategoriesRule;
use App\Rules\CitiesRule;
use App\Rules\RegionsRule;
use Validator;

class FilterController extends Controller
{
    use ApiControllerTrait;

    /**
     * Filter organizations by category and KLADR place
     *
     * @param Request $request
     * @return array
     */
    public function organization(Request $request)
    {
        
        $input = $request->all();
        
        $validator = Validator::make($input, [
            'categories'        => ['sometimes', 'array', new CategoriesRule()],
            'cities'            => ['sometimes', 'array', new CitiesRule()],
            'search'            => 'sometimes|min:2|max:50',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages());
        }


        $categories         = (empty( $input['categories'])) ? false :  array_filter($input['categories']) ;
        $countries          = false; 
        $regions            = false; 
        $cities             = (empty( $input['cities'])) ? false : array_filter($input['cities']) ;
        $search             = (empty( $input['search'])) ? false : $input['search'] ;

        return $this->successResponse(
            OrganizationItemFormatter::formatPaginator(
                FilterOrganizationRepository::filter(
                    $categories,
                    $countries,
                    $regions,
                    $cities,
                    $search
                )->simplePaginate(12)
            )
        );
    }

    /**
     * @param Request $request
     * @return array
     */
    public function markers(Request $request)
    {
        return $this->successResponse(
            OrganizationItemFormatter::formatMarkers(
                FilterOrganizationRepository::filter(
                    array_filter(explode(',', $request->get('category'))),
                    array_filter(explode(',', $request->get('country'))),
                    array_filter(explode(',', $request->get('region'))),
                    array_filter(explode(',', $request->get('city')))
                )->approve()->simplePaginate(1000)
            )
        );
    }

    /**
     * @param Request $request
     * @return array
     */
    public function news(Request $request)
    {
        return $this->successResponse(
            NewsItemFormatter::formatPaginator(
                FilterNewsRepository::filter(
                    array_filter(explode(',', $request->get('category'))),
                    array_filter(explode(',', $request->get('country'))),
                    array_filter(explode(',', $request->get('region'))),
                    array_filter(explode(',', $request->get('city')))
                )->news()->approve()->simplePaginate(12)
            )
        );
    }

    /**
     * @param Request $request
     * @return array
     */
    public function stocks(Request $request)
    {
        return $this->successResponse(
            NewsItemFormatter::formatPaginator(
                FilterNewsRepository::filter(
                    array_filter(explode(',', $request->get('category'))),
                    array_filter(explode(',', $request->get('country'))),
                    array_filter(explode(',', $request->get('region'))),
                    array_filter(explode(',', $request->get('city')))
                )->stock()->approve()->simplePaginate(12)
            )
        );
    }

//     /**
//      * @param Request $request
//      * @return array
//      */
//     public function deals(Request $request)
//     {
// dd(array_filter(explode(',', $request->get('category'))));
// //         $input_text = strip_tags($_GET['input_text']);
// // $input_text = htmlspecialchars($input_text);
// // $input_text = mysql_escape_string($input_text);
//         return $this->successResponse(
//             DealsItemFormatter::formatPaginator(
//                 FilterDealsRepository::filter(
//                     array_filter(explode(',', $request->get('category'))),
//                     array_filter(explode(',', $request->get('country'))),
//                     array_filter(explode(',', $request->get('region'))),
//                     array_filter(explode(',', $request->get('city'))),
//                     $request->get('fast_deal'),
//                     $request->get('pay_type_cash'),
//                     $request->get('pay_type_noncash'),
//                     $request->get('finish')
//                 )->simplePaginate(12)
//             )
//         );
//     }

      /**
     * @param Request $request
     * @return array
     */
    public function filterDeals(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'status'            => 'sometimes|in:' . OrganizationDeal::DEAL_STATUS_MODERATION . ','. OrganizationDeal::DEAL_STATUS_APPROVE. ','. OrganizationDeal::DEAL_STATUS_ARCHIVE. ','. OrganizationDeal::DEAL_STATUS_BANNED,
            'type_deal'         => 'sometimes|in:' . OrganizationDeal::DEAL_TYPE_SELL . ','. OrganizationDeal::DEAL_TYPE_BUY,
            'subtype_deal'      => 'sometimes|in:' . OrganizationDeal::DEAL_SUBTYPE_USED . ','. OrganizationDeal::DEAL_SUBTYPE_NEW . ','. OrganizationDeal::DEAL_SUBTYPE_NA,
            'payment_status'    => 'sometimes|in:' . OrganizationDeal::DEAL_STATUS_PAYMENT_NOT_PAID . ','. OrganizationDeal::DEAL_STATUS_PAYMENT_FREE . ','. OrganizationDeal::DEAL_STATUS_PAYMENT_PAID,
            'region'            => 'sometimes|exists:regions,id',
            'city'              => 'sometimes|exists:cities,id',
            'category'          => 'sometimes|exists:categories,id',
            'date_created'      => 'sometimes|in:ASC,DESC,asc,desc',
            'date_deadline'     => 'sometimes|in:ASC,DESC,asc,desc',
            'date_published'    => 'sometimes|in:ASC,DESC,asc,desc',
            'with_photo'        => 'sometimes|in:0, 1',
            'finish'            => 'sometimes|in:0, 1',
			'deal_name'         => 'sometimes|max:190',
			//  'description' => 'sometimes|min:16|max:4000',
			//  'pay_type_cash' => 'sometimes|boolean',
			//  'pay_type_noncash' => 'sometimes|boolean',
			'budget_from' => 'sometimes|lte:budget_to|numeric', 
			'budget_to' => 'sometimes|numeric',
			//  'fast_deal' => 'sometimes|boolean',
			//  'favorite_only' => 'sometimes|boolean',
			//  'deadline_deal' => 'required|date|date_format:"Y-m-d"|after:deadline_service',  // todo удалить при чистке
			//  'deadline_deal' => 'required|date|date_format:"Y-m-d"', 
			//  'deadline_service' => 'required|date|date_format:"Y-m-d"', // todo удалить при чистке
			//  'question' => 'sometimes|required|max:1024',
			'categories' => ['sometimes', 'array', new CategoriesRule()],
			'cities' => ['sometimes', 'array', new CitiesRule()],
			//'regions' => ['sometimes', 'array', new RegionsRule()],
            'user_id'              => 'sometimes|exists:users,id',
			//  'cities.*' => ['required',  new CitiesRule()],
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->messages());
        }

        
        $categories         = (empty( $input['categories'])) ? false :  array_filter($input['categories']) ;
        $countries          = false; // (empty( $input['countries'])) ? false : array_filter($input['countries']) ; 
        $regions            = false; //(empty( $input['regions'])) ? false : array_filter($input['regions']) ;
        $cities             = (empty( $input['cities'])) ? false : array_filter($input['cities']) ;
        $fast_deal          = null; // (empty( $input['fast_deal'])) ? false : array_filter($input['fast_deal']);
        $pay_type_cash      = null; // (empty( $input['pay_type_cash'])) ? false : array_filter($input['pay_type_cash']);
        $pay_type_noncash   = null; // (empty( $input['pay_type_noncash'])) ? false : array_filter($input['pay_type_noncash']);
        $finish             = (empty( $input['finish'])) ? null : $input['finish'];
        $type_deal          = (empty( $input['type_deal'])) ? null : $input['type_deal'];
        
        $region             = $input['region'] ?? null;
        $category           = $input['category'] ?? null;
        $city               = $input['city'] ?? false;
        $datePublishedDeal  = (empty( $input['date_published'])) ? 'DESC' : $input['date_published'];
        $dateCreateDeal     = (empty( $input['date_created'])) ? 'DESC' : $input['date_created'];
        $dateDeadlineDeal   = (empty( $input['date_deadline'])) ? 'DESC' : $input['date_deadline'];
        $budgetFrom         = (empty( $input['budget_from'])) ? 0 : (double) $input['budget_from'];
        $budgetTo           = (empty( $input['budget_to'])) ? 99999999999 : (double) $input['budget_to'];
        $withPhoto          = (empty( $input['with_photo'])) ? false : $input['with_photo'];
        $inDealName         = (empty( $input['search'])) ? false : $input['search'];
        if($inDealName !== false){ // запишем лог текстовый запрос по названию сделки
            $user = \Illuminate\Support\Facades\Auth::guard('api')->user();
            $user_id = ($user) ?  $user->id : null;
            \App\Models\LogSearch::create(['user_id' => $user_id, 'search_text' => $inDealName]);
        }

        $subtype_deal       = (empty( $input['subtype_deal'])) ? null : $input['subtype_deal'];
        $status             = (empty( $input['status'])) ? null : $input['status'];
        $user_id            = (empty( $input['user_id'])) ? null : $input['user_id'];
        $paymentStatus      = (empty( $input['payment_status'])) ? null : $input['payment_status'];
//dd($region);
//dd($input);
//         $input_text = strip_tags($_GET['input_text']);
// $input_text = htmlspecialchars($input_text);
// $input_text = mysql_escape_string($input_text);

        $toFilter = FilterDealsRepository::filter(
            $categories,
            $countries, //array_filter(explode(',', $request->get('country'))),
            $regions, //array_filter(explode(',', $request->get('region'))),
            $cities,
            $fast_deal,
            $pay_type_cash ,
            $pay_type_noncash,
            $finish,
            $type_deal,
            $city,
            $dateCreateDeal,   
            $dateDeadlineDeal,
            $budgetFrom, 
            $budgetTo,      
            $withPhoto,        
            $inDealName,
            $subtype_deal,
            $status,
            $user_id,
            $paymentStatus,
            $datePublishedDeal,      
            $category,
            $region         
        )->simplePaginate($request->get('per_page'));
//dd( $toFilter->toArray());
        return $this->successResponse(
            DealsItemFormatter::formatPaginator(
                $toFilter
                // FilterDealsRepository::filter(
                //     array_filter(explode(',', $request->get('category'))),
                //     array_filter(explode(',', $request->get('country'))),
                //     array_filter(explode(',', $request->get('region'))),
                //     array_filter(explode(',', $request->get('city'))),
                //     $request->get('fast_deal'),
                //     $request->get('pay_type_cash'),
                //     $request->get('pay_type_noncash'),
                //     $request->get('finish')
                // )->simplePaginate(15)
            )
        );
    }

}
