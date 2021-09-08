<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// auth
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth',
    'namespace' => 'Backend\Auth',
], function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::get('user', 'AuthController@user');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('logout', 'AuthController@logout');
    // auth email verify
    Route::get('/email/resend', 'EmailVerificationController@resend')->name('verification.resend');
    Route::get('/email/verify/{id}/{hash}', 'EmailVerificationController@verify')->name('verification.verify');
    // reset password
    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'ResetPasswordController@reset');
});

// backend
Route::group([
    'middleware' => 'api',
    'namespace' => 'Backend',
], function () {

    Route::post('user/detail', 'UserDetailController@update');
    Route::post('user/setting', 'UserDetailController@setting');

    Route::apiResource('category', 'CategoryController');

    Route::apiResource('product', 'ProductController');
    Route::get('producttrash', 'ProductController@productTrash');
    Route::post('productrestore', 'ProductController@productRestore');
    Route::post('productForceDelete', 'ProductController@productForceDelete');

    Route::apiResource('blog', 'BlogController');
    Route::get('blogTrash', 'BlogController@blogTrash');
    Route::post('blogRestore', 'BlogController@blogRestore');
    Route::post('blogForceDelete', 'BlogController@blogForceDelete');

    Route::apiResource('contact', 'ContactController');
    Route::get('contactFileDownload/{fileId}', 'ContactController@contactFileDownload');
    Route::get('contactTrash', 'ContactController@contactTrash');
    Route::post('contactRestore', 'ContactController@contactRestore');
    Route::post('contactForceDelete', 'ContactController@contactForceDelete');

    Route::apiResource('userfeedback', 'UserFeedbackController');
    Route::get('allFeedbackIndex', 'UserFeedbackController@allFeedbackIndex');
    Route::post('approveOrNot', 'UserFeedbackController@approveOrNot');
    Route::post('delete', 'UserFeedbackController@delete');

    Route::get('totalDetailsIndex', 'BackendController@index');

    Route::get('userIndex', 'UserController@index');
    Route::get('getUser/{id}', 'UserController@getUser');
    Route::post('addRoleToUsers', 'UserController@addRoleToUsers');

    Route::apiResource('banner', 'BannerController');

    Route::apiResource('footer', 'FooterController');

    Route::apiResource('notification', 'NotificationController');
    Route::post('notificationDelete', 'NotificationController@notificationDelete');
});

// fontend
Route::group([
    'middleware' => 'api',
    'namespace' => 'Fontend',
], function () {

    Route::get('bannerIndex', 'FontendController@bannerIndex');
    Route::get('footerIndex', 'FontendController@footerIndex');
    Route::get('productIndex', 'FontendController@productIndex');
    Route::get('blogIndex', 'FontendController@blogIndex');
    Route::get('showBlog/{slug}', 'FontendController@showBlog');
    Route::get('allProductIndex', 'FontendController@allProductIndex');
    Route::get('categoryIndex', 'FontendController@categoryIndex');
    Route::get('userFeedbackIndex', 'FontendController@userFeedbackIndex');
});
