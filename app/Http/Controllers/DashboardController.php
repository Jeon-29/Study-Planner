<?php

namespace App\Http\Controllers;

use App\Models\ClassSchedule;
use App\Models\Todo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $now = Carbon::now();
        $todayDayName = $now->format('l');

        // 1. Fetch nearest pending task for the Hero Card
        $nextUpTask = Todo::with('subjectDetails')
            ->where('user_id', $userId)
            ->where('status', 'pending')
            ->whereNotNull('due_date')
            ->orderBy('due_date', 'asc')
            ->orderBy('due_time', 'asc')
            ->first();

        // 2. Fetch today's class schedules
        $todaysSchedules = ClassSchedule::with('subject')
            ->whereHas('subject', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where('day_of_week', $todayDayName)
            ->orderBy('start_time', 'asc')
            ->get();

        // 3. Determine if today's classes are completely finished
        $allTodayFinished = false;

        if ($todaysSchedules->isNotEmpty()) {
            $lastClass = $todaysSchedules->last();
            $lastClassEndTime = Carbon::parse($lastClass->end_time);

            if ($now->gt($lastClassEndTime)) {
                $allTodayFinished = true;
            }
        } else {
            $allTodayFinished = true;
        }

        // 4. Fetch the next upcoming class if today's classes are finished
        $nextClassSchedule = null;
        $nextClassDayLabel = null;

        if ($allTodayFinished) {
            // Check the next 7 days to find the next active schedule
            for ($i = 1; $i <= 7; $i++) {
                $targetDate = $now->copy()->addDays($i);
                $targetDayName = $targetDate->format('l');

                $upcomingSchedule = ClassSchedule::with('subject')
                    ->whereHas('subject', function ($query) use ($userId) {
                        $query->where('user_id', $userId);
                    })
                    ->where('day_of_week', $targetDayName)
                    ->orderBy('start_time', 'asc')
                    ->first();

                if ($upcomingSchedule) {
                    $nextClassSchedule = $upcomingSchedule;
                    $nextClassDayLabel = ($i === 1) ? 'Tomorrow' : $targetDayName;
                    break;
                }
            }
        }

        // 5. Extract unique subjects for today
        $todaysSubjects = $todaysSchedules->pluck('subject')->filter()->unique('id');

        // 6. Calculate task progress statistics
        $totalTasks = Todo::where('user_id', $userId)->count();
        $completedTasks = Todo::where('user_id', $userId)->where('status', 'done')->count();

        // Prevent division by zero if no tasks exist
        $progressPercentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        return view('dashboard', compact(
            'nextUpTask',
            'todaysSchedules',
            'allTodayFinished',
            'nextClassSchedule',
            'nextClassDayLabel',
            'todaysSubjects',
            'totalTasks',
            'completedTasks',
            'progressPercentage'
        ));
    }
}
