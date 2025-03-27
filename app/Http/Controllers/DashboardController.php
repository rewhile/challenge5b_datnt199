<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Assignment;
use App\Models\Challenge;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Display the dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        $stats = [
            'users' => User::count(),
            'assignments' => Assignment::count(),
            'challenges' => Challenge::count(),
        ];
        
        // Add teacher-specific stats
        if ($user->isTeacher()) {
            $stats['my_assignments'] = Assignment::where('teacher_id', $user->id)->count();
            $stats['my_challenges'] = Challenge::where('teacher_id', $user->id)->count();
            
            // Get recent assignment submissions
            $recentSubmissions = \App\Models\Submission::with(['student', 'assignment'])
                ->whereHas('assignment', function ($query) use ($user) {
                    $query->where('teacher_id', $user->id);
                })
                ->latest()
                ->take(5)
                ->get();
        } 
        // Add student-specific stats
        else {
            $stats['my_submissions'] = \App\Models\Submission::where('student_id', $user->id)->count();
            
            // Get recent assignments
            $recentAssignments = Assignment::latest()->take(5)->get();
        }
        
        return view('dashboard', [
            'user' => $user,
            'stats' => $stats,
            'recentSubmissions' => $recentSubmissions ?? null,
            'recentAssignments' => $recentAssignments ?? null,
        ]);
    }
}
