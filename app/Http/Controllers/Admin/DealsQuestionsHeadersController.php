<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\DealsQuestionsHeaders\DealsQuestionsHeadersResource;
use App\Traits\ApiControllerTrait;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\DealsQuestionsHeaders\DealsQuestionsHeadersStoreRequest;
use App\Http\Requests\Admin\DealsQuestionsHeaders\DealsQuestionsHeadersUpdateRequest;

use App\Services\DealsQuestionsHeader\DealsQuestionsHeaderService;
use Exception;

use App\Models\User;
use App\Models\DealQuestionHeader;

class DealsQuestionsHeadersController extends Controller
{
	use ApiControllerTrait;

   /**
    * @var DealsQuestionsHeaderService
    */
    private $dealsQuestionsHeaderService;

	/**
    * @param DealsQuestionsHeaderService $dealsQuestionsHeaderService
    */
    public function __construct(DealsQuestionsHeaderService $dealsQuestionsHeaderService)
    {
        $this->dealsQuestionsHeaderService= $dealsQuestionsHeaderService;
	}
	
    /**
    * @param Request $request
    * @return DealsProcentCollection
    */
    public function index(Request $request)
    { 
        $dealsQuestionsHeaderService = $this->dealsQuestionsHeaderService->index($request);
    
        return $this->successResponse($dealsQuestionsHeaderService);
    }
    
    /**
    * @param int $id
    * @return DealsQuestionsHeadersResource
    * @throws Exception
    */
    public function get(int $id)
    {
        $dealsQuestionsHeaderService = $this->dealsQuestionsHeaderService->get($id);

        if($dealsQuestionsHeaderService instanceof DealQuestionHeader){
            return $this->successResponse(new DealsQuestionsHeadersResource($dealsQuestionsHeaderService));
        }
        else{
            return $this->errorResponse($dealsQuestionsHeaderService);
        }
    }

    /**
    * @param DealsQuestionsHeadersRequest $request
    * @return DealsQuestionsHeadersResource
    * @throws Exception
    */
    public function create(DealsQuestionsHeadersStoreRequest $request)
    { 
        /** @var User $user */
       //$user = Auth::guard('api')->user();

        $dealsQuestionsHeaderService = $this->dealsQuestionsHeaderService->create($request->all());

        if($dealsQuestionsHeaderService instanceof DealQuestionHeader){
            return $this->successResponse(new DealsQuestionsHeadersResource($dealsQuestionsHeaderService));
        }
        else{
            return $this->errorResponse($dealsQuestionsHeaderService);
        }
    }


    /**
    * @param int $id
    * @param DealsProcentUpdateRequest $request
    * @return DealsQuestionsHeadersResource
    * @throws Exception
    */
    public function update(int $id, DealsQuestionsHeadersUpdateRequest $request)
    {
        $dealsQuestionsHeaderService = $this->dealsQuestionsHeaderService->update($id, $request->all());

        if($dealsQuestionsHeaderService instanceof DealQuestionHeader){
            return $this->successResponse(new DealsQuestionsHeadersResource($dealsQuestionsHeaderService));
        }
        else{
            return $this->errorResponse($dealsQuestionsHeaderService);
        }
    }

    /**
    * @param int $id
    * @return JsonResponse
    * @throws Exception
    */
    public function delete(int $id)
    {
        $dealsQuestionsHeaderService =  $this->dealsQuestionsHeaderService->delete($id);

        if($dealsQuestionsHeaderService instanceof DealQuestionHeader){
            return $this->successResponse(['id' => $dealsQuestionsHeaderService->id]);
        }
        else{
            return $this->errorResponse($dealsQuestionsHeaderService);
        }
    }
}

