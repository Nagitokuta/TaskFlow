<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'タスク管理') - {{ config('app.name') }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
    <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>

<body class="bg-gray-50 text-gray-900 min-h-screen">
    <nav class="bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-14">
                <div class="flex items-center gap-6">
                    <a href="{{ route('tasks.index') }}" class="text-lg font-semibold text-gray-800">タスク管理</a>
                    <a href="{{ route('tasks.index') }}" class="text-sm text-gray-600 hover:text-gray-900">一覧</a>
                    @if(auth()->user()->role === 'admin')
                    <a href="{{ route('tasks.create') }}" class="text-sm text-gray-600 hover:text-gray-900">新規作成</a>
                    @endif
                    @if(auth()->user()->role === 'admin')
                    <a href="{{ route('wait_approval_tasks') }}" class="text-sm text-gray-600 hover:text-gray-900">承認待ちのタスク</a>
                    @endif
                    @if(auth()->user()->role === 'user')
                    <a href="{{ route('your_tasks') }}" class="text-sm text-gray-600 hover:text-gray-900">あなたへのタスク</a>
                    @endif
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-500">
                        {{ auth()->user()->name }}
                        (
                        <span class="{{ auth()->user()->role === 'admin' ? 'text-red-500' : 'text-gray-500' }}">
                            {{ auth()->user()->role }}
                        </span>
                        )
                    </span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 hover:text-red-800">ログアウト</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if (session('message'))
        <div class="mb-4 px-4 py-3 rounded-md bg-green-50 text-green-800 border border-green-200">
            {{ session('message') }}
        </div>
        @endif
        @yield('content')
    </main>
</body>

</html>