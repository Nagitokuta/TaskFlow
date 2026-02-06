<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $query = Task::with(['creator', 'assignee'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $tasks = $query->paginate(15)->withQueryString();
        return view('tasks.index', compact('tasks'));
    }

    public function show(Task $task): View
    {
        $task->load(['creator', 'assignee', 'comments.user']);
        return view('tasks.show', compact('task'));
    }

    public function create(): View
    {
        $users = User::orderBy('name')->get();
        return view('tasks.create', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string', 'in:pending,in_progress,wait_approval,completed'],
            'assigned_to' => ['nullable', 'exists:users,id'],
        ]);

        $validated['created_by'] = $request->user()->id;
        Task::create($validated);

        return redirect()->route('tasks.index')->with('message', 'タスクを作成しました。');
    }

    public function updateStatus(Request $request, Task $task): RedirectResponse
    {
        $task->update(['status' => $request->status]);
        return redirect()->route('tasks.show', $task)->with('message', 'ステータスを更新しました。');
    }

    public function edit(Task $task): View
    {
        $users = User::orderBy('name')->get();
        return view('tasks.edit', compact('task', 'users'));
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string', 'in:pending,in_progress,wait_approval,completed'],
            'assigned_to' => ['nullable', 'exists:users,id'],
        ]);

        $task->update($validated);
        return redirect()->route('tasks.show', $task)->with('message', 'タスクを更新しました。');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('message', 'タスクを削除しました。');
    }
}
