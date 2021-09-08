<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserDetailValidation;
use Illuminate\Http\Request;
use App\UserDetail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'verified']);
    }

    public function update(UserDetailValidation $request)
    {

        if (!Hash::check($request->password, auth()->user()->getAuthPassword())) {
            return response()->json(['errors' => ['password' => "Password do not match our records."]], 403);
        }

        $avatar_path =  auth()->user()->userdetail->avatar;
        $pathinfo = pathinfo($avatar_path);
        $userAvatar = $pathinfo['filename'] . '.' . $pathinfo['extension'];

        if (request()->hasFile('avatar')) {


           
            if ($userAvatar && $userAvatar != "avatar.jpg") {
                Storage::delete('public/avatar/' . $userAvatar);
            }

            $file = request()->file("avatar");
            $avatar_make = \Image::make($file)->fit(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->encode();
            $giveAvatarName = time() . "-" . request("avatar")->getClientOriginalName();
            Storage::put($giveAvatarName, $avatar_make);
            Storage::move($giveAvatarName, 'public/avatar/' . $giveAvatarName);
            $userAvatar = $giveAvatarName;
        }

        request()->user()->userdetail()->update([
            'avatar' =>  $userAvatar,
        ]);

        if ($request->name) {
            request()->user()->update([
                "name" => $request->name,
            ]);
        }
        if ($request->phone) {
            request()->user()->userdetail()->update([
                "phone" => $request->phone,
            ]);
        }
        if ($request->address) {
            request()->user()->userdetail()->update([
                "address" => $request->address,
            ]);
        }
        if ($request->email) {
            request()->user()->update([
                "email" => $request->email,
                "email_verified_at" => null,
            ]);
            return  response()->json("logout", 200);
        }


        return  response()->json(null, 200);
    }


    public function setting(Request $request)
    {
        request()->validate([
            'old_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        if (!Hash::check($request->old_password, auth()->user()->getAuthPassword())) {
            return response()->json(['errors' => ['old_password' => "Password do not match our records."]], 403);
        }

        if ($request->password) {
            request()->user()->update([
                'password' => Hash::make($request->password),

            ]);
        }

        return  response()->json(null, 200);
    }
}
