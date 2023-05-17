<?php

namespace App\Http\Controllers\Client\Api\v1;

use App\Traits\ApiControllerTrait;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Validator;

class UserNoticeSubscribeController extends Controller
{
    
    use ApiControllerTrait;

    /**
     * Подписка и отписка на оповещения по почте 
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function subscribeNoticeFromLK(Request $request)
    {
       
        $user = Auth::guard('api')->user();

        $validated = Validator::make($request->all(), [
            'notice_allowed'     => 'required|boolean',
        ]);

        if ($validated->fails()) {
            return $this->errorResponse($validated->messages());
        }

        // подписка на оповещения
        if(true === (boolean) $request->notice_allowed){
            $tagsIds = \App\Models\Org\Channel::select('id')->pluck('id')->toArray();
            $user->company->channels()->syncWithoutDetaching($tagsIds);
        }
        else{ // отписаться
            $user->company->channels()->detach();
        }

        return $this->successResponse();
    }

    /**
     * Подписка и отписка на оповещения по почте 
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function unsubscribeNoticeFromMail(Request $request)
    {

        $validated = Validator::make($request->all(), [
            'mail'     => 'required|exists:users,email',
            'uid'      => 'required|exists:users,unique_id',
        ]);

        if ($validated->fails()) {
            return redirect('/?unsubscribed=false');
            //return $this->errorResponse($validated->messages());
        }

        // отписка от получения писем по новым заказам
        if($user = User::where('unique_id', '=' , $request->uid)->where('email', '=' , $request->mail)->first()){
            $user->company->channels()->detach();
            return redirect('/?unsubscribed=true');
        }

        return redirect('/?unsubscribed=false');
    }
}
