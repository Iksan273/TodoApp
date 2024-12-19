<?php

use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

Route::middleware('jwt.auth')->group(function () {
    Route::post('checklists', [ChecklistController::class, 'create']);
    Route::delete('checklists/{id}', [ChecklistController::class, 'delete']);
    Route::get('checklists', [ChecklistController::class, 'index']);
    Route::get('checklists/{id}', [ChecklistController::class, 'show']);

    Route::post('checklists/{id}/items', [ItemController::class, 'create']);
    Route::get('items/{id}', [ItemController::class, 'show']);
    Route::put('items/{id}', [ItemController::class, 'update']);
    Route::put('items/{id}/status', [ItemController::class, 'updateStatus']);
    Route::delete('items/{id}', [ItemController::class, 'delete']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::get('me', [UserController::class, 'me']);
});
