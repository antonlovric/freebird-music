<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\AuthController as ControllersAuthController;
use App\Http\Controllers\PostController;
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

//Unprotected routes
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, "verify"])->middleware('signed')->name('verification.verify');
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return ["message" => "Email resent."];
})->name('verification.send');
Route::post("/auth/register", [ControllersAuthController::class, "register"]);
Route::post("/auth/login", [ControllersAuthController::class, "login"]);
Route::get("/posts", [PostController::class, "index"]);
Route::get("/posts/{id}", [PostController::class, "show"]);



//Protected routes
Route::group(["middleware" => ["auth:sanctum"]], function () {
    Route::post("/auth/logout", [ControllersAuthController::class, "logout"]);
    Route::post("/posts", [PostController::class, "store"]);
    Route::put("/posts/{id}", [PostController::class, "update"]);
    Route::delete("/posts/{id}", [PostController::class, "destroy"]);
    Route::post("/logout", [AuthController::class, "logout"]);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
