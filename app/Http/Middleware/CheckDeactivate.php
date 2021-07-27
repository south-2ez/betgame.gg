<?php

namespace App\Http\Middleware;

use Closure;

class CheckDeactivate
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
        if(auth()->user()) {
            if(auth()->user()->status == 'deactivated') {
                $user_id = auth()->user()->id;

                auth()->logout();

                session([
                    'message' => 'Your account was previously deactivated. Click <a data-uid="'.$user_id.'" class="alert-link reactivate-account" href="#">here</a> to Activate and log in again.'
                ]);

                return redirect('/login');
            }
        }

        return $next($request);
    }
}
