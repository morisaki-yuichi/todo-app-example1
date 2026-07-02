<?php

use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

// トップページは TODO 一覧へ転送する
Route::redirect('/', '/todos');

Route::get('/todos', [TodoController::class, 'index'])->name('todos.index');
// 注意: /todos/create は可変パス /todos/{todo} より先に定義する
// (後にすると "create" が {todo} として解釈されてしまう)
Route::get('/todos/create', [TodoController::class, 'create'])->name('todos.create');
Route::post('/todos', [TodoController::class, 'store'])->name('todos.store');
Route::get('/todos/{todo}', [TodoController::class, 'show'])->name('todos.show');
Route::get('/todos/{todo}/edit', [TodoController::class, 'edit'])->name('todos.edit');
Route::put('/todos/{todo}', [TodoController::class, 'update'])->name('todos.update');
Route::patch('/todos/{todo}/toggle', [TodoController::class, 'toggle'])->name('todos.toggle');
