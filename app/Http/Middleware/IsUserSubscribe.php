<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsUserSubscribe
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($this->authCheck()){
            $user = $this->authUser();
            $end_date = $user->subscriptionPackage?->end_date;
            if( $end_date < now()){
                $user->update(['is_subscribe' => false]);
                $user->subscriptionPackage()->update(['status' => 'expired']);
            }else{
                $user->update(['is_subscribe' => true]);
                $user->subscriptionPackage()->update(['status' => 'active']);
            }
        }
        return $next($request);
    }

    public function authCheck(){
        if(auth()?->check() || auth('sanctum')?->user()?->check()){
            return true;
        }
        return false;
    }

    public function authUser(){
        if(auth()->check()){
            return auth()->user();
        }
        return auth('sanctum')->user();
    }
}
