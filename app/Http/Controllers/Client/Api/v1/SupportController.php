<?php

namespace App\Http\Controllers\Client\Api\v1;

use App\Traits\ApiControllerTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Org\OrganizationDeal;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\SendMantisTicket;

use Validator;

class SupportController extends Controller
{
    use ApiControllerTrait;


    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();

        if($request->data['form_type']==0){
            $theme='Техническая поддержка: '.$request->data['theme'];
        }else{
            $theme='Пожелания: '.$request->data['theme'];
        }

        if($user){
            $user = User::find($user->id);
            $text='';
            $text.='<p>'.$request->data['text'].'</p>';
            $text.='<p> <b>Телефон: </b>'.$user->phone.'</p>';
            $text.='<p> <b>E-mail: </b>'.$user->email.'</p>';
            $user->notify(new SendMantisTicket($text, $theme));
        }else{

            $text='';
            $text.='<p>'.$request->data['text'].'</p>';
            $text.='<p> <b>Телефон: </b>'.$request->data['phone'].'</p>';
            $text.='<p>  <b>E-mail: </b>'.$request->data['email'].'</p>';

            $user = User::find(1);
            $user->notify(new SendMantisTicket($text, $theme));
        }

        return $this->successResponse();
    }

    /**
     * Послать письмо в саппорт с просьбой создать заявку
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function createDeal(Request $request)
    {
        
        try{
            
            $user = Auth::guard('api')->user();
 
            $input = $request->all();

            $validator = Validator::make($input, [
                'name' 	    => 'required|min:2|max:190', // название сделки
                'phone'     => 'required|min:6|max:11|regex:/^[\d]+$/i', // номер телефона
                'time_from' => 'required_with:time_to|nullable|regex:/^\w\w\:\w\w$/i', // время от
                'time_to' 	=> 'required_with:time_from|nullable|regex:/^\w\w\:\w\w$/i',  // время ДО
            ]);
            if ($validator->fails()) {
                return $this->errorResponse($validator->messages());
            }

            $messageToManagers = $title = '';

            // если юзер авторизаван
            if($user){
                
                $messageToManagers = "У пользователя c идентификатором: " . $user->unique_id . ", и номером телефона: +7" .  $input['phone'] . 
                        ", есть проблемма с созданием заявки с именем: " . $input['name'] . ". Почтовый адрес пользователя: " .  $user->email;
                
                $title = 'Пользователю с идентификатором: ' . $user->unique_id . ' нужна помощь в создании заявки';
            }
            else{

                $messageToManagers = "У пользователя c номером телефона: +7" .  $input['phone'] . 
                            ", есть проблемма с созданием заявки с именем: " . $input['name'] . ". " ;

                $title = 'Пользователю с нужна помощь в создании заявки';
            }

            if(isset($input['time_from']) and isset($input['time_to'])){
                $messageToManagers .= "  Ему удобно позвонить с " . $input[ 'time_from'] . " до " .  $input[ 'time_to'] ;
            }
           
            $subject = 'Просьба помочь с созданием заказа';

            \Mail::to(config('b2b.email.managers'))->send(new \App\Mail\SendToManagers($messageToManagers, $title, $subject));
            
            return $this->successResponse();
        }
        catch(\Throwable $e){
            return $this->errorResponse($e->getMessage());
        }

    }

    public function innFailed(Request $request)
    {
        
        try{
            $user = Auth::guard('api')->user();

            $input = $request->all();

            $validator = Validator::make($input, [
                'inn'       => 'required|digits:10',
            ]);
            if ($validator->fails()) {
                return $this->errorResponse($validator->messages());
            }

            $messageToManagers = "У пользователя c идентификатором:" . $user->unique_id . ", и номером телефона: +7" . $user->phone . 
                    ", есть проблемма с созданием организациии по номеру ИНН: ". $input['inn'];

            \Mail::to(config('b2b.email.managers'))->send(new \App\Mail\SendToManagersInnFailed( (string) $messageToManagers));
            
            return $this->successResponse();
        }
        catch(\Throwable $e){
            return $this->errorResponse($e->getMessage());
        }

    }

    public function dealFailed(Request $request)
    {
        
        try{
            $user = Auth::guard('api')->user();

            $input = $request->all();

            $validator = Validator::make($input, [
                'deal_id' 	    => 'required|exists:organizations_deals,id', // номер сделки
                'comment' 	    => 'required|min:2|max:1024', // комментарий, почему не состоялась сделка
                'phone'         => 'sometimes|nullable|min:6|max:11|regex:/^[\d]+$/i', // номер телефона
            ]);

            if ($validator->fails()) {
                return $this->errorResponse($validator->messages());
            }
 
            $phone = $request->phone ??  $user->phone ;

            $messageToManagers = "Сделка по заказу № " . $request->deal_id  . " не состоялась. Пользователь c номером телефона: +7" .  $phone . 
            " оставил комментарий: " . $request->comment ;

            $idsDealsBuyContacts = $user->idsDealsBuyContacts(); // id объявлений с проплаченными контактами
            $commission = count($idsDealsBuyContacts) > 0 ? OrganizationDeal::find($request->deal_id)->commission : 200;

            $title = 'Сделка у пользователя с идентификатором: ' . $user->unique_id . ' не состоялась. Комиссия к возврату: ' . $commission . " руб.";
            $subject = 'Сделка не состоялась';

            \Mail::to(config('b2b.email.managers'))->send(new \App\Mail\SendToManagers($messageToManagers, $title, $subject));
            
            return $this->successResponse();
        }
        catch(\Throwable $e){
            return $this->errorResponse($e->getMessage());
        }

    }

}
