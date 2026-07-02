<?php

use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

// トップページは TODO 一覧へ転送する
Route::redirect('/', '/todos');

Route::get('/todos', [TodoController::class, 'index'])->name('todos.index');
