<?php

namespace App\Http\Middleware;

use Closure;

class MatchManager
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
        if (\Auth::check() && in_array(\Auth::user()->type, ['admin', 'matchmanager'])) {
            return $next($request);
        }

        return redirect('home');
    }
}
