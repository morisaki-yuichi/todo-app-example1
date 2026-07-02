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

    /**
     * 新規作成フォームを表示する
     */
    public function create()
    {
        return view('todos.create');
    }

    /**
     * 新規 TODO を保存する
     */
    public function store(Request $request)
    {
        // 検証に失敗すると自動で前の画面に戻り、エラーと入力値が引き継がれる
        $validated = $request->validate(
            [
                'title' => ['required', 'string', 'max:100'],
                'description' => ['nullable', 'string', 'max:1000'],
            ],
            [
                'title.required' => 'タイトルは必須です。',
                'title.max' => 'タイトルは100文字以内で入力してください。',
                'description.max' => '内容は1000文字以内で入力してください。',
            ],
        );

        Todo::create($validated);

        // PRG パターン: 保存後はリダイレクトして二重登録を防ぐ
        return redirect()
            ->route('todos.index')
            ->with('success', 'TODOを作成しました。');
    }
}
