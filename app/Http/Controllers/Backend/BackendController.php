<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Contact;
use App\Product;
use App\UserFeedback;
use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class BackendController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'verified']);
        $this->middleware('role:Super Admin|Admin');
    }

    public function index()
    {

        $User = 0;
        $Product = 0;
        $Contact = 0;
        $UserFeedback = 0;

        $UserCount = User::all()->count();
        if ($UserCount) {
            $User = $UserCount;
        }
        $ProductCount = Product::all()->count();
        if ($ProductCount) {
            $Product = $ProductCount;
        }
        $ContactCount = Contact::all()->count();
        if ($ContactCount) {
            $Contact = $ContactCount;
        }
        $UserFeedbackCount = UserFeedback::all()->count();

        if ($UserFeedbackCount) {
            $UserFeedback = $UserFeedbackCount;
        }


        return response()->json(['User' => $User, 'Product' => $Product, 'Contact' => $Contact, 'UserFeedback' => $UserFeedback,]);
    }
}
