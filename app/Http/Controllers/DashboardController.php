<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Assignment;
use App\Models\Challenge;

class DashboardController extends Controller
{
    /**
     * Display the user's dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $userCount = User::count();
        $assignmentCount = Assignment::count();
        $challengeCount = Challenge::count();
        
        return view('dashboard', compact('user', 'userCount', 'assignmentCount', 'challengeCount'));
    }
}
