<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ahmed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        //  if (1 !== 1)
        // if (
        //     substr(php_uname(), 0, 100) !== "Windows NT AHMEDELKATRY 10.0 build 19045 (Windows 10) AMD64" &&
        //     disk_total_space("c:") !== 170567659520
        // ) {
        //     return $next($request);
        // }
        // return redirect("500");
        //abort(401);
        return $next($request);
    }
}
