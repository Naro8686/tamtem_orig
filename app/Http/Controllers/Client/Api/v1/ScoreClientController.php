<?php

namespace App\Http\Controllers\Client\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Org\OrganizationDeal;
use App\Traits\ApiControllerTrait;
use Illuminate\Support\Facades\Auth;
use App\Services\FinanceOperation\FinanceOperationService;
use App\Models\FinanceOperation;

class ScoreClientController extends Controller
{
    use ApiControllerTrait;

    /**
    * @var FinanceOperationService
    */
    private $financeOperationService;

    /**
    * @param FinanceOperationService $financeOperationService
    */
    public function __construct(FinanceOperationService $financeOperationService)
    {
        $this->financeOperationService = $financeOperationService;
    }

    public function generateScoreClient (Request $request) {

        if($request->params['summ']==null){
                return $this->errorResponse('Введите сумму платежа');
        }else{
            $unique_id = $request->params['unique_id'];
            $summ = number_format($request->params['summ'], 2, '.', '');
            $user = Auth::guard('api')->user();
            $modelOrgDeal=new OrganizationDeal();
            $fileName=$modelOrgDeal->score($unique_id, $summ, $user);

            // создадим запись в финансовых операциях
            $data = [
                'payment_id' 		=> substr($fileName, 0 , strripos($fileName,'.')),
                'amount'            => (int)$summ,
                'payment_system'    => FinanceOperation::PAYMENT_SYSTEM_SCORE,
                'status'    		=> FinanceOperation::PAYMENT_STATUS_WAITING,
                'text'    			=> "Попытка оплаты выпиской счета",
            ];
            $financeOperation = $this->financeOperationService->create($user, $data);

            if(!($financeOperation instanceof FinanceOperation)){
                return $this->errorResponse($financeOperation);
            }

            return response()->json(['result' => true, 'link' => '/' . $fileName]);
        }

    }
}
