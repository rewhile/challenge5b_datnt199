<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    /**
     * Display a listing of assignments
     */
    public function index()
    {
        $assignments = Assignment::with('teacher')->latest()->get();
        return view('assignments.index', compact('assignments'));
    }

    /**
     * Show the form for creating a new assignment
     */
    public function create()
    {
        // Only teachers can create assignments
        if (!Auth::user()->isTeacher()) {
            return redirect()->route('assignments.index')->with('error', 'Only teachers can create assignments');
        }
        
        return view('assignments.create');
    }

    /**
     * Store a newly created assignment
     */
    public function store(Request $request)
    {
        // Only teachers can create assignments
        if (!Auth::user()->isTeacher()) {
            return redirect()->route('assignments.index')->with('error', 'Only teachers can create assignments');
        }

        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'assignment_file' => 'required|file|max:10240', // 10MB max file size
            'due_date' => 'nullable|date',
        ]);
        
        $path = $request->file('assignment_file')->store('assignments');
        
        Assignment::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'file_path' => $path,
            'due_date' => $validated['due_date'] ?? null,
            'teacher_id' => Auth::id(),
        ]);
        
        return redirect()->route('assignments.index')->with('success', 'Assignment created successfully');
    }

    /**
     * Display the specified assignment
     */
    public function show(Assignment $assignment)
    {
        // Load submissions if user is a teacher
        if (Auth::user()->isTeacher()) {
            $assignment->load('submissions.student');
        }
        
        return view('assignments.show', compact('assignment'));
    }

    /**
     * Download the assignment file
     */
    public function download(Assignment $assignment)
    {
        return Storage::download($assignment->file_path);
    }

    /**
     * Show the form for editing the specified assignment
     */
    public function edit(Assignment $assignment)
    {
        // Only the teacher who created the assignment can edit it
        if (Auth::id() !== $assignment->teacher_id) {
            return redirect()->route('assignments.index')->with('error', 'You can only edit your own assignments');
        }
        
        return view('assignments.edit', compact('assignment'));
    }

    /**
     * Update the specified assignment
     */
    public function update(Request $request, Assignment $assignment)
    {
        // Only the teacher who created the assignment can update it
        if (Auth::id() !== $assignment->teacher_id) {
            return redirect()->route('assignments.index')->with('error', 'You can only update your own assignments');
        }
        
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'assignment_file' => 'nullable|file|max:10240', // 10MB max file size
            'due_date' => 'nullable|date',
        ]);
        
        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'due_date' => $validated['due_date'] ?? null,
        ];
        
        // Update file if a new one is uploaded
        if ($request->hasFile('assignment_file')) {
            // Delete the old file
            Storage::delete($assignment->file_path);
            
            // Store the new file
            $path = $request->file('assignment_file')->store('assignments');
            $data['file_path'] = $path;
        }
        
        $assignment->update($data);
        
        return redirect()->route('assignments.show', $assignment)->with('success', 'Assignment updated successfully');
    }

    /**
     * Remove the specified assignment
     */
    public function destroy(Assignment $assignment)
    {
        // Only the teacher who created the assignment can delete it
        if (Auth::id() !== $assignment->teacher_id) {
            return redirect()->route('assignments.index')->with('error', 'You can only delete your own assignments');
        }
        
        // Delete the file
        Storage::delete($assignment->file_path);
        
        // Delete all submissions
        foreach ($assignment->submissions as $submission) {
            Storage::delete($submission->file_path);
        }
        
        $assignment->delete();
        
        return redirect()->route('assignments.index')->with('success', 'Assignment deleted successfully');
    }
}
