<?php

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Auth\UserLoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Common\CountryStateController;
use App\Http\Controllers\API\Auth\UserRegistrationController;
use PHPUnit\Framework\TestStatus\Risky;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->name('auth')->group(function() {
    Route::post('register', UserRegistrationController::class)->middleware('guest')->name('register');
    Route::middleware(['guest:' . config('fortify.guard')])->post('login', UserLoginController::class)->name('register');
    Route::get('user', [AuthController::class, 'getAuthenticatedUser'])->middleware('auth:sanctum')->name('user');
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
    Route::post('password/forgot', [AuthController::class, 'forgotPassword'])->middleware('throttle:5,1')->name('password.forgot');
	Route::post('password/reset', [AuthController::class, 'resetPassword'])->name('password.reset');
});


Route::controller(CountryStateController::class)->group(function () {
    Route::get('countries', 'listCountries');
    Route::get('states', 'listStates');
});

