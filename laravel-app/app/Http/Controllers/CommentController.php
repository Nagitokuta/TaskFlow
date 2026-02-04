<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Task $task): RedirectResponse
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        Comment::create([
            'task_id' => $task->id,
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
        ]);

        return redirect()->route('tasks.show', $task)->with('message', 'コメントを追加しました。');
    }
}
