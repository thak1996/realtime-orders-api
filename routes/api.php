<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Events\MyEvent;

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

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'],)->group(function () {
    Route::post("/logout", [AuthController::class, "logout"]);
    Route::get("/user", [AuthController::class, "user"]);

    Route::post("/orders", [OrderController::class, "store"])->middleware('role:customer');
});

Route::get("/status", fn() => response()->json(["status" => "API is running"]));

Route::get('/my-event', fn() => event(new MyEvent("Hello World")));
