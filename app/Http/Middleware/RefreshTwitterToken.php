<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RefreshTwitterToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(!$request->user() || !$request->user()->token){
            return $next($request);
        }
        if($request->user()->token->hasExpired()){
            return redirect('/refresh');
        }
        return $next($request);
        
        
    }
}
