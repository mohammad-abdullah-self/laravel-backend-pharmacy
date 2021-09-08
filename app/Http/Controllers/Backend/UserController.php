<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\User as UserResource;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:api', 'verified']);
        $this->middleware('role:Super Admin|Admin')->only(['index']);
        $this->middleware('role:Super Admin')->only(['getUser', 'addRoleToUsers']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->getUserRoleName() == "Super Admin") {
            return UserResource::collection(User::where('id', "!=", auth()->user()->id)->orderBy('id', 'DESC')->get());
        } elseif (auth()->user()->getUserRoleName() == "Admin") {
            $users = User::role('Super Admin')->first();
            return UserResource::collection(User::where('id', "!=", $users->id)->where('id', "!=", auth()->user()->id)->orderBy('id', 'DESC')->get());
        }
    }

    public function getUser(Request $request)
    {
        $user = User::whereId($request->id)->get();

        return UserResource::collection($user);
    }


    public function addRoleToUsers(Request $request)
    {

        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $role = null;

        // if ($request->role === 1) {
        //     $role = 'Super Admin';
        // }

        if ($request->role === 2) {
            $role = 'Admin';
        }

        if ($request->role === 3) {
            $role = 'User';
        }


        $user = User::findOrFail($request->id);
        $user->syncRoles($role);
        $user = User::whereId($request->id)->get();

        return UserResource::collection($user);
    }
}
