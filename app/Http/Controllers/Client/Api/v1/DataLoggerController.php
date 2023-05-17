<?php

namespace App\Http\Controllers\Client\Api\v1;


use Illuminate\Http\Request;
use App\Classes\DataLogger\DataLogger;
use App\Traits\ApiControllerTrait;
use App\Http\Controllers\Controller;
use Auth;
use Validator;

class DataLoggerController extends Controller
{
    use ApiControllerTrait;

    public function store(Request $request)
    {
        try{

            $input = $request->all();

            $validator = Validator::make($input, [
                'url'       => 'sometimes|nullable|max:1024',
                'method'    => 'sometimes|nullable|max:10',
                'status'    => 'sometimes|nullable|max:3',
                'data'      => 'sometimes|nullable|max:1024',
                'response'  => 'sometimes|nullable|max:5000',
            ]);
            if ($validator->fails()) {
                return $this->errorResponse();
            }

            $url      = $input['url'] ?? 'n/a';
            $method   = $input['method'] ?? 'n/a'; 
            $status   = $input['status'] ?? 'n/a';   
            $data     = $input['data'] ?? 'n/a';     
            $response = $input['response'] ?? 'n/a'; 
            $user = Auth::guard('api')->user();

            DataLogger::create($url, $status, $method, $data, $response, $user->id);
        }
        catch(\Exception $e){
             //return $this->errorResponse($e->getMessage());
        }
     
    }
}

