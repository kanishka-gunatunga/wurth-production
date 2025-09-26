<?php

namespace Modules\Finance\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinanceAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::check()){
            if(Auth::user()->user_role == 7 && Auth::user()->status == "active"){
                return $next($request);
            } else{ 
                $request->session()->flush();
                return redirect('/')->with('fail', 'You have to login first');
            }
        } else{
            $request->session()->flush();
            return redirect('/')->with('fail', 'You have to login first');
        }
    }
}
