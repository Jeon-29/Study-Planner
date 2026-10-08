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

        // 1. Upcoming Assessment Snapshot (Stat Cards)
        $upcomingQuizzesCount = Assessment::where('user_id', $userId)
            ->where('type', 'quiz')
            ->where('status', 'upcoming')
            ->count();

        $upcomingExamsCount = Assessment::where('user_id', $userId)
            ->where('type', 'exam')
            ->where('status', 'upcoming')
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
                'upcomingQuizzesCount',
                'upcomingExamsCount',
                'todayQuizzes',
                'quizzes',
                'exams',
                'subjects'
            ));
        }

        return view('assessments.index', compact(
            'upcomingQuizzesCount',
            'upcomingExamsCount',
            'todayQuizzes',
            'quizzes',
            'exams',
            'subjects'
        ));
    }

    public function store(Request $request)
    {
        // Removed status and score from validation; user only inputs core details.[cite: 13]
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
        $validated['status']  = 'upcoming'; // Default state on creation[cite: 13]
        $validated['score']   = null;       // Default score on creation[cite: 13]

        Assessment::create($validated);

        return redirect()->route('assessments.index')->with('success', 'Assessment added successfully!');
    }

    public function markAsDone(Request $request, Assessment $assessment)
    {
        // Security check: ensure the user owns this assessment[cite: 13]
        if ($assessment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Validate that the score is an integer and does not exceed the total items[cite: 13]
        $validated = $request->validate([
            'score' => 'required|integer|min:0|max:' . $assessment->total_items,
        ]);

        $assessment->update([
            'score'  => $validated['score'],
            'status' => 'finished'
        ]);

        return redirect()->back()->with('success', 'Assessment marked as finished!');
    }

    public function destroy(Assessment $assessment)
    {
        $assessment->delete();

        return redirect()->back()->with('success', 'Assessment deleted successfully!');
    }
}
