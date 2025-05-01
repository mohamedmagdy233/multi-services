<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
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
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json([
                    'msg' => 'Token is Invalid',
                    'data' => null,
                    'status' => 401
                ]);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json([
                    'msg' => 'Token is Expired',
                    'data' => null,
                    'status' => 401
                ]);
            }else{
                return response()->json([
                    'msg' => 'Authorization Token not found',
                    'data' => null,
                    'status' => 401
                ]);
            }
        }
        return $next($request);
    }
}
