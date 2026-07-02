@extends('layout')

@section('title', 'TODO削除の確認')

@section('content')
    <h2>TODO削除の確認</h2>

    <p class="warning">
        次のTODOを削除します。<strong>この操作は取り消せません。</strong>
    </p>

    <dl class="todo-detail">
        <dt>タイトル</dt>
        <dd>{{ $todo->title }}</dd>

        <dt>内容</dt>
        <dd class="pre-wrap">{{ $todo->description ?? '(未記入)' }}</dd>

        <dt>状態</dt>
        <dd>{{ $todo->completed ? '完了' : '未完了' }}</dd>
    </dl>

    <form method="POST" action="{{ route('todos.destroy', $todo) }}">
        @csrf
        @method('DELETE')
        <button type="submit" class="danger">削除する</button>
        <a href="{{ route('todos.show', $todo) }}">キャンセル</a>
    </form>
@endsection
