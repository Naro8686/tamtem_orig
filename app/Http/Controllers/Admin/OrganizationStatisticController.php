<?php
namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Client\ClientStoreRequest;
use App\Http\Requests\Admin\Client\ClientStoreUserRequest;
use App\Http\Requests\Admin\Client\ClientUpdateRequest;
use App\Http\Requests\Admin\Client\ClientUpdateUser;
use App\Models\LogAdmin;
use App\Models\Org\OrganizationStatistic;
use App\Models\Org\Organization;
use App\Models\Org\OrganizationDeal;
use App\Models\Org\OrganizationOffice;
use App\Models\Org\OrganizationStore;
use App\Models\ScoreDocs;
use App\Models\User;
use App\Notifications\SendScoreClient;
use App\Repositories\OrganizationRepository;
use App\Traits\ApiControllerTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Validator;
use Illuminate\Support\Facades\Hash;
use PDF;
use Mail;
use App\Services\FinanceOperation\FinanceOperationService;
use App\Models\FinanceOperation;
use App\Http\Controllers\Client\Api\v1\PaymentServicesController;
use App\Http\Controllers\Client\Api\v1\DialogsController;
use App\Models\Payment\Subscription;
use App\Models\Category;
use Illuminate\Notifications\Notifiable;


class OrganizationStatisticController extends Controller
{
    use ApiControllerTrait;

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
        
			$URI = config('constants.agents.agent-id-from-inn');	

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
				//dd($response);
        if(isset($response->error) or $response->result === false){
            return null;
        }

        return $response->data->agent_id;

		}
		public static function agentIdFromInn2($inn)
    {
			$URI = config('constants.agents.agent-id-from-innn');	
		
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
		
		 /**
     * Проверяем хомяк ли привел компанию
     *
     * @param  mixed $data
     *
     * @return void
     */
	
		public function checkagentCompany (Request $request) {
			$agentttt=self::agentIdFromInn2($request->params['inn']);
			if ($agentttt != null)	return $this->successResponse();
			else return $this->errorResponse();	
		}
		

	  /**
     * Подтверждение компании и начисление денежных средств агенту за регистрацию
     *
     * @param  mixed $data
     *
     * @return void
     */
	
		public function confirmCompanyToAgents (Request $request) {

		$summ='1000';
		$org_is_activve='1';
		
		$organization=Organization::where('org_inn', $request->params['inn'])->first();
		if ($organization->org_is_active === 0){
		
			$agentttt=self::agentIdFromInn2($request->params['inn']);
			if ($agentttt === null){

					Organization::where('org_inn', $request->params['inn'])->update(
							[
					'org_is_active' => '1'
						]
						);
					User::where('id',$request->params['id'])->update(
						[
							'hide_email' => '1'
						]
						);
					return $this->successResponse();
			}
			else {
					OrganizationStatistic::where('inn',$request->params['inn'])->update(
					['agent_id'=> $agentttt]);
					$organizationstatistic = OrganizationStatistic::where('inn', $request->params['inn'])->first();
					OrganizationStatistic::where('inn', $request->params['inn'])->update(
					[
					'response_ballance'=> $organizationstatistic->response_ballance+$summ
					]
					);
					Organization::where('org_inn', $request->params['inn'])->update(
					[
					'org_is_active' => '1'
					]
					);
					User::where('id',$request->params['id'])->update(
					[
					'hide_email' => '1'
					]
					);
					return $this->successResponse();
			}
		}
		else return $this->errorResponse();
	}
}

?>