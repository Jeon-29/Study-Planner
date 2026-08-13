<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SubjectController extends Controller
{
    /**
     * Display a listing of the user's subjects based on term filters.
     */
    public function index(Request $request)
    {
        // Get the current filter from the URL, defaulting to 'all' if none exists
        $currentFilter = $request->query('filter', 'all');

        // Build the query using Eloquent's conditional 'when' statements
        $subjects = Auth::user()->subjects()
            ->withCount('todos')
            ->when($currentFilter === '1st-sem', function ($query) {
                $query->where('semester', '1st Sem')->where('is_archived', false);
            })
            ->when($currentFilter === '2nd-sem', function ($query) {
                $query->where('semester', '2nd Sem')->where('is_archived', false);
            })
            ->when($currentFilter === 'archived', function ($query) {
                $query->where('is_archived', true);
            })
            ->when($currentFilter === 'all', function ($query) {
                // For 'all', we just want to hide archived subjects
                $query->where('is_archived', false);
            })
            ->orderBy('code', 'asc')
            ->get();

        // Pass both the subjects and the active filter to the Blade view
        return view('subject.index', compact('subjects', 'currentFilter'));
    }

    /**
     * Display the specified subject's interactive details view (tasks, instructor info, resources).
     */
    public function show($id)
    {
        $subject = Subject::with('todos')->findOrFail($id);

        // Prevent cross-user data tampering
        abort_if($subject->user_id !== Auth::id(), 403);

        return view('subject.show', compact('subject'));
    }

    /**
     * Store a newly created subject in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:subjects,code,NULL,id,user_id,'.Auth::id(),
            'name' => 'required|string|max:150',
            'semester' => 'required|string|in:1st Sem,2nd Sem',
            'color_theme' => 'nullable|string|max:7',
            'instructor_name' => 'nullable|string|max:255',
            'instructor_email' => 'nullable|email|max:255',
            'consultation_hours' => 'nullable|string|max:255',
        ], [
            'code.unique' => 'You have already added this subject code to your tracker!',
        ]);

        Auth::user()->subjects()->create([
            'code' => strtoupper($validated['code']),
            'name' => $validated['name'],
            'semester' => $validated['semester'],
            'color_theme' => $validated['color_theme'] ?? '#64748b',
            'instructor_name' => $validated['instructor_name'] ?? null,
            'instructor_email' => $validated['instructor_email'] ?? null,
            'consultation_hours' => $validated['consultation_hours'] ?? null,
        ]);

        return redirect()->route('subject.index')->with('success', 'Subject registered successfully!');
    }

    public function downloadFile($id)
    {
        $file = File::findOrFail($id);

        // Check if file exists on S3/Backblaze and trigger browser download
        if (Storage::disk('s3')->exists($file->path)) {
            return Storage::disk('s3')->download($file->path, $file->filename);
        }

        return back()->with('error', 'File not found on cloud storage.');
    }

    /**
     * Update the specified subject profile using standard ID tracking.
     */
    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);

        // Prevent cross-user data tampering
        abort_if($subject->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:subjects,code,'.$id.',id,user_id,'.Auth::id(),
            'name' => 'required|string|max:150',
            'semester' => 'required|string|in:1st Sem,2nd Sem',
            'color_theme' => 'required|string|max:7',
            'instructor_name' => 'nullable|string|max:255',
            'instructor_email' => 'nullable|email|max:255',
            'consultation_hours' => 'nullable|string|max:255',
        ]);

        $subject->update([
            'code' => strtoupper($validated['code']),
            'name' => $validated['name'],
            'semester' => $validated['semester'],
            'color_theme' => $validated['color_theme'],
            'instructor_name' => $validated['instructor_name'] ?? null,
            'instructor_email' => $validated['instructor_email'] ?? null,
            'consultation_hours' => $validated['consultation_hours'] ?? null,
        ]);

        return redirect()->route('subject.index')->with('success', 'Subject updated successfully!');
    }

    /**
     * Remove the specified subject and its linked tasks from storage.
     */
    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);

        // Prevent cross-user data tampering
        abort_if($subject->user_id !== Auth::id(), 403);

        $subject->delete();

        return redirect()->route('subject.index')->with('success', 'Subject deleted successfully!');
    }

    /**
     * Bypassed page redirect handler (Modal implementation is on index view instead)
     */
    public function edit(Subject $subject)
    {
        abort(404);
    }

    /**
     * Toggle the archived status of the specified subject.
     */
    public function toggleArchive($id)
    {
        $subject = Subject::findOrFail($id);

        // Prevent cross-user data tampering
        abort_if($subject->user_id !== Auth::id(), 403);

        $subject->update([
            'is_archived' => ! $subject->is_archived,
        ]);

        $status = $subject->is_archived ? 'archived' : 'restored';

        return redirect()->route('subject.index')->with('success', "Subject {$status} successfully!");
    }

    public function storeFile(Request $request, $id)
    {
        // Validate the incoming input
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx|max:10240', // Max 10MB
        ]);

        $subject = Subject::findOrFail($id);

        if ($request->hasFile('file')) {
            $uploadedFile = $request->file('file');

            // Generate a unique filename and store it in the public storage
            $originalName = $uploadedFile->getClientOriginalName();
            $path = $uploadedFile->store('subject-files', 's3');

            // Create the database record linked to this subject
            File::create([
                'subject_id' => $subject->id,
                'title' => $request->title,
                'category' => $request->category,
                'path' => $path,
                'filename' => $originalName,
            ]);
        }

        return redirect()->route('subject.show', ['id' => $subject->id, 'tab' => 'files'])
            ->with('success', 'File uploaded successfully!');
    }

    public function destroyFile($id)
    {
        $file = File::findOrFail($id);
        $subjectId = $file->subject_id;

        // Delete the actual physical file from storage safely without exists() check
        if (! empty($file->path) && is_string($file->path) && $file->path !== '0') {
            try {
                Storage::disk('s3')->delete($file->path);
            } catch (\Exception $e) {
                // Bypass storage errors if the file doesn't exist
            }
        }

        // Delete the database record
        $file->delete();

        return redirect()->route('subject.show', ['id' => $subjectId, 'tab' => 'files'])
            ->with('success', 'File deleted successfully!');
    }
}
