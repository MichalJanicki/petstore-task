<?php

use Illuminate\Support\Facades\Route;
use Modules\Petstore\Http\Controllers\PetstoreController;

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

Route::name('petstore.')->group(function () {
    Route::get('/create', [PetstoreController::class, 'create'])->name('create');
    Route::get('/', [PetstoreController::class, 'index'])->name('index');
    Route::get('/{id}', [PetstoreController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [PetstoreController::class, 'edit'])->name('edit');

    Route::delete('/{id}/remove', [PetstoreController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/update', [PetstoreController::class, 'update'])->name('update');
    Route::post('/store', [PetstoreController::class, 'store'])->name('store');
});
