<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    /**
     * Show the form for creating a new submission
     */
    public function create(Assignment $assignment)
    {
        // Only students can create submissions
        if (!Auth::user()->isStudent()) {
            return redirect()->route('assignments.show', $assignment)->with('error', 'Only students can submit assignments');
        }

        return view('submissions.create', compact('assignment'));
    }

    /**
     * Store a newly created submission
     */
    public function store(Request $request, Assignment $assignment)
    {
        // Only students can create submissions
        if (!Auth::user()->isStudent()) {
            return redirect()->route('assignments.show', $assignment)->with('error', 'Only students can submit assignments');
        }

        $validated = $request->validate([
            'comment' => 'nullable',
            'submission_file' => 'required|file|max:10240', // 10MB max file size
        ]);

        $path = $request->file('submission_file')->store('submissions');

        Submission::create([
            'assignment_id' => $assignment->id,
            'student_id' => Auth::id(),
            'file_path' => $path,
            'comment' => $validated['comment'],
        ]);

        return redirect()->route('assignments.show', $assignment)->with('success', 'Submission created successfully');
    }

    /**
     * Download the submission file
     */
    public function download(Submission $submission)
    {
        // Only the teacher or the student who submitted can download
        if (!Auth::user()->isTeacher() && Auth::id() !== $submission->student_id) {
            return abort(403);
        }

        return Storage::download($submission->file_path);
    }
}
