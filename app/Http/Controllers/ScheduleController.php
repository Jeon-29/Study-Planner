<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassSchedule;
use App\Models\Todo;
use App\Models\Subject;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index()
    {
        // 1. Fetch only the authenticated user's schedules
        $classSchedules = ClassSchedule::query()->where('user_id', Auth::id())
            ->with('subject')
            ->get();

        // Scope subjects to the logged-in user to prevent data leaks
        $subjects = Subject::query()->where('user_id', Auth::id())->get();

        // Scope the raw schedules to the logged-in user
        $rawSchedules = ClassSchedule::query()->where('user_id', Auth::id())
            ->with('subject')
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        // 3. Map recurring weekly schedules to actual dates for the calendar
        $schedules = [];
        $startOfMonth = now()->startOfMonth()->subDays(7);
        $endOfMonth = now()->endOfMonth()->addDays(7);
        $period = CarbonPeriod::create($startOfMonth, $endOfMonth);

        foreach ($period as $date) {
            $dateString = $date->format('Y-m-d');

            $matchingSchedules = $classSchedules->filter(function ($schedule) use ($date) {
                $dayOfWeekInput = strtolower($schedule->day_of_week);

                return $dayOfWeekInput === strtolower($date->format('l')) ||
                       $dayOfWeekInput === strtolower($date->format('D')) ||
                       $dayOfWeekInput == $date->dayOfWeek;
            });

            if ($matchingSchedules->isNotEmpty()) {
                $schedules[$dateString] = $matchingSchedules->map(function ($schedule) {
                    $startTime = Carbon::parse($schedule->start_time);
                    $endTime = Carbon::parse($schedule->end_time);

                    $subjectName = $schedule->subject->name
                        ?? $schedule->subject->title
                        ?? $schedule->subject->subject_name
                        ?? 'Class';

                    $subjectCode = $schedule->subject->code
                        ?? $schedule->subject->subject_code
                        ?? null;

                    // Dynamically read color_theme from the subjects table
                    $subjectColor = $schedule->subject->color_theme
                        ?? $schedule->subject->color
                        ?? '#6366F1';

                    return [
                        'id'         => $schedule->id,
                        'subject'    => $subjectName,
                        'code'       => $subjectCode,
                        'type'       => $schedule->type,
                        'room'       => $schedule->room ?? 'TBA',
                        'start_time' => $startTime->format('H:i'),
                        'end_time'   => $endTime->format('H:i'),
                        'start'      => $startTime->format('g:ia'),
                        'end'        => $endTime->format('g:ia'),
                        'slot'       => $startTime->roundMinute(60)->format('g:ia'),
                        'duration'   => $startTime->format('g:ia') . ' - ' . $endTime->format('g:ia'),
                        'color'      => $subjectColor,
                        'offset'     => 'left-16 right-0',
                    ];
                })->values()->toArray();
            }
        }

        // 4. Fetch todos and map them cleanly by eager loading their subject details
        $todos = Todo::query()->where('user_id', Auth::id())
            ->with('subjectDetails')
            ->get()
            ->groupBy(function ($todo) {
                return $todo->subjectDetails->name
                    ?? $todo->subjectDetails->title
                    ?? $todo->subject;
            })
            ->map(function ($assignments) {
                return [
                    'pending' => $assignments->where('status', 'pending')->count(),
                    'done'    => $assignments->where('status', 'done')->count(),
                    'overdue' => $assignments->where('status', 'overdue')->count(),
                    'tasks'   => $assignments->where('status', 'pending')->pluck('title')->toArray()
                ];
            });

        return view('todo.schedule', compact('schedules', 'todos', 'subjects', 'rawSchedules'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id'   => 'required|exists:subjects,id',
            'type'         => 'required|string|max:50',
            'room'         => 'nullable|string|max:255',
            'day_of_week'  => 'required|string',
            'start_time'   => 'required',
            'end_time'     => 'required|after:start_time',
            'is_recurring' => 'nullable|boolean',
        ]);

        $validated['is_recurring'] = $request->has('is_recurring') ? (bool)$request->is_recurring : true;

        // Automatically attach the logged-in user's ID
        $validated['user_id'] = Auth::id();

        ClassSchedule::create($validated);

        return redirect()->route('schedule')->with('success', 'Schedule added successfully!');
    }

    public function update(Request $request, $id)
    {
        // Prevent IDOR by ensuring the schedule belongs to the auth user before updating
        $schedule = ClassSchedule::query()->where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'subject_id'   => 'required|exists:subjects,id',
            'room'         => 'nullable|string|max:255',
            'type'         => 'required|string|max:50',
            'day_of_week'  => 'required|string',
            'start_time'   => 'required',
            'end_time'     => 'required|after:start_time',
            'is_recurring' => 'nullable|boolean',
        ]);

        $validated['is_recurring'] = $request->has('is_recurring') ? (bool)$request->is_recurring : true;

        $schedule->update($validated);

        return redirect()->route('schedule')->with('success', 'Schedule updated successfully!');
    }

    public function destroy($id)
    {
        // Prevent IDOR by ensuring the schedule belongs to the auth user before deleting
        $schedule = ClassSchedule::query()->where('user_id', Auth::id())->findOrFail($id);

        $schedule->delete();

        return redirect()->route('schedule')->with('success', 'Schedule deleted successfully!');
    }
}
