@extends('layouts.app')

@section('title', $task->title)

@section('content')
<div class="mb-6 flex justify-between items-start">
    <div>
        <a href="{{ route('tasks.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mb-2 inline-block">← 一覧へ</a>
        <h1 class="text-2xl font-bold">{{ $task->title }}</h1>
        <p class="text-sm text-gray-500 mt-1">#{{ $task->id }} · 作成: {{ $task->creator->name }} · {{ $task->created_at->format('Y/m/d H:i') }}</p>
    </div>
    <a href="{{ route('tasks.edit', $task) }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300 text-sm">編集</a>
</div>

<div class="bg-white rounded-lg shadow border border-gray-200 p-6 mb-6">
    <dl class="grid gap-4">
        <div>
            <dt class="text-sm font-medium text-gray-500">ステータス</dt>
            <dd>
                <span class="px-2 py-1 text-sm rounded-full
                    @if($task->status === 'pending') bg-gray-100 text-gray-700
                    @elseif($task->status === 'in_progress') bg-blue-100 text-blue-800
                    @elseif($task->status === 'wait_approval') bg-red-100 text-red-800
                    @elseif($task->status === 'completed') bg-green-100 text-green-800
                    @endif">
                    {{ \App\Models\Task::statusLabels()[$task->status] }}
                </span>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">担当者</dt>
            <dd class="text-gray-900">{{ $task->assignee->name ?? '未割当' }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">説明</dt>
            <dd class="text-gray-900 whitespace-pre-wrap">{{ $task->description ?: '（なし）' }}</dd>
        </div>
        <form method="POST" action="{{ route('tasks.status.update', $task) }}">
            @csrf
            @method('PUT')
            <div>
                <!--ステータス変更ボタンはこのタスクの担当者にのみ表示-->
                @if($task->assignee?->id === auth()->user()->id&& $task->status !== 'completed')
                <!--タスクのステータスがボタンと同じ値ならdisableにする-->
                <button
                    type="submit"
                    name="status"
                    value="wait_approval"
                    @disabled($task->status === 'wait_approval')
                    class="mt-2 py-2 px-4 rounded-md text-sm
                    {{ $task->status === 'wait_approval'
            ? 'bg-gray-400 cursor-not-allowed text-white'
            : 'bg-blue-600 hover:bg-blue-700 text-white'
        }}"
                    >
                    承認待ちにする
                </button>
                <button
                    type="submit"
                    name="status"
                    value="in_progress"
                    @disabled($task->status === 'in_progress')
                    class="mt-2 py-2 px-4 rounded-md text-sm
                    {{ $task->status === 'in_progress'
            ? 'bg-gray-400 cursor-not-allowed text-white'
            : 'bg-blue-600 hover:bg-blue-700 text-white'
        }}"
                    >
                    対応中にする
                </button>
                @endif
                <!--承認するボタンは管理者のみ表示-->
                @if(auth()->user()->role === 'admin' && $task->status === 'wait_approval')
                <button type="submit" name="status" value="completed" class="mt-2 bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 text-sm">承認する</button>
                @endif
            </div>
        </form>
    </dl>
</div>

<div class="bg-white rounded-lg shadow border border-gray-200 p-6">
    <h2 class="text-lg font-semibold mb-4">コメント</h2>
    <form method="POST" action="{{ route('tasks.comments.store', $task) }}" class="mb-6">
        @csrf
        <textarea name="body" rows="3" required placeholder="コメントを入力..."
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"></textarea>
        @error('body')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
        <button type="submit" class="mt-2 bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 text-sm">投稿</button>
    </form>

    <ul class="space-y-4">
        @forelse ($task->comments as $comment)
        <li class="border-l-2 border-gray-200 pl-4 py-2">
            <p class="text-gray-900 whitespace-pre-wrap">{{ $comment->body }}</p>
            <p class="text-sm text-gray-500 mt-1">{{ $comment->user->name }} · {{ $comment->created_at->format('Y/m/d H:i') }}</p>
        </li>
        @empty
        <li class="text-gray-500 text-sm">コメントはまだありません。</li>
        @endforelse
    </ul>
</div>
@endsection