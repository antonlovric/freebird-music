<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\AuthController as ControllersAuthController;
use App\Http\Controllers\ConditionController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\OpenRequestController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Models\CartItem;
use App\Models\Product;
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

Route::controller(ControllersAuthController::class)->group(function () {
    Route::post("/auth/register", [ControllersAuthController::class, "register"]);
    Route::post("/auth/login", [ControllersAuthController::class, "login"]);
});

Route::controller(PostController::class)->group(function () {
    Route::get("/posts", "index");
    Route::get("/posts/{id}", "show");
    Route::get("/posts/search/{authorID}", "searchAuthor");
});

Route::controller(DiscountController::class)->group(function () {
    Route::get("/discounts", "index");
});

Route::controller(GenreController::class)->group(function () {
    Route::get("/genres", "index");
});

Route::controller(ProductController::class)->group(function () {
    Route::get("/products", "index");
    Route::get("/products/{id}", "show");
    Route::put("/products/rate/{id}", "rateProduct");
    Route::get("/products/search/{title}", "searchTitle");
    Route::get("/products/filter/{minRating}{maxRating}", "searchTitle");
});

Route::controller(PaymentController::class)->group(function () {
    Route::get("/payments/order/{id}", "index");
    Route::post("/payments", "store");
});

Route::controller(CartItem::class)->group(function () {
    Route::get("/cartItems", "index");
    Route::get("/cartItems/{id}", "show");
    Route::post("/cartItems", "store");
    Route::put("/cartItems/{id}", "update");
    Route::delete("/cartItems/{id}", "destroy");
});

Route::controller(ConditionController::class)->group(function () {
    Route::get("/conditions", "index");
});

Route::controller(ProductTypeController::class)->group(function () {
    Route::get("/productTypes", "index");
});


//Protected routes
Route::group(["middleware" => ["auth:sanctum"]], function () {
    // Posts
    Route::post("/posts", [PostController::class, "store"]);
    Route::put("/posts/{id}", [PostController::class, "update"]);
    Route::delete("/posts/{id}", [PostController::class, "destroy"]);

    // Images
    Route::post("/images", [ImageController::class, "store"]);
    Route::delete("/images/{id}", [ImageController::class, "destroy"]);

    // Tags
    Route::post("/tags", [TagController::class, "store"]);
    Route::put("/tags/{id}", [TagController::class, "update"]);
    Route::delete("/tags/{id}", [TagController::class, "destroy"]);

    // ProductTypes
    Route::post("/productTypes", [ProductTypeController::class, "store"]);
    Route::put("/productTypes/{id}", [ProductTypeController::class, "update"]);
    Route::delete("/productTypes/{id}", [ProductTypeController::class, "destroy"]);

    // Products
    Route::controller(ProductController::class)->group(function () {
        Route::post("/products", "store");
        Route::put("/products/rateProduct/{id}", "rateProduct");
        Route::put("/products/{id}", "update");
        Route::delete("/products/{id}", "destroy");
    });

    Route::controller(OpenRequestController::class)->group(function () {
        Route::get("/openRequests", "index");
        Route::get("/openRequests/{id}", "show");
        Route::get("/openRequests/user/{id}", "userRequests");
        Route::post("/openRequests", "store");
        Route::put("/openRequests", "update");
        Route::delete("/openRequests/{id}", "destroy");
    });

    Route::controller(DiscountController::class)->group(function () {
        Route::get("/discounts/{id}", "show");
        Route::post("/discounts", "store");
        Route::put("/discounts", "update");
        Route::delete("/discounts/{id}", "destroy");
    });

    Route::controller(GenreController::class)->group(function () {
        Route::get("/genres/{id}", "show");
        Route::post("/genres", "store");
        Route::put("/genres", "update");
        Route::delete("/genres/{id}", "destroy");
    });

    Route::controller(PaymentController::class)->group(function () {
        Route::get("/payments", "index");
    });

    Route::controller(OrderController::class)->group(function () {
        Route::get("/orders", "index");
        Route::post("/orders", "store");
        Route::get("/orders/{id}", "show");
    });

    Route::controller(UserController::class)->group(function () {
        Route::get("/users", "index");
        Route::post("/users/deleteUsers", "destroyUsers");
    });

    

    Route::post("/auth/logout", [ControllersAuthController::class, "logout"]);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
