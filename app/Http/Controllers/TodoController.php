<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\ClassSchedule;

class TodoController extends Controller
{
    public function index()
    {
        // 1. Fetch the logged-in user's tasks ordered by upcoming deadline
        $todos = Auth::user()->todos()->orderBy('due_date', 'asc')->orderBy('due_time', 'asc')->get();

        // 2. Fetch the user's custom subject color themes mapping [ 'COURSE_CODE' => 'color_name' ]
        $subjectColors = Auth::user()->subjects()
            ->pluck('color_theme', 'code')
            ->toArray();

        // 3. Dynamically partition tasks based on real-time deadlines before sending to the view
        $now = Carbon::now();

        $pendingTodos = $todos->filter(function ($todo) use ($now) {
            if ($todo->status === 'done') return false;

            // Combine date and time to check if deadline is still in the future
            $dueTimestamp = Carbon::parse($todo->due_date . ' ' . ($todo->due_time ?? '00:00:00'));
            return $dueTimestamp->isFuture() || $dueTimestamp->isToday();
        });

        $overdueTodos = $todos->filter(function ($todo) use ($now) {
            if ($todo->status === 'done') return false;

            $dueTimestamp = Carbon::parse($todo->due_date . ' ' . ($todo->due_time ?? '00:00:00'));
            // It's overdue if the combined deadline timestamp has strictly passed
            return $dueTimestamp->isPast() && !$dueTimestamp->isToday();
        });

        $doneTodos = $todos->where('status', 'done');

        // Pass $subjectColors directly into the compact array for your Blade loops to read
        return view('todo.index', compact('pendingTodos', 'overdueTodos', 'doneTodos', 'subjectColors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            // Dynamically verifies the code exists in your subjects table
            'subject'     => 'required|string|exists:subjects,code',
            'description' => 'nullable|string',
            'due_date'    => 'required|date|after_or_equal:today',
            'due_time'    => 'required',
            'priority'    => 'required|in:low,medium,high',
        ]);

        // Calculate initial state dynamically based on user input
        $dueTimestamp = Carbon::parse($validated['due_date'] . ' ' . $validated['due_time']);
        $status = $dueTimestamp->isPast() ? 'overdue' : 'pending';

        Todo::create([
            'user_id'     => Auth::id(),
            'title'       => $validated['title'],
            'subject'     => $validated['subject'],
            'description' => $validated['description'],
            'due_date'    => $validated['due_date'],
            'due_time'    => $validated['due_time'],
            'priority'    => $validated['priority'],
            'status'      => $status,
        ]);

        return redirect()->route('todo.index')->with('success', 'Assignment added successfully!');
    }

    public function update(Request $request, Todo $todo)
    {
        abort_if($todo->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            // Refactored to dynamic rule matching your store method structure
            'subject'     => 'required|string|exists:subjects,code',
            'description' => 'nullable|string',
            'due_date'    => 'required|date',
            'due_time'    => 'required',
            'priority'    => 'required|in:low,medium,high',
        ]);

        // Keep status sync clean if the task isn't already finished
        if ($todo->status !== 'done') {
            $dueTimestamp = Carbon::parse($validated['due_date'] . ' ' . $validated['due_time']);
            $validated['status'] = ($dueTimestamp->isPast() && !$dueTimestamp->isToday()) ? 'overdue' : 'pending';
        }

        $todo->update($validated);

        return redirect()->route('todo.index')->with('success', 'Assignment updated successfully!');
    }

    public function destroy(Todo $todo)
    {
        abort_if($todo->user_id !== Auth::id(), 403);

        $todo->delete();

        return redirect()->route('todo.index')->with('success', 'Assignment deleted.');
    }

    public function complete(Todo $todo)
    {
        abort_if($todo->user_id !== Auth::id(), 403);

        $todo->update(['status' => 'done']);

        return redirect()->back()->with('success', 'Assignment completed!');
    }

    public function schedule()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $weekDays = CarbonPeriod::create($startOfWeek, $endOfWeek);

        // 1. Fetch Class Schedules for the week
        $classes = ClassSchedule::with('subject')
            ->whereIn('day_of_week', $weekDays->map(fn($d) => $d->format('l'))->toArray())
            ->get();

        // 2. Fetch User's Subject Colors for consistency
        $subjectColors = Auth::user()->subjects()->pluck('color_theme', 'code')->toArray();

        // 3. Get pending task counts per subject for the modal feature
        $pendingCounts = Auth::user()->todos()
            ->where('status', 'pending')
            ->get()
            ->groupBy('subject')
            ->map->count();

        return view('todo.schedule', compact('weekDays', 'classes', 'subjectColors', 'pendingCounts'));
    }
}
