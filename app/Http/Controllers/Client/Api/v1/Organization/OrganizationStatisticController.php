<?php

namespace App\Http\Controllers\Client\Api\v1\Organization;

use App\Traits\ApiControllerTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \App\Models\Org\Organization;
use App\Models\Org\OrganizationDeal;
use \App\Models\Org\OrganizationStatistic;
use App\Notifications\SendSlackReferal;
use Validator;
use Auth;
use Carbon\Carbon;
use Cookie;

class OrganizationStatisticController extends Controller
{
    use ApiControllerTrait;


    /**
     * Переход на этот сайт по реферальной ссылке и передача параметров в гланй блейд
     *
     * @param  mixed $data
     *
     * @return void
     */
    public function referalLinkEnter($data)
    {
        try{
            $data = base64_decode($data);
//dd($data);
            $parts = parse_url($data); 
            parse_str($parts['path'], $query);
            // dd($query);
            $validator = Validator::make($query, [
                'inn' => 'required|unique:organizations,org_inn',
                'name' => 'sometimes|string|min:1|max:190',
                'agent_id' => 'required|numeric',
                'bid' => 'sometimes|exists:organizations_deals,id',
                'date_generate' => 'required|date|date_format:"Y-m-d H:i:s"|before:' . Carbon::now()->addHours(72),
            ]);
            if ($validator->fails()) {
                return redirect('/');
               // return $this->errorResponse($validator->messages());
            }

            // на столько минут выставлять жизнь куки
            $date_expire_ref = Carbon::now()->diffInMinutes(Carbon::parse($query['date_generate'])->addHours(72), false);
                Cookie::queue( 'tamtem-ref-inn', $query['inn'], $date_expire_ref, null, null, false, false);
                Cookie::queue( 'tamtem-ref-org-name', $query['name'], $date_expire_ref, null, null, false, false);
                Cookie::queue( 'tamtem-ref-agent-id', $query['agent_id'], $date_expire_ref, null, null, false, false);
                if (isset( $query['bid'])) {
                     // проверить ,чтобы заказ не был завершен
                     if($deal = OrganizationDeal::where('id','=', $query['bid'])->where('status', '=', OrganizationDeal::DEAL_STATUS_APPROVE)->where('finish', '<>', 1)->first()){
                        Cookie::queue( 'tamtem-ref-bid-id', $query['bid'], $date_expire_ref, null, null, false, false);
												$deal->notify(new SendSlackReferal); 
												return redirect("/bids/" . $query['bid']);
                     }
                }
            return redirect('/?destination=regorg');
//            dd($date_expire_ref);

            //return view('client.layouts.spa' , ['ref' => $query]);
        }
        catch(Throwable $e){
            return redirect('/');
           // throw new \App\Exceptions\NotFoundException($e->getMessage() . " in " . __CLASS__ . "." . __FUNCTION__);
        }
    }

    /**
     * Получить статистику компаний по id агентов
     *
     * @param  mixed $data
     *
     * @return void
     */
    public function getStatistic(Request $request)
    {
        try{

            $validator = Validator::make($request->all(), [
                'agents' => 'required|regex:/(\d+\,*)+/u',
                'prev_request_date' => 'required|date|date_format:"Y-m-d H:i:s"',
            ]);
            if ($validator->fails()) {
                return $this->errorResponse($validator->messages());
            }
            
            $agents = $request->agents;
            $agents = explode(",",  $request->agents);
            $retData = [];
            // return response( $agents);
            // return response($request->prev_request_date);
            if($statistics = OrganizationStatistic::whereIn('agent_id', $agents)->where('updated_at', '>=', $request->prev_request_date)->get()){
                // dd( $statistics->toArray());
            
                //перебрать каждую организацию и собрать данные по ней
                foreach ($statistics as $org_statistic) {
                 //   dd( $agents);
                    $organization = $org_statistic->organization()->with('categories')->first();
                  //   dd($organization->toArray());
                  //  dd( $organization);
                    // если организация в работе
                    if($organization->org_status === Organization::ORG_STATUS_APPROVE){
                        // если юзер создал организацию и юзеру разрешена работа на сайте
                        if($org_statistic->organization->owner->is_org_created === 1 and 
                            ($org_statistic->organization->owner->status === \App\Models\User::USER_STATUS_APPROVE or
                             $org_statistic->organization->owner->status === \App\Models\User::USER_STATUS_REGISTRED)){
                           //  dd('тут');//   $retData[$org_statistic->agent_id] = 
                            $categories = $organization->categories->pluck('name')->implode(',');
                          //  if($organization->id == 51) dd( $categories);
                            $name = $organization->org_name;

                            $retData[$org_statistic->agent_id][$org_statistic->inn]['name'] =  $name ;
                            $retData[$org_statistic->agent_id][$org_statistic->inn]['category_name'] =  $categories ;
                            $retData[$org_statistic->agent_id][$org_statistic->inn]['points_set_winner'] = $org_statistic->count_set_winners ;
                            $retData[$org_statistic->agent_id][$org_statistic->inn]['points_response']   = $org_statistic->count_responses ;
                            $retData[$org_statistic->agent_id][$org_statistic->inn]['response_ballance'] = $org_statistic->response_ballance ;
                        }
                    }
                }

                return $this->successResponse($retData);
            }
            else{
                return $this->errorResponse();
            }
         
            return $this->successResponse(json_encode($retData));
        }
        catch(Throwable $e){
            return $this->errorResponse();
        }
    }

    /**
     * Создаем запись в статистике с привязкой к агенту
     * array $data [  'agent_id' => int, 'inn' => int]
     *
     * @param  mixed $organization
     * @param  mixed $data
     *
     * @return void
     */
    public function storeStatisticToOrganization(Organization $organization, Array $data)
    {
        try{

            if(!$organization->statistic){
                if((int) $data['inn'] === (int)$organization->org_inn){
                    $statistic = $organization->statistic()->create($data);
                    return $statistic;
                }
                else{
                    return 'Не совпадают ИНН созданной организации и пришедшего ИНН от агента';
                }
            }
            else{
                return 'Ранее уже создана привязка к агенту';
            }
        }
        catch(Throwable $e){
            return $this->errorResponse($e->getMessage());
        }
    }

     /**
     * Доступен ли ИНН для регистрации
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function innIsAvailable(Request $request, $isLocalRequest = false)
    {
        
        $validator = Validator::make($request->all(), [
            'inn' => 'required|min:10|max:12',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->messages());
        }

        $organization = Organization::where('org_inn', '=', $request->inn)->first();

        $returnVal = false;

        if($organization){
            $returnVal = ($isLocalRequest) ? false : $this->errorResponse();
        }
        else{
            $returnVal = ($isLocalRequest) ? true : $this->successResponse();
        }

        return $returnVal;
    }


    /**
     *  Взять id агента с сервера агентов, кто регал реферальную ссылку последним, на компанию с указанным ИНН
     *
     * @param  mixed $inn
     * @param  mixed $isLocalRequest
     *
     * @return void
     */
    public static function agentIdFromInn($inn)
    {
				
			/* alexey_fedotov - специально добавил в uri в слово constantss букву s - для добавления компании в организатион статистик */
			$URI = config('constantss.agents.agent-id-from-inn');	
			//$URI = config('constants.agents.agent-id-from-inn');

        $params = array (
            'inn' => $inn,
        );

        ////////////////////////////////////////////////////////

        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $URI);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        // указываем, что у нас POST запрос
        curl_setopt($ch, CURLOPT_POST, 1);
        // добавляем переменные
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
 //dd($response);

        ///////////////////////////////////////////////////
     
        // если какая-то ошибка в механизме запросов
        if ($info['http_code'] !== 200 or $response === FALSE or strpos($response, 'access denied') !== false  ) {    
            return null;
        }

        $response = \json_decode($response); 

        if(isset($response->error) or $response->result === false){
            return null;
        }

        return $response->data->agent_id;

    }
}
