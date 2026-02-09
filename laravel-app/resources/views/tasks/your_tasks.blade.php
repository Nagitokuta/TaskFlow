@extends('layouts.app')

@section('title', 'あなたへのタスク一覧')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">あなたへのタスク一覧</h1>
</div>

<form method="GET" action="{{ route('tasks.index') }}" class="flex flex-wrap gap-3 mb-6">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="タイトル・説明で検索"
        class="rounded-md border border-gray-300 px-3 py-2 w-64">
    <button type="submit" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300">検索</button>
</form>

<div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">タイトル</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ステータス</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">作成者</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">担当者</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">作成日</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse ($tasks as $task)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-sm text-gray-500">{{ $task->id }}</td>
                <td class="px-4 py-3">
                    <a href="{{ route('tasks.show', $task) }}" class="text-blue-600 hover:underline font-medium">{{ $task->title }}</a>
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 text-xs rounded-full
                            @if($task->status === 'pending') bg-gray-100 text-gray-700
                            @elseif($task->status === 'in_progress') bg-blue-100 text-blue-800
                            @elseif($task->status === 'wait_approval') bg-red-100 text-red-800
                            @elseif($task->status === 'completed') bg-green-100 text-green-800
                            @else bg-green-100 text-green-800
                            @endif">
                        {{ \App\Models\Task::statusLabels()[$task->status] }}
                    </span>
                </td>
                <td class="px-4 py-3 text-sm">{{ $task->creator->name ?? '-' }}</td>
                <td class="px-4 py-3 text-sm">{{ $task->assignee->name ?? '-' }}</td>
                <td class="px-4 py-3 text-sm text-gray-500">{{ $task->created_at->format('Y/m/d') }}</td>
                <td class="px-4 py-3">
                    <a href="{{ route('tasks.edit', $task) }}" class="text-sm text-gray-600 hover:text-gray-900">編集</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-8 text-center text-gray-500">タスクがありません。</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-gray-200">
        {{ $tasks->links() }}
    </div>
</div>
@endsection