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
        if (! Auth::check() || ! $this->attempt($request)) {
            return $this->fail();
        }

        return $next($request);
    }

    /**
     * Attempt to reauthenticate the user
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return bool
     */
    protected function attempt($request)
    {
        return Auth::validate([
            'email'    => $request->user()->email,
            'password' => $request->get('reauth_password'),
        ]);
    }

    /**
     * Executed when reauthentication failed
     *
     * @throws ReauthFailedException
     */
    public function fail()
    {
        // Maybe log the user out
        if (config('reauth.logout')) {
            Auth::logout();
        }

        // Throw an exception
        if (($onFail = config('reauth.onFail')) == 'exception') {

            // Determine which exception to use – either a custom one or the default ReauthFailedException
            $exception = config('reauth.exception.class', ReauthFailedException::class);

            // Throw the exception, passing a customisable exception message
            throw new $exception(
                config('reauth.exception.message', 'Reauth failed')
            );
        }

        // Redirect back with an error message
        if ($onFail == 'redirect') {
            return redirect()->back()->withErrors([
                config('reauth.redirect.message'),
            ]);
        }

        // If we reach this point, something has been misconfigured
        throw new ReauthFailedException(
            'Password could not be verified. Also, there is a reauth misconfiguration'
        );
    }
}
