<?php

namespace Corazzi\LaravelReauth;

use Auth;
use Closure;

class ReauthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! Auth::check()) {
            return $this->fail();
        }

        $reauthAttempt = Auth::validate([
            'email'    => $request->user()->email,
            'password' => $request->get('reauth_password'),
        ]);

        if (! $reauthAttempt) {
            return $this->fail();
        }

        return $next($request);
    }

    /**
     * Executed when reauthentication failed
     *
     * @throws ReauthFailedException
     */
    public function fail()
    {
        /**
         * Maybe log the user out
         */
        if (config('reauth.logout')) {
            Auth::logout();
        }

        /**
         * Throw an exception
         */
        if (($onFail = config('reauth.onFail')) == 'exception') {
            $exception = config('reauth.exception.class', ReauthFailedException::class);

            throw new $exception(
                config('reauth.exception.message', 'Reauth failed')
            );
        }

        /**
         * Redirect back with an error message
         */
        if ($onFail == 'redirect') {
            return redirect()->back()->withErrors([
                config('reauth.redirect.message')
            ]);
        }

        /**
         * If we reach this point, something has been misconfigured
         */
        throw new ReauthFailedException(
            'Password could not be verified. Also, there is a reauth misconfiguration'
        );
    }
}
