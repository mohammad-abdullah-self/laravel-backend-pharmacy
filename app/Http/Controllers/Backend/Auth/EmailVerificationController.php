<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Support\Facades\Auth;


class EmailVerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    // protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('auth:api')->only('resend');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }


    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response(['message' => 'Already verified'], 208);
        }

        $request->user()->sendEmailVerificationNotification();

        return response(['message' => 'Email Send'], 202);
    }

    public function verify(Request $request)
    {
        $user = User::findOrFail($request->route('id'));
        auth()->login($user);

        if (!hash_equals((string) $request->route('id'), (string) $request->user()->getKey())) {
            throw new AuthorizationException();
        }

        if (!hash_equals((string) $request->route('hash'), sha1($request->user()->getEmailForVerification()))) {
            throw new AuthorizationException();
        }

        if ($request->user()->hasVerifiedEmail()) {
            return response(['message' => 'Already verified'], 208);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        if ($response = $this->verified($request)) {
            return $response;
        }

        return redirect(env('EmailVerifiedRedirectURL'));
    }
}
