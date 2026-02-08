@extends('layouts.app')

@section('title', 'タスク作成')

@section('content')
<div class="mb-6">
    <a href="{{ route('tasks.index') }}" class="text-sm text-gray-600 hover:text-gray-900">← 一覧へ</a>
    <h1 class="text-2xl font-bold mt-2">タスク作成</h1>
</div>

<div class="bg-white rounded-lg shadow border border-gray-200 p-6 max-w-2xl">
    <form method="POST" action="{{ route('tasks.store') }}" class="space-y-4">
        @csrf
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">タイトル <span class="text-red-500">*</span></label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                class="w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            @error('title')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">説明</label>
            <textarea name="description" id="description" rows="4"
                class="w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">{{ old('description') }}</textarea>
            @error('description')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">ステータス <span class="text-red-500">*</span></label>
            <select name="status" id="status" class="w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                @foreach (\App\Models\Task::statusLabels() as $value => $label)
                <option value="{{ $value }}" @selected(old('status', 'pending' )===$value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('status')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-1">担当者</label>
            <select
                name="assigned_to"
                id="assigned_to"
                class="w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                <option value="" disabled>選択してください</option>
                @foreach ($users as $user)
                <option
                    value="{{ $user->id }}"
                    @selected(old('assigned_to', auth()->id()) == $user->id)
                    >
                    {{ $user->name }}
                </option>
                @endforeach
            </select>
            @error('assigned_to')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 font-medium">作成</button>
            <a href="{{ route('tasks.index') }}" class="bg-gray-200 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-300">キャンセル</a>
        </div>
    </form>
</div>
@endsection