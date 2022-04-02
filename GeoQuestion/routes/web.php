<?php

use App\Http\Controllers\CustomAuthController;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => 'auth'], function () {
    Route::get('', [CustomAuthController::class, 'dashboard']);
    Route::get('nextQuestion', [\App\Http\Controllers\GameController::class, 'getNextQuestion'])->name('nextQuestion');
    Route::post('sendAnswer', [\App\Http\Controllers\GameController::class, 'postSendAnswer'])->name('postSendAnswer');
    Route::get('getLeaderBoard', [\App\Http\Controllers\LeaderboardController::class, 'getLeaderBoard'])->name('getLeaderBoard');
});
Route::group(['middleware' => 'guest'], function () {
    Route::get('login', [CustomAuthController::class, 'index'])->name('login');
    Route::post('custom-login', [CustomAuthController::class, 'customLogin'])->name('login.custom');
    Route::get('registration', [CustomAuthController::class, 'registration'])->name('register-user');
    Route::post('custom-registration', [CustomAuthController::class, 'customRegistration'])->name('register.custom');
    Route::get('signout', [CustomAuthController::class, 'signOut'])->name('signout');

});

