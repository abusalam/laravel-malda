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
        $browser="";
            if(strrpos(strtolower($_SERVER["HTTP_USER_AGENT"]),strtolower("MSIE")))
            {
            $browser="Internet Explorer";
            }
            else if(strrpos(strtolower($_SERVER["HTTP_USER_AGENT"]),strtolower("Presto")))
            {
            $browser="Opera";
            }
            else if(strrpos(strtolower($_SERVER["HTTP_USER_AGENT"]),strtolower("CHROME")))
            {
            $browser="Google Chrome";
            }
            else if(strrpos(strtolower($_SERVER["HTTP_USER_AGENT"]),strtolower("SAFARI")))
            {
            $browser="Safari";
            }
            else if(strrpos(strtolower($_SERVER["HTTP_USER_AGENT"]),strtolower("FIREFOX")))
            {
            $browser="FIREFOX";
            }
            else
            {
            $browser="OTHER";
            }
           
      
      $userDetails->sessionId = Session::getId();
      $userDetails->userIp = $request->ip();
      $userDetails->visitedPage = \Request::getRequestUri();
      $userDetails->description = json_encode($request->all());
      $userDetails->browser = $browser;
      $userDetails->save();
        return $next($request);
    }
}
