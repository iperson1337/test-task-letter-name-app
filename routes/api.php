<?php

use App\Http\Controllers\API\ApplicationController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->prefix('applications')->name('applications.')->group(function () {
    Route::get('/', [ApplicationController::class, 'index'])->name('index');
    Route::get('/statuses', [ApplicationController::class, 'statuses'])->name('statuses');
    Route::post('/', [ApplicationController::class, 'store'])->name('store');
    Route::put('/{application}/approve', [ApplicationController::class, 'approve'])->name('approve');
    Route::put('/{application}/reject', [ApplicationController::class, 'reject'])->name('reject');
});
