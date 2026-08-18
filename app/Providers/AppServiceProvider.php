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
        if (Auth::check()) {
            $today = now()->toDateString();
            $userId = Auth::id();

            // Run your exact AssessmentController logic behind the scenes
            $todayQuizzesCount = Assessment::where('user_id', $userId)
                ->where('type', 'quiz')
                ->whereDate('assessment_date', $today)
                ->count();

            $todayExamsCount = Assessment::where('user_id', $userId)
                ->where('type', 'exam')
                ->whereDate('assessment_date', $today)
                ->count();

            $todayQuizzes = Assessment::with('subject')
                ->where('user_id', $userId)
                ->where('type', 'quiz')
                ->whereDate('assessment_date', $today)
                ->get();

            $quizzes = Assessment::with('subject')
                ->where('user_id', $userId)
                ->where('type', 'quiz')
                ->orderBy('assessment_date', 'asc')
                ->get()
                ->groupBy('status');

            $exams = Assessment::with('subject')
                ->where('user_id', $userId)
                ->where('type', 'exam')
                ->orderBy('assessment_date', 'asc')
                ->get()
                ->groupBy('status');

            $subjects = \App\Models\Subject::where('user_id', $userId)->get();

            // Inject them directly into the todo view data pool
            $view->with(compact(
                'todayQuizzesCount',
                'todayExamsCount',
                'todayQuizzes',
                'quizzes',
                'exams',
                'subjects'
            ));
        }
    });
        });

        if (app()->environment('local')) {
        URL::forceScheme('https');
    }
    }
}
