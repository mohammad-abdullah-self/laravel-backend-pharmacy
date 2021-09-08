<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    protected function sendResetLinkResponse(Request $request, $response)
    {
        return response(['meassage' => trans($response)], 200);
    }


    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return response(['errors' => ['email' => trans($response)]], 422);
    }
}
