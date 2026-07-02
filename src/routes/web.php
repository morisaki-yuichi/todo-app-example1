<?php

use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

// トップページは TODO 一覧へ転送する
Route::redirect('/', '/todos');

Route::get('/todos', [TodoController::class, 'index'])->name('todos.index');
Route::get('/todos/create', [TodoController::class, 'create'])->name('todos.create');
Route::post('/todos', [TodoController::class, 'store'])->name('todos.store');
