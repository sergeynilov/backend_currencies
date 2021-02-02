<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

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
            if (! $user = $this->auth->parseToken()->authenticate()) {
                return response()->json(['success' => false, 'error' => __('Invalid User.')]);
            }
            \Log::info( '---- JwtMiddleware$e $user->id ::' . print_r(  ($user->id ?? 'User not defined'), true  ) );
        } catch (TokenExpiredException $e) {
            try {
                $refreshed = $this->auth->refresh($this->auth->getToken());
                \Log::info( '-0 JwtMiddleware$e $refreshed ::' . print_r(  $refreshed, true  ) );
                $user = $this->auth->setToken($refreshed)->toUser();
                \Log::info( '-00 JwtMiddleware$e $user->id ::' . print_r(  ($user->id ?? 'User not defined'), true  ) );
                header('Authorization: Bearer ' . $refreshed);
            } catch (JWTException $e) {
                return response()->json(['success' => false, 'error' => __('Could not generate refresh token')]);
            }
        } catch (JWTException $e) {
            \Log::info( '-1 JwtMiddleware$e->getMessage() ::' . print_r(  $e->getMessage(), true  ) );
            return response()->json(['success' => false, 'error' => __('Invalid request')]);
        }
        return  $next($request);
    }
/* public function handle($request, Closure $next)
        {
            try {
                $user = JWTAuth::parseToken()->authenticate();
            } catch (Exception $e) {
                if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                    return response()->json(['status' => 'Token is Invalid']);
                }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                    return response()->json(['status' => 'Token is Expired']);
                }else{
                    return response()->json(['status' => 'Authorization Token not found']);
                }
            }
            return $next($request);
        } */
}
