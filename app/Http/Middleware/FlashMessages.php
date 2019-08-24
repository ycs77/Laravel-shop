<?php

namespace App\Http\Middleware;

use Closure;

class FlashMessages
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
        if ($request->session()->get('verified')) {
            flash(__('Successful verification'))->success()->important();
        }

        return $next($request);
    }
}
