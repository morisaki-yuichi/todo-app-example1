@extends('layout')

@section('title', 'TODO一覧')

@section('content')
    <h2>TODO一覧</h2>

    <p><a class="button" href="{{ route('todos.create') }}">新規作成</a></p>

    @if ($todos->isEmpty())
        <p>TODOがありません。「新規作成」から最初のTODOを登録しましょう。</p>
    @else
        <ul class="todo-list">
            @foreach ($todos as $todo)
                <li class="todo-item {{ $todo->completed ? 'is-completed' : '' }}">
                    <span class="todo-status">{{ $todo->completed ? '完了' : '未完了' }}</span>
                    <span class="todo-title"><a href="{{ route('todos.show', $todo) }}">{{ $todo->title }}</a></span>
                    <span class="todo-date">{{ $todo->created_at->format('Y/m/d H:i') }}</span>
                </li>
            @endforeach
        </ul>
    @endif
@endsection
