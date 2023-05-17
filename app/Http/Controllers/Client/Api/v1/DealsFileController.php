<?php

namespace App\Http\Controllers\Client\Api\v1;

use App\Http\Resources\DealsFile\DealsFileResource;
use App\Traits\ApiControllerTrait;

use App\Http\Requests\Client\Api\v1\DealsFile\DealsFileStoreRequest;
use App\Services\DealsFile\DealsFileService;
use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DealsFile;
use Auth;

class DealsFileController extends Controller
{
    
    use ApiControllerTrait;

    /**
    * @var DealsFileService
    */
    private $dealsFileService;
    /**
    * @var User
    */
    private $authUser;

    /**
    * @param DealsFileService $dealsFileService
    */
    public function __construct(DealsFileService $dealsFileService)
    {
        $this->dealsFileService = $dealsFileService;
        $this->authUser = Auth::guard('api')->user();
    }

    /**
    * @param Request $request
    * @return DealsFileCollection
    */
    public function index(Request $request)
    { 
        
        // если юзер не админ, то покажем только его файлы
        if(! $this->authUser->isAdmin()){
            $request->merge([
                'user_id' => $this->authUser->id,
            ]);
        }
        
        $dealsFile = $this->dealsFileService->index($request);
    
        return $this->successResponse($dealsFile);
    }
    
    /**
    * @param int $id
    * @return DealsFileResource
    * @throws Exception
    */
    public function get(int $id)
    {
        
        $dealsFile = $this->dealsFileService->get($id);

        // если юзер не админ, то покажем только его файлы
        if(! $this->authUser->isAdmin() and $this->authUser->id !== $dealsFile->user_id){
            return $this->errorResponse("Запись {$id} не найдена или не доступна для вас");
        }

        if($dealsFile instanceof DealsFile){
            return $this->successResponse(new DealsFileResource($dealsFile));
        }
        else{
            return $this->errorResponse($dealsFile);
        }
    }

    /**
    * @param DealsFileStoreRequest $request
    * @return DealsFileResource
    * @throws Exception
    */
    public function create(DealsFileStoreRequest $request)
    { 

        $data = $request->all();

        // если сам юзер загружает файл или админ за него
        if($this->authUser->id === (int)$data['user_id'] or $this->authUser->isAdmin()){
            $dealsFile = $this->dealsFileService->create($data);

            if($dealsFile instanceof DealsFile){
                return $this->successResponse(new DealsFileResource($dealsFile));
            }
            else{
                return $this->errorResponse($dealsFile);
            }
        }

        return $this->errorResponse("Только сам пользователь может загружать файл для себя или администратор");
    }


    /**
    * @param int $id
    * @return JsonResponse
    * @throws Exception
    */
    public function delete(int $id)
    {
        
        $dealsFile = $this->dealsFileService->get($id);

        // если юзер не админ, то покажем только его файлы
        if(! $this->authUser->isAdmin() and $this->authUser->id !== $dealsFile->user_id){
            return $this->errorResponse("Запись {$id} не найдена или не доступна для вас");
        }       
        
        $dealsFile =  $this->dealsFileService->delete($id);

        if($dealsFile instanceof DealsFile){
            return $this->successResponse(['id' => $dealsFile->id]);
        }
        else{
            return $this->errorResponse($dealsFile);
        }
    }
}
