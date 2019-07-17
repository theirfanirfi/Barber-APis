<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use URL;
class AuthWare
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
        if(Auth::check() && Auth::user()->role == 0){
        return $next($request);
        }else {
            Session()->put('redirect_url',URL::current());
            return redirect('/login');
        }
    }
}
