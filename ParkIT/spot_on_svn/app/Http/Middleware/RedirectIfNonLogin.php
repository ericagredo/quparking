<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class RedirectIfNonLogin {

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
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next){

		/*if ($this->auth->check()){
			return new RedirectResponse(url('/home'));
		}
		return $next($request);*/
                //echo 'dfgfg';exit;
		$route = $request->route();
		$actions = $route->getAction();
		if(isset($this->auth->user()->id) && !empty($this->auth->user()->id)){
			return Redirect::route('home');
		}else{
			return $next($request);
		}
	}



}
