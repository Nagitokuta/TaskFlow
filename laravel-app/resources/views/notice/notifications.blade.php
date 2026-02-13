@extends('layouts.app')

@section('title', '通知')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">通知</h1>
</div>

<div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
    <table class="min-w-full border border-gray-200 text-sm">
        <thead class="bg-gray-100 text-gray-600">
            <tr>
                <th class="px-4 py-2 text-left">お知らせ</th>
                <th class="px-4 py-2 text-left">タスクID</th>
                <th class="px-4 py-2 text-left">タスクタイトル</th>
                <th class="px-4 py-2 text-left">状態</th>
                <th class="px-4 py-2 text-left">日付</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-200">
            @forelse ($notifications as $notice)
            <tr class="hover:bg-gray-50">
                <!-- お知らせ -->
                <td class="px-4 py-3">
                    {{ $notice->data['message'] }}
                </td>

                <!-- タスクID -->
                <td class="px-4 py-3 text-gray-500">
                    #{{ $notice->task->id ?? '-' }}
                </td>

                <!-- タスクタイトル -->
                <td class="px-4 py-3">
                    @if($notice->task)
                    <a href="{{ route('tasks.show', $notice->task->id) }}"
                        class="text-blue-600 hover:underline">
                        {{ \Illuminate\Support\Str::limit($notice->task->title, 23, '...') }}
                    </a>
                    @else
                    -
                    @endif
                </td>

                <!-- 状態 -->
                <td class="px-4 py-3">
                    @if (is_null($notice->read_at))
                    <form method="POST" action="{{ route('notifications.read', $notice->id) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="text-red-600 hover:underline">
                            既読にする
                        </button>
                    </form>
                    @else
                    <span class="text-gray-400">既読</span>
                    @endif
                </td>

                <!-- 日付 -->
                <td class="px-4 py-3 text-gray-500">
                    @if($notice->task)
                    {{ \Carbon\Carbon::parse($notice->task->created_at)->format('Y/m/d') }}
                    @else
                    -
                    @endif
                </td>

            </tr>
            @empty
            <div>
                <p class="px-4 py-8 text-center text-gray-500">通知がありません。</p>
            </div>
            @endforelse
        </tbody>
    </table>
</div>
@endsection