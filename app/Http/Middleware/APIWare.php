<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
class APIWare
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
        $user = new User();
        if($request->input('token') != "" && $user->verifyToken($request->input('token')) > 0 ){
            return $next($request);
        }else {
            return response()->json([
                'error' => true,
                'isAuthenticated' => false,
            ]);
        }
    }
}
