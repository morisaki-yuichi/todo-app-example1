<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * TODO 一覧を表示する(作成日時の新しい順)
     */
    public function index()
    {
        $todos = Todo::latest()->get();

        return view('todos.index', ['todos' => $todos]);
    }
}
