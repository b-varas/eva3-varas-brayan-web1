<?php

use App\Http\Controllers\Api\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/proyectos/{id}', [ProjectController::class, 'show']);
Route::post('/proyectos', [ProjectController::class, 'store']);
Route::get('/proyectos', [ProjectController::class, 'index']);
