<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        /*if (Auth::guard($guard)->check()) {
            return redirect('/admin');
        }

        return $next($request);*/
        $route = $request->route();
        $actions = $route->getAction();

        if($request->session()->get('userData') && !empty($request->session()->get('userData')->id) ){
            if($actions['middleware'] == "web"){
                return redirect('admin');
            }
        }
    }
}
