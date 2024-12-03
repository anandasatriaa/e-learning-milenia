<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdmin
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
        if (auth()->user()->lvl === 1) {
            return $next($request);
        }
        return redirect()->route('pages.dashboard')->with([
            'error' => [
                'title' => 'Unauthorized',
                'message' => 'Tidak dapat mengakses halaman ini!'
            ]
        ]);
    }
}
