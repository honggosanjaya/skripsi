<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsSalesman
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
        if( $request->session()->get('role') == 'salesman'){
            return $next($request);
        }
        elseif( $request->session()->get('role') == null){
            Auth::guard('web')->logout();
 
            $request->session()->invalidate();
        
            $request->session()->regenerateToken();

            return redirect('/login');
        }
        abort(403);
    }
}
