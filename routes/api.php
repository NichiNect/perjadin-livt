<?php

use App\Http\Controllers\PicklistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('picklist')->group(function () {

    Route::get('/role', [PicklistController::class, 'optionRole']);
    Route::get('/province', [PicklistController::class, 'optionProvince']);
    Route::get('/city', [PicklistController::class, 'optionCity']);
    Route::get('/user', [PicklistController::class, 'optionUser']);
});