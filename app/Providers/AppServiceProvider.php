<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

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
        // Globally share the logged-in user's subjects list with all layout views
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $view->with('globalSubjects', Auth::user()->subjects()->orderBy('code', 'asc')->get());
            } else {
                $view->with('globalSubjects', collect());
            }
        View::composer('todo.index', function ($view) {
        $todayQuizzes = Quiz::whereDate('date_column', Carbon::today())->get();
        $view->with('todayQuizzes', $todayQuizzes);
    });
        });

        if (app()->environment('local')) {
        URL::forceScheme('https');
    }
    }
}
