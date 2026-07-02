@extends('layout')

@section('title', 'TODO編集')

@section('content')
    <h2>TODO編集</h2>

    @if ($errors->any())
        <ul class="errors">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('todos.update', $todo) }}">
        @csrf
        @method('PUT') {{-- HTMLフォームはPUTを送れないため、Laravelに読み替えさせる --}}

        <div class="field">
            <label for="title">タイトル(必須・100文字以内)</label>
            {{-- old(): 検証失敗時は直前の入力、初回表示は現在の値 --}}
            <input type="text" id="title" name="title" value="{{ old('title', $todo->title) }}">
        </div>

        <div class="field">
            <label for="description">内容(任意・1000文字以内)</label>
            <textarea id="description" name="description" rows="5">{{ old('description', $todo->description) }}</textarea>
        </div>

        <button type="submit">更新する</button>
        <a href="{{ route('todos.show', $todo) }}">キャンセル</a>
    </form>
@endsection
