<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// use App\Http\Controllers\TenantController;

// Route::prefix('tenants')->group(function () {
//     Route::post('add', [TenantController::class, 'create']);
//     Route::get('/', [TenantController::class, 'index']);
// });

// Route::prefix('domains')->group(function () {
//     Route::get('/', [TenantController::class, 'showDomains']);
// });

Route::get('/', function () {
    return view('welcome');
});
