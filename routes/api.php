<?php

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

use App\Http\Controllers\TenantController;

Route::prefix('tenants')->group(function () {
    Route::post('add', [TenantController::class, 'create']);
    Route::get('/', [TenantController::class, 'index']);
    Route::delete('/{id}', [TenantController::class, 'delete']);
});

Route::prefix('domains')->group(function () {
    Route::get('/', [TenantController::class, 'showDomains']);
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
