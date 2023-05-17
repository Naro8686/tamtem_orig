<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\DealsProcent\DealsProcentResource;
use App\Traits\ApiControllerTrait;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Client\Api\v1\DealsProcent\DealsProcentStoreRequest;

use App\Services\DealsProcent\DealsProcentService;
use Exception;

use App\Models\User;
use App\Models\DealsProcent;

class DealsProcentsController extends Controller
{
	use ApiControllerTrait;

   /**
    * @var DealsProcentService
    */
    private $dealsProcentService;

	/**
    * @param DealsProcentService $dealsProcentService
    */
    public function __construct(DealsProcentService $dealsProcentService)
    {
        $this->dealsProcentService = $dealsProcentService;
	}
	
    /**
    * @param Request $request
    * @return DealsProcentCollection
    */
    public function index(Request $request)
    { 
        $dealsProcentService = $this->dealsProcentService->index($request);
    
        return $this->successResponse($dealsProcentService);
    }
    
    /**
    * @param int $id
    * @return DealsProcentResource
    * @throws Exception
    */
    public function get(int $id)
    {
        $dealsProcentService = $this->dealsProcentService->get($id);

        if($dealsProcentService instanceof DealsProcent){
            return $this->successResponse(new DealsProcentResource($dealsProcentService));
        }
        else{
            return $this->errorResponse($dealsProcentService);
        }
    }

    /**
    * @param DealsProcentStoreRequest $request
    * @return DealsProcentResource
    * @throws Exception
    */
    public function create(DealsProcentStoreRequest $request)
    { 
        /** @var User $user */
       //$user = Auth::guard('api')->user();

        $dealsProcentService = $this->dealsProcentService->create($request->all());

        if($dealsProcentService instanceof DealsProcent){
            return $this->successResponse(new DealsProcentResource($dealsProcentService));
        }
        else{
            return $this->errorResponse($dealsProcentService);
        }
    }


    /**
    * @param int $id
    * @param DealsProcentUpdateRequest $request
    * @return DealsProcentResource
    * @throws Exception
    */
    public function update(int $id, DealsProcentStoreRequest $request)
    {
        $dealsProcentService = $this->dealsProcentService->update($id, $request->all());

        if($dealsProcentService instanceof DealsProcent){
            return $this->successResponse(new DealsProcentResource($dealsProcentService));
        }
        else{
            return $this->errorResponse($dealsProcentService);
        }
    }

    /**
    * @param int $id
    * @return JsonResponse
    * @throws Exception
    */
    public function delete(int $id)
    {
        $dealsProcentService =  $this->dealsProcentService->delete($id);

        if($dealsProcentService instanceof DealsProcent){
            return $this->successResponse(['id' => $dealsProcentService->id]);
        }
        else{
            return $this->errorResponse($dealsProcentService);
        }
    }
}
