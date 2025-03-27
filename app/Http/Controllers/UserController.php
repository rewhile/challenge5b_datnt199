<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the users
     */
    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        // Only teachers can create users
        if (!Auth::user()->isTeacher()) {
            return redirect()->route('users.index')->with('error', 'Only teachers can create users');
        }
        
        return view('users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        // Only teachers can create users
        if (!Auth::user()->isTeacher()) {
            return redirect()->route('users.index')->with('error', 'Only teachers can create users');
        }

        $validated = $request->validate([
            'username' => 'required|unique:users|max:255',
            'fullname' => 'required|max:255',
            'email' => 'required|email|unique:users|max:255',
            'phone' => 'nullable|max:20',
            'role' => 'required|in:teacher,student',
            'password' => 'required|min:8',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        
        User::create($validated);
        
        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        // Only allow editing yourself unless you're a teacher
        $currentUser = Auth::user();
        if ($currentUser->role !== 'teacher' && $currentUser->id !== $user->id) {
            return redirect()->route('users.index')->with('error', 'You are not authorized to edit that user.');
        }

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        // Only allow editing yourself unless you're a teacher
        $currentUser = Auth::user();
        if ($currentUser->role !== 'teacher' && $currentUser->id !== $user->id) {
            return redirect()->route('users.index')->with('error', 'You are not authorized to update that user.');
        }

        $validatedData = $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string',
            'password' => 'nullable|min:8',
        ]);

        // Don't update username or fullname for students editing themselves
        if ($currentUser->role === 'teacher' || $currentUser->id !== $user->id) {
            $validatedData = array_merge($validatedData, $request->validate([
                'username' => 'required|unique:users,username,' . $user->id,
                'fullname' => 'required|string',
                'role' => 'required|in:teacher,student',
            ]));
        }

        if (!empty($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        $user->update($validatedData);

        return redirect()->route('users.show', $user)
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // Only teachers can delete users
        if (Auth::user()->role !== 'teacher') {
            return redirect()->route('users.index')->with('error', 'You are not authorized to delete users.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}
