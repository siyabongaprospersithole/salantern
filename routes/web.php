<?php

use Illuminate\Support\Facades\Route;
use App\Mail\Noreply;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Navigates to login page
Route::get('/', function () {
    return view('login');
});

// Navigates to signup page
Route::get('/account/signup', function(){
    return view('signup');
});

// Navigates to home page after authentication
//I didn't use any middleware on the routes page 
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']);

//POST route to update authenticated user details
Route::post('/u/profile/update', [App\Http\Controllers\UserController::class, 'update']);

//POST route to update authenticated user password
Route::post('/u/password/update', [App\Http\Controllers\UserController::class, 'update']);

//GET route to logout
Route::get('/u/logout', [App\Http\Controllers\LoginController::class, 'logout']);

//POST route to create a new user
Route::post('/account/signup', [App\Http\Controllers\SignupController::class, 'store']);

//POST route to login user
Route::post('/account/login', [App\Http\Controllers\LoginController::class, 'login']);

