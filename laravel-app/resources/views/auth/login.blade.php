<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ログイン - {{ config('app.name') }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
    <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>

<body class="bg-gray-50 text-gray-900 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <h1 class="text-2xl font-bold text-center mb-6">タスク管理システム</h1>
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
            <h2 class="text-lg font-semibold mb-4">ログイン</h2>
            @if ($errors->any())
            <div class="mb-4 px-4 py-3 rounded-md bg-red-50 text-red-800 border border-red-200 text-sm">
                @foreach ($errors->all() as $err)
                <p>{{ $err }}</p>
                @endforeach
            </div>
            @endif
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">メールアドレス</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">パスワード</label>
                    <input type="password" name="password" id="password" required
                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" class="rounded border-gray-300">
                    <label for="remember" class="ml-2 text-sm text-gray-600">ログイン状態を保持</label>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 font-medium">
                    ログイン
                </button>
            </form>
        </div>
        <a href="{{ route('register') }}" class="block text-center text-sm text-gray-600 hover:text-gray-900 mt-4">新規登録はこちら</a>
    </div>
</body>

</html>