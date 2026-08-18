<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AssessmentController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        Assessment::whereNull('user_id')->orWhere('user_id', '!=', $userId)->update(['user_id' => $userId]);

        $today = Carbon::now('Asia/Manila')->toDateString();

        $todayQuizzesCount = Assessment::where('user_id', $userId)
            ->where('type', 'quiz')
            ->whereDate('assessment_date', $today)
            ->count();

        $todayExamsCount = Assessment::where('user_id', $userId)
            ->where('type', 'exam')
            ->whereDate('assessment_date', $today)
            ->count();

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

        $subjects = Subject::where('user_id', $userId)->get();

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
        $validated['status'] = 'upcoming';
        $validated['score'] = null;

        Assessment::create($validated);

        return redirect()->route('assessments.index')->with('success', 'Assessment added successfully!');
    }

    public function markAsDone(Request $request, Assessment $assessment)
    {
        if ($assessment->user_id !== Auth::id()) {
            $assessment->user_id = Auth::id();
            $assessment->save();
        }

        $maxItems = (int) $assessment->total_items;

        $validated = $request->validate([
            'score' => 'required|integer|min:0|max:' . $maxItems,
        ]);

        // Reverted back to original update logic
        $assessment->update([
            'score' => $validated['score'],
            'status' => 'finished',
        ]);

        return redirect()->back()->with('success', 'Assessment marked as finished!');
    }

    public function destroy(Assessment $assessment)
    {
        if ($assessment->user_id !== Auth::id()) {
            $assessment->user_id = Auth::id();
            $assessment->save();
        }

        $assessment->delete();

        return redirect()->back()->with('success', 'Exam/Quiz deleted successfully.');
    }
}
