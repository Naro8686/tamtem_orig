<?php

namespace App\Http\Controllers\Client\Api\v1;


use App\Traits\ApiControllerTrait;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Org\OrganizationDeal;
use App\Models\Org\OrganizationDealMember;

use Auth;
use Throwable;
use Validator;


class ResponseNotificationsController extends Controller
{
	use ApiControllerTrait;

    public function __construct()
    {

    }


    /**
     * Кол-во не прочитанных откликов countunreaded
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function countunreaded(Request $request)
    {

		try{

			$user = Auth::guard('api')->user();

			// мои не прочитанные отклики
			$responsesUnreadedMyResponese = $user->organization->responsesActive()->get(); // мои не прочитанные отклики

			$unreadedResponse = [
				'unreaded_owner_response' => 0,
				'unreaded_owner_deal' => 0,
			];

			foreach($responsesUnreadedMyResponese as $response){
				if($response->is_readed_owner_response === 0) $unreadedResponse['unreaded_owner_response'] += 1;

            }
			
			// не прочитанные отклики по моим сделкам
			$deals = $user->deals()->where('status', '=', OrganizationDeal::DEAL_STATUS_APPROVE)->where('type_deal', '=', OrganizationDeal::DEAL_TYPE_BUY)->get();

			foreach($deals as $deal){
				$unreadedResponse['unreaded_owner_deal'] += $deal->members()->where('is_readed_owner_deal', '<>', 1)->count();
			}

			return $this->successResponse($unreadedResponse);
        }
        catch(Throwable $e){
            return $this->errorResponse($e->getMessage());
        }

    }

    /**
     * пометить отклик прочитанным markReaded
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function markReaded(Request $request)
    {
        
        try{

			$user = Auth::guard('api')->user();
			$input = $request->all();
			
			$validator = Validator::make( $input, [
				'id' => 'required|exists:organizations_deals_members,id',
				'command' => 'required|in:is_readed_owner_response,is_readed_owner_deal',
			]); 

			if ($validator->fails()) {
				return $this->errorResponse($validator->messages());
            }
            
            $id = (int) $input['id'];

            $row = null;

            switch ($input['command']) {

                case 'is_readed_owner_response': // владелец отклика

                    $row = $user->organization()->first()->responsesActive()->where('id', '=', $id )->first();

                    break;
                    
                case 'is_readed_owner_deal': // владелец заявки

                    $response = OrganizationDealMember::find($id);
                    $ownerDealId = $response->ownerDeal->id;
            
                    if($ownerDealId === $user->id){
                        $row = $response;
                    }

                    break;
            }

			if($row){
				$rowName = $input['command'];
				$row->$rowName = true; //прочитали
				$row->save();
				return $this->successResponse();
			}
        
            return $this->errorResponse();
           // return $this->errorResponse('Не могу найти отклик с id = ' . $input['id']);
        }
        catch(Throwable $e){
            return $this->errorResponse($e->getMessage());
        }

    }	
}
