<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Reauth failure handling
    |--------------------------------------------------------------------------
    |
    | By default, a failure in reauthing will throw a new ReauthFailedException.
    | You can choose how to handle this in app\Exceptions\Handler.
    |
    | You can also choose to redirect back to the previous page with an error message.
    |
    | Supported: "exception", "redirect"
    |
    */
    'onFail' => 'exception',

    /*
    |--------------------------------------------------------------------------
    | Reauth failed exception
    |--------------------------------------------------------------------------
    |
    | The default exception to be thrown is Corazzi\LaravelReauth\ReauthFailedException,
    | but you may also define your own exception here, as well has the error message
    | that is passed to it.
    |
    */
    'exception' => [
        'class' => \Corazzi\LaravelReauth\ReauthFailedException::class,
        'message' => 'Reauth failed'
    ],

    /*
    |--------------------------------------------------------------------------
    | Redirection
    |--------------------------------------------------------------------------
    |
    | If you choose to redirect the user back after the reauth failure,
    | you can customise the message that will be included in the error bag.
    |
    */
    'redirect' => [
        'message' => 'Sorry, we couldn\'t verify your password'
    ],

    /*
    |--------------------------------------------------------------------------
    | Logout on failure
    |--------------------------------------------------------------------------
    |
    | You can choose to log the user out if their reauth fails.
    | This is disabled by default.
    |
    */
    'logout' => false,
];