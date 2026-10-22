<?php

namespace App\Providers;

use App\Listeners\LogUserLogin;
use App\Listeners\LogUserLogout;
use App\Models\Batch;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        Password::defaults(function () {
            return Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols();
        });

        View::composer('layouts.app', function ($view) {
            $count = 0;
            if (Auth::check()) {
                $user = Auth::user();
                $role = $user->role ?? null;

                if ($role === 'Supervisor') {
                    $count = Batch::where('status', 'submitted')
                        ->where('supervisor_id', $user->id)
                        ->count();
                } elseif ($role === 'Manager') {
                    $count = Batch::where('status', 'waiting_manager')
                        ->where('manager_id', $user->id)
                        ->count();
                } elseif ($role === 'Super Admin') {
                    $count = Batch::whereIn('status', ['submitted', 'waiting_manager'])
                        ->count();
                }
            }

            $view->with('sidebarPendingApprovalCount', $count);
        });
    }
}
