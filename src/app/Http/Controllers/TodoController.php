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
     * TODO の詳細を表示する
     * (ルートモデルバインディング: {todo} の ID から自動取得。存在しなければ 404)
     */
    public function show(Todo $todo)
    {
        return view('todos.show', ['todo' => $todo]);
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
        $validated = $this->validateTodo($request);

        Todo::create($validated);

        // PRG パターン: 保存後はリダイレクトして二重登録を防ぐ
        return redirect()
            ->route('todos.index')
            ->with('success', 'TODOを作成しました。');
    }

    /**
     * 編集フォームを表示する
     */
    public function edit(Todo $todo)
    {
        return view('todos.edit', ['todo' => $todo]);
    }

    /**
     * TODO を更新する
     */
    public function update(Request $request, Todo $todo)
    {
        $validated = $this->validateTodo($request);

        $todo->update($validated);

        return redirect()
            ->route('todos.show', $todo)
            ->with('success', 'TODOを更新しました。');
    }

    /**
     * 作成・更新で共通のバリデーション(DRY: ルールを1箇所に集約)
     * 検証に失敗すると自動で前の画面に戻り、エラーと入力値が引き継がれる
     */
    private function validateTodo(Request $request): array
    {
        return $request->validate(
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
    }
}
