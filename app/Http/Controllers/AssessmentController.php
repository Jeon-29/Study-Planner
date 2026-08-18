<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // Ensure Carbon is imported

class AssessmentController extends Controller
{
    public function index(Request $request)
    {
        // Force the timezone to Philippine time to ensure "today" is accurate
        $today = Carbon::now('Asia/Manila')->toDateString();
        $userId = Auth::id();

        // 1. The Daily Snapshot (Stat Cards)
        $todayQuizzesCount = Assessment::where('user_id', $userId)
            ->where('type', 'quiz')
            ->whereDate('assessment_date', '=', $today)
            ->count();

        $todayExamsCount = Assessment::where('user_id', $userId)
            ->where('type', 'exam')
            ->whereDate('assessment_date', '=', $today)
            ->count();

        // 2. Fetching & Grouping Quizzes (All)
        $quizzes = Assessment::with('subject')
            ->where('user_id', $userId)
            ->where('type', 'quiz')
            ->orderBy('assessment_date', 'asc')
            ->get()
            ->groupBy('status');

        // 3. Fetching & Grouping Exams (All)
        $exams = Assessment::with('subject')
            ->where('user_id', $userId)
            ->where('type', 'exam')
            ->orderBy('assessment_date', 'asc')
            ->get()
            ->groupBy('status');

        $subjects = Subject::where('user_id', Auth::id())->get();

        return view('assessments.index', compact(
            'todayQuizzesCount',
            'todayExamsCount',
            'quizzes',
            'exams',
            'subjects'
        ));
    }

    public function store(Request $request)
    {
        // Removed status and score from validation; user only inputs core details.
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'type' => 'required|in:quiz,exam',
            'assessment_date' => 'required|date',
            'start_time' => 'nullable',
            'room' => 'nullable|string|max:255',
            'total_items' => 'required|integer|min:1',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'upcoming'; // Default state on creation
        $validated['score'] = null;       // Default score on creation

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
            'score' => 'required|integer|min:0|max:'.$assessment->total_items,
        ]);

        $assessment->update([
            'score' => $validated['score'],
            'status' => 'finished',
        ]);

        return redirect()->back()->with('success', 'Assessment marked as finished!');
    }

    public function destroy(Assessment $assessment)
    {
        $assessment->delete();

        return redirect()->back()->with('success', 'Exam/Quiz deleted successfully.');
    }
}
