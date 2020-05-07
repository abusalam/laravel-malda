<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class SessionChecking
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
        if (session()->has('user_code') != 1) {
            return redirect('/login');
        } else {
            $now = time(); // checking the time now when home page starts
            if (session()->has('expire') == 1) {
                if ($now > session()->get('expire')) {
                    Session::flush();
                    if ($request->ajax()) {
                        $response = [
                            'logout_error'=> true,
                        ];

                        return response()->json($response, 200);
                    } else {
                        return redirect('./session');
                    }
                } else {
                    session(['expire' => $now + (15 * 60)]);
                }
            } else {
                session(['expire' => $now + (15 * 60)]);
            }
            if (session()->has('user_code') != 1) {
                //                Session::flush();
                if ($request->ajax()) {
                    $response = [
                        'logout_error'=> true,
                    ];

                    return response()->json($response, 200);
                } else {
                    return redirect('./session');
                }
            } else {
                return $next($request);
            }
        }
    }
}
