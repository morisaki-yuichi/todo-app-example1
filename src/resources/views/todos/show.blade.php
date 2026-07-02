@extends('layout')

@section('title', 'TODO詳細')

@section('content')
    <h2>TODO詳細</h2>

    <dl class="todo-detail">
        <dt>タイトル</dt>
        <dd class="{{ $todo->completed ? 'is-completed-text' : '' }}">{{ $todo->title }}</dd>

        <dt>内容</dt>
        <dd class="pre-wrap">{{ $todo->description ?? '(未記入)' }}</dd>

        <dt>状態</dt>
        <dd>{{ $todo->completed ? '完了' : '未完了' }}</dd>

        <dt>作成日時</dt>
        <dd>{{ $todo->created_at->format('Y/m/d H:i') }}</dd>

        <dt>更新日時</dt>
        <dd>{{ $todo->updated_at->format('Y/m/d H:i') }}</dd>
    </dl>

    <form method="POST" action="{{ route('todos.toggle', $todo) }}">
        @csrf
        @method('PATCH')
        <button type="submit">{{ $todo->completed ? '未完了に戻す' : '完了にする' }}</button>
    </form>

    <p>
        <a class="button" href="{{ route('todos.edit', $todo) }}">編集</a>
        <a class="button danger" href="{{ route('todos.delete', $todo) }}">削除</a>
        <a href="{{ route('todos.index') }}">一覧へ戻る</a>
    </p>
@endsection
