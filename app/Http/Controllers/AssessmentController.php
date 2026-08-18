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

        // 1. Fetching Collections for the view and counts for stat cards
        $todayQuizzesCollection = Assessment::with('subject')
            ->where('user_id', $userId)
            ->where('type', 'quiz')
            ->whereDate('assessment_date', $today)
            ->get();

        $todayExamsCollection = Assessment::with('subject')
            ->where('user_id', $userId)
            ->where('type', 'exam')
            ->whereDate('assessment_date', $today)
            ->get();

        // Numeric counts for stat widgets if needed separately
        $todayQuizzes = $todayQuizzesCollection->count();
        $todayExams = $todayExamsCollection->count();

        // 2. Fetching & Grouping Quizzes
        // We eager load 'subject' to avoid the N+1 query problem
        $quizzes = Assessment::with('subject')
            ->where('user_id', $userId)
            ->where('type', 'quiz')
            ->orderBy('assessment_date', 'asc')
            ->get()
            ->groupBy('status'); // Automatically groups into 'upcoming', 'finished', etc.

        // 3. Fetching & Grouping Exams
        $exams = Assessment::with('subject')
            ->where('user_id', $userId)
            ->where('type', 'exam')
            ->orderBy('assessment_date', 'asc')
            ->get()
            ->groupBy('status');


        $subjects = \App\Models\Subject::where('user_id', Auth::id())->get();

        // Fixed folder path from 'assessments.index' to 'assessment.index' to match your view directory
        return view('assessments.index', compact('todayQuizzes', 'todayExams', 'quizzes', 'exams', 'subjects'));
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
