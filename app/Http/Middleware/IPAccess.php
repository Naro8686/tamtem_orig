<?php

namespace App\Http\Middleware;

use Closure;

class IPAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    { 
        try{
            if(!in_array($request->ip(),  config("constants.ip_white_list"))){
                return response('Access denied.' . ' Your IP = ' . $request->ip(), 403);
            }
            return $next($request);
        }
        catch(Throwable $e){
            return redirect('/');
           // throw new \App\Exceptions\NotFoundException($e->getMessage() . " in " . __CLASS__ . "." . __FUNCTION__);
        }
    }
}
