<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $notifications = $user->notifications()
            ->whereNull('hidden_at')
            ->latest()
            ->get();

        $taskIds = $notifications->pluck('data.task_id')->unique();

        $tasks = \App\Models\Task::whereIn('id', $taskIds)->get()->keyBy('id');

        $notifications->map(function ($notification) use ($tasks) {
            $notification->task = $tasks[$notification->data['task_id']] ?? null;
            return $notification;
        });
        return view('notice.notifications', compact('notifications'));
    }

    public function read($id, Request $request)
    {
        $notification = $request->user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        return back();
    }

    public function hide($id, Request $request)
    {
        $notification = $request->user()
            ->notifications()
            ->where('id', $id)
            ->whereNull('hidden_at')
            ->firstOrFail();

        $notification->update([
            'hidden_at' => now()
        ]);

        return back();
    }
}
