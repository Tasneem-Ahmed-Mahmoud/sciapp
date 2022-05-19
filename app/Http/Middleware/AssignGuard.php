<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use App\Traits\GeneralTrait;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use JWTAuth;
class AssignGuard extends BaseMiddleware
{
 use GeneralTrait;
    public function handle($request, Closure $next, $guard = null)
    {
        if($guard != null){
            auth()->shouldUse($guard); //shoud you user guard / table
            $token = $request->header('auth-token');
            $request->headers->set('auth-token', (string) $token, true);
            $request->headers->set('Authorization', 'Bearer '.$token, true);
        
        try {
             //$user = $this->auth->authenticate($request);  //check authenticted user
              $user = JWTAuth::parseToken()->authenticate();
          } catch (TokenExpiredException $e) {
              return  $this -> returnError('401','Unauthenticated user');
          } catch (JWTException $e) {
              return  $this -> returnError('', 'token_invalid');
          }

        }
        return $next($request);
    }
}
