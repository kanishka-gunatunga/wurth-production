<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::check()){
            // if(Auth::user()->user_role == 1 && Auth::user()->status == "active"){
            if(Auth::user()->status == "active"){
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
