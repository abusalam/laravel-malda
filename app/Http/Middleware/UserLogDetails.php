<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App\tbl_user_log_details;
class UserLogDetails
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
      
      
      $userDetails = new tbl_user_log_details();
      if(session()->has('user_code')){
        $userDetails->userCode = session()->get('user_code');
      }else{
        $userDetails->userCode = '0' ;
      }
      
      $userDetails->sessionId = Session::getId();
      $userDetails->userIp = $request->ip();
      $userDetails->visitedPage = \Request::getRequestUri();
      $userDetails->description = json_encode($request->all());
      $userDetails->save();
        return $next($request);
    }
}
