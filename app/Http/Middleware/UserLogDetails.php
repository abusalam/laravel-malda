<?php

namespace App\Http\Middleware;

use App\tbl_user;
use App\tbl_user_log_details;
use Closure;
use Illuminate\Support\Facades\Route;
use Session;

class UserLogDetails
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (isset($request->username)) {
            $mob = $request->username;
        }
        if (isset($request->mobile_no)) {
            $mob = $request->mobile_no;
        }
        if (isset($request->mob)) {
            $mob = $request->mob;
        }

        $currentPath = Route::getFacadeRoot()->current()->uri();
        $userDetails = new tbl_user_log_details();
        if (session()->has('user_code')) {
            $userDetails->userCode = session()->get('user_code');
        } else {
            $userDetails->userCode = '0';
            if ($currentPath == '/') {
                $userDetails->visitor_count = 1;
            }
        }

        if (isset($mob)) {
            $result = tbl_user::where('mobile_no', $mob)->count();
            if ($result > 0) {
                $browser = $_SERVER['HTTP_USER_AGENT'];
                $userDetails->sessionId = Session::getId();
                $userDetails->userIp = $request->ip();
                $userDetails->visitedPage = \Request::getRequestUri();
                $userDetails->description = json_encode($request->all());
                $userDetails->browser = $browser;
                $userDetails->save();
            }
        } else {
            $browser = $_SERVER['HTTP_USER_AGENT'];
            $userDetails->sessionId = Session::getId();
            $userDetails->userIp = $request->ip();
            $userDetails->visitedPage = \Request::getRequestUri();
            $userDetails->description = json_encode($request->all());
            $userDetails->browser = $browser;
            $userDetails->save();
        }

        return $next($request);
    }
}
