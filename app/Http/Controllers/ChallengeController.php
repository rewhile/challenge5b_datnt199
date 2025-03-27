<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChallengeController extends Controller
{
    /**
     * Display a listing of the challenges
     */
    public function index()
    {
        $challenges = Challenge::with('teacher')->get();
        return view('challenges.index', compact('challenges'));
    }

    /**
     * Show the form for creating a new challenge
     */
    public function create()
    {
        // Only teachers can create challenges
        if (Auth::user()->role !== 'teacher') {
            return redirect()->route('challenges.index')->with('error', 'Only teachers can create challenges');
        }
        
        return view('challenges.create');
    }

    /**
     * Store a newly created challenge
     */
    public function store(Request $request)
    {
        // Only teachers can create challenges
        if (Auth::user()->role !== 'teacher') {
            return redirect()->route('challenges.index')->with('error', 'Only teachers can create challenges');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'hint' => 'required|string',
            'challenge_file' => 'required|file|mimes:txt|max:2048',
        ]);

        // Get the original filename (answer)
        $fileName = pathinfo($request->file('challenge_file')->getClientOriginalName(), PATHINFO_FILENAME);
        
        // Store file with the original name
        $path = $request->file('challenge_file')->storeAs('challenges', $fileName . '.txt');
        
        Challenge::create([
            'title' => $validated['title'],
            'hint' => $validated['hint'],
            'file_path' => $path,
            'teacher_id' => Auth::id(),
        ]);
        
        return redirect()->route('challenges.index')->with('success', 'Challenge created successfully');
    }

    /**
     * Display the specified challenge
     */
    public function show(Challenge $challenge)
    {
        return view('challenges.show', compact('challenge'));
    }

    /**
     * Attempt to solve the challenge
     */
    public function solve(Request $request, Challenge $challenge)
    {
        $validated = $request->validate([
            'answer' => 'required|string',
        ]);

        // Get the correct answer from the filename
        $correctAnswer = pathinfo($challenge->file_path, PATHINFO_FILENAME);
        
        // Compare with the provided answer (case-insensitive)
        if (strcasecmp(trim($validated['answer']), $correctAnswer) === 0) {
            // If correct, read the content of the file
            $content = Storage::get($challenge->file_path);
            
            return redirect()->back()->with([
                'success' => 'Correct answer! Here is the content:',
                'content' => $content,
            ]);
        }
        
        return redirect()->back()->with('error', 'Incorrect answer. Try again.');
    }

    /**
     * Remove the specified challenge
     */
    public function destroy(Challenge $challenge)
    {
        // Only the teacher who created the challenge can delete it
        if (Auth::id() !== $challenge->teacher_id) {
            return redirect()->route('challenges.index')->with('error', 'You can only delete your own challenges');
        }
        
        // Delete the file
        Storage::delete($challenge->file_path);
        
        $challenge->delete();
        
        return redirect()->route('challenges.index')->with('success', 'Challenge deleted successfully');
    }
}
