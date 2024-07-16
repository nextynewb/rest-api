<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\API\AuthController;

Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
});


Route::controller(TodoController::class)->group(function () {
    Route::get('/get_all_data', 'get_all_data');

});


Route::middleware(['auth:api'])->group(function () {

    Route::controller(TodoController::class)->group(function () {
        Route::get('/todo/index/{todo?}', 'index');
        Route::post('/todo/store', 'store');
        Route::post('/todo/update', 'update');
        Route::post('/todo/update_status', 'update_status');
        Route::get("/todo/get_tasks_by_id", "get_tasks_by_id");
        Route::post('/todo/delete', 'delete');
        


    });

    Route::controller(AuthController::class)->group(function () {
        Route::post('/logout', 'logout');
    });

});



