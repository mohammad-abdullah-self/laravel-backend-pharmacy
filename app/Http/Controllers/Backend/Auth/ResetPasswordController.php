<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected function sendResetResponse(Request $request, $response)
    {
        return response(['meassage' => trans($response)], 200);
    }


    protected function sendResetFailedResponse(Request $request, $response)
    {
        return response(['errors' => ['email' => trans($response)]], 422);
    }
}
