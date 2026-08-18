<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssessmentController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();
        $userId = Auth::id(); // Using our custom auth setup

        // 1. The Daily Snapshot (Stat Cards - renamed to avoid collision)
        $todayQuizzesCount = Assessment::where('user_id', $userId)
            ->where('type', 'quiz')
            ->whereDate('assessment_date', $today)
            ->count();

        $todayExamsCount = Assessment::where('user_id', $userId)
            ->where('type', 'exam')
            ->whereDate('assessment_date', $today)
            ->count();

        // 2. Actual collection of today's quizzes for listing/iteration
        $todayQuizzes = Assessment::with('subject')
            ->where('user_id', $userId)
            ->where('type', 'quiz')
            ->whereDate('assessment_date', $today)
            ->get();

        // 3. Fetching & Grouping Quizzes (All)
        $quizzes = Assessment::with('subject')
            ->where('user_id', $userId)
            ->where('type', 'quiz')
            ->orderBy('assessment_date', 'asc')
            ->get()
            ->groupBy('status');

        // 4. Fetching & Grouping Exams (All)
        $exams = Assessment::with('subject')
            ->where('user_id', $userId)
            ->where('type', 'exam')
            ->orderBy('assessment_date', 'asc')
            ->get()
            ->groupBy('status');

        $subjects = \App\Models\Subject::where('user_id', Auth::id())->get();

        return view('assessments.index', compact(
            'todayQuizzesCount',
            'todayExamsCount',
            'todayQuizzes',
            'quizzes',
            'exams',
            'subjects'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'subject_id'      => 'required|exists:subjects,id',
            'type'            => 'required|in:quiz,exam',
            'status'          => 'required|in:upcoming,finished,overdue',
            'assessment_date' => 'required|date',
            'start_time'      => 'nullable',
            'room'            => 'nullable|string|max:255',
            'total_items'     => 'required|integer|min:1',
            'score'           => 'nullable|integer|min:0',
        ]);

        $validated['user_id'] = Auth::id();

        Assessment::create($validated);

        return redirect()->route('assessments.index')->with('success', 'Assessment added successfully!');
    }
}
