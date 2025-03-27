<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChallengeController extends Controller
{
    /**
     * Display a listing of challenges
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
        if (!Auth::user()->isTeacher()) {
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
        if (!Auth::user()->isTeacher()) {
            return redirect()->route('challenges.index')->with('error', 'Only teachers can create challenges');
        }

        $validated = $request->validate([
            'title' => 'required|max:255',
            'hint' => 'required',
            'challenge_file' => 'required|file|mimes:txt|max:2048', // 2MB max text file
        ]);
        
        // Store file with its original name (without spaces and diacritics)
        $path = $request->file('challenge_file')->store('challenges');

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
     * Handle a challenge solution attempt
     */
    public function solve(Request $request, Challenge $challenge)
    {
        $validated = $request->validate([
            'answer' => 'required',
        ]);

        $answer = $validated['answer'];
        $expectedAnswer = pathinfo(basename($challenge->file_path), PATHINFO_FILENAME);

        if ($answer === $expectedAnswer) {
            $content = Storage::get($challenge->file_path);
            return redirect()->back()->with(['success' => 'Correct answer!', 'content' => $content]);
        } else {
            return redirect()->back()->with('error', 'Incorrect answer. Try again.');
        }
    }

    /**
     * Remove the specified challenge
     */
    public function destroy(Challenge $challenge)
    {
        // Only teachers can delete challenges
        if (!Auth::user()->isTeacher()) {
            return redirect()->route('challenges.index')->with('error', 'Only teachers can delete challenges');
        }
        
        // Delete file
        Storage::delete($challenge->file_path);
        
        $challenge->delete();
        
        return redirect()->route('challenges.index')->with('success', 'Challenge deleted successfully');
    }
}
