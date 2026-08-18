<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssessmentController extends Controller
{
    public function index(Request $request)
    {
        $today = now()->toDateString();
        $userId = Auth::id(); // Utilizing custom auth logic

        // 1. The Daily Snapshot (Stat Cards)
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

        if ($request->ajax()) {
            return view('assessments.index', compact(
                'todayQuizzesCount',
                'todayExamsCount',
                'todayQuizzes',
                'quizzes',
                'exams',
                'subjects'
            ));
        }

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
        // Removed status and score from validation; user only inputs core details.
        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'subject_id'      => 'required|exists:subjects,id',
            'type'            => 'required|in:quiz,exam',
            'assessment_date' => 'required|date',
            'start_time'      => 'nullable',
            'room'            => 'nullable|string|max:255',
            'total_items'     => 'required|integer|min:1',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status']  = 'upcoming'; // Default state on creation
        $validated['score']   = null;       // Default score on creation

        Assessment::create($validated);

        return redirect()->route('assessments.index')->with('success', 'Assessment added successfully!');
    }

    public function markAsDone(Request $request, Assessment $assessment)
    {
        // Security check: ensure the user owns this assessment
        if ($assessment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Validate that the score is an integer and does not exceed the total items
        $validated = $request->validate([
            'score' => 'required|integer|min:0|max:' . $assessment->total_items,
        ]);

        $assessment->update([
            'score'  => $validated['score'],
            'status' => 'finished'
        ]);

        return redirect()->back()->with('success', 'Assessment marked as finished!');
    }
}
