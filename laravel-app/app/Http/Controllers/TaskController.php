<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;
use App\Notifications\TaskWaitApprovalNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $query = Task::with(['creator', 'assignee'])
            ->orderByRaw("
            CASE
                WHEN status = 'pending' THEN 1
                WHEN status = 'in_progress' THEN 2
                WHEN status = 'wait_approval' THEN 3
                WHEN status = 'completed' THEN 4
                ELSE 5
            END
        ")
            ->orderByDesc('id');

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
            'assigned_to' => ['required', 'exists:users,id'],
        ]);

        $validated['created_by'] = $request->user()->id;
        $task = Task::create($validated);
        $assignee = User::find($validated['assigned_to']);
        $assignee->notify(new TaskAssignedNotification($task));

        return redirect()->route('tasks.index')->with('message', 'タスクを作成しました。');
    }

    public function yourTasks(Request $request)
    {
        $userId = $request->user()?->id;

        if (!$userId) {
            abort(403);
        }

        $tasks = Task::where('assigned_to', $userId)
            ->latest()
            ->paginate(15);

        return view('tasks.your_tasks', compact('tasks'));
    }

    public function wait_approval_tasks(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            abort(403);
        }

        $tasks = Task::where('status', 'wait_approval')
            ->latest()
            ->paginate(15);

        return view('tasks.wait_approval_tasks', compact('tasks'));
    }


    public function updateStatus(Request $request, Task $task): RedirectResponse
    {
        $task->update(['status' => $request->status]);

        if (in_array($task->status, [
            Task::STATUS_WAIT_APPROVAL,
            Task::STATUS_COMPLETED
        ])) {
            return back()->with('error', 'このタスクは変更できません。');
        }

        if ($request->status === 'wait_approval') {
            $admins = User::where('role', 'admin')->get();

            foreach ($admins as $admin) {
                $admin->notify(new TaskWaitApprovalNotification($task));
            }
        }
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

        $user = $request->user();
        if (
            $task->created_by !== $user->id
            && $user->role !== 'admin'
        ) {
            return redirect()
                ->route('tasks.index')
                ->with('error', '編集権限がありません');
        }

        $task->update($validated);
        return redirect()->route('tasks.show', $task)->with('message', 'タスクを更新しました。');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('message', 'タスクを削除しました。');
    }
}
