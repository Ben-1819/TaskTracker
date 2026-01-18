<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Try to parse the users JWT
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $e) {
            log::error('The token has expired');
            return response()->json(['error' => 'The token has expired'], 401);
        } catch (TokenInvalidException $e) {
            log::error('The token is invalid');
            return response()->json(['error' => 'The token is invalid'], 401);
        } catch (JWTException $e) {
            log::error('Token not found or malformed');
            return response()->json(['error' => 'Token not found or malformed'], 401);
        }
        return $next($request);
    }
}
