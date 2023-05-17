<?php

namespace App\Http\Controllers\Client\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Api\v1\Register\RegisterUserRequest;
use App\Repositories\OrganizationRepository;
use App\Traits\ApiControllerTrait;
use Auth;

class RegisterController extends Controller
{
    use ApiControllerTrait;

    // todo удалить при чистке
    // public function registerCustomer(RegisterCustomerRequest $request)
    // {
    //     if (OrganizationRepository::createOrganization($request))
    //         return $this->successResponse();

    //     return $this->errorResponse();
    // }

    // public function registerSupplier(RegisterSupplierRequest $request)
    // {
    //     if (OrganizationRepository::createOrganization($request))
    //         return $this->successResponse();

    //     return $this->errorResponse();
    // }


    public function registerUser(RegisterUserRequest $request)
    {
        $user = OrganizationRepository::createOrganization($request);

        if ($user){

            if(true === $request->has('to_register_user') and true === (bool)$request->to_register_user ){
                return $this->successResponse([
                    'api_token' => $user->api_token,
                ]);
            }
            else{
                return $this->successResponse();
            }
        }

        return $this->errorResponse();
    }

}
