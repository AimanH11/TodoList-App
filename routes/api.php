<?php
// routes/api.php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoItemController;

Route::apiResource('/todo', TodoItemController::class);
