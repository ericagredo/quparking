<?php namespace App\Http\Middleware;

use Closure;
/*use Illuminate\Contracts\Auth\Guard;*/
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class Authenticate {

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @return void
	 */
	/*public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}*/

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next, $guard = null)
	{
		/*if ($this->auth->guest())
		{
			if ($request->ajax())
			{
				return response('Unauthorized.', 401);
			}
			else
			{
				return redirect()->guest('auth/login');
			}
		}
		return $next($request);*/
                //echo 'hi';exit;
		if (Auth::guard($guard)->guest()) {
                    //print_r($request->session()->get('userData'));exit;
                    if($request->session()->get('userData') && !empty($request->session()->get('userData')->id) ){
                       //return redirect('admin');
                    }else{
                        return redirect('/admin/login');
                    } 
                    /*if ($request->ajax() || $request->wantsJson()) {
                        return response('Unauthorized.', 401);
                    } else {
                        //return redirect('login');
                    }*/
                }
                return $next($request);
                }

}