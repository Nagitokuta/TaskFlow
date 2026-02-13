<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Task;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $user = request()->user();
            $view->with('unreadCount', $user->unreadNotifications()->count());

            if (Auth::check() && Auth::user()->role === 'user') {
                /** @var User $user */
                $user = Auth::user();

                $count = $user->pendingAssignedTasks()->count();

                $view->with('pendingTaskCount', $count);
            }

            if ($user->role === 'admin') {
                $approvalCount = Task::waitApproval()->count();
                $view->with('approvalTaskCount', $approvalCount);
            }
        });
    }
}
