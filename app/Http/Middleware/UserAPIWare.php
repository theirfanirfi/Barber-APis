<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class UserAPIWare
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
        $token = $request->input('token');
        if(!empty($token)){
            $checkUser = $user->verifyToken($token);
            if($checkUser->count() > 0 && $checkUser->first()->role == 0){
                return $next($request);
            }else {
                return response()->json([
                    'isError' => true,
                    'isAuthenticated' => false,
                    'unAuthorized' => true,
                    'message' => 'Invalid credentials, or you are not authorized to perform this action'
                ]);
            }
        }else {
            return response()->json([
                'isError' => true,
                'isAuthenticated' => false,
                'message' => 'Invalid credentials'
            ]);
        }
    }
}
