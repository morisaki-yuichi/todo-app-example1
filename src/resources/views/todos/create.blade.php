@extends('layout')

@section('title', 'TODO新規作成')

@section('content')
    <h2>TODO新規作成</h2>

    {{-- バリデーションエラーはフォームの上にまとめて日本語で表示する --}}
    @if ($errors->any())
        <ul class="errors">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('todos.store') }}">
        @csrf {{-- CSRF 対策トークン。POST フォームには必須 --}}

        <div class="field">
            <label for="title">タイトル(必須・100文字以内)</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}">
        </div>

        <div class="field">
            <label for="description">内容(任意・1000文字以内)</label>
            <textarea id="description" name="description" rows="5">{{ old('description') }}</textarea>
        </div>

        <button type="submit">作成する</button>
        <a href="{{ route('todos.index') }}">キャンセル</a>
    </form>
@endsection
