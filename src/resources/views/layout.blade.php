<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | TODOアプリ</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <header class="site-header">
        <h1><a href="{{ route('todos.index') }}">TODOアプリ</a></h1>
    </header>

    <main class="container">
        {{-- 操作成功時のメッセージ(フラッシュメッセージ: 1回表示したら消える) --}}
        @if (session('success'))
            <p class="flash-success">{{ session('success') }}</p>
        @endif

        @yield('content')
    </main>
</body>
</html>
