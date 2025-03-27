<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\ChallengeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // User routes
    Route::resource('users', UserController::class);
    
    // Message routes
    Route::post('/users/{user}/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{message}/edit', [MessageController::class, 'edit'])->name('messages.edit');
    Route::put('/messages/{message}', [MessageController::class, 'update'])->name('messages.update');
    Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
    
    // Assignment routes
    Route::resource('assignments', AssignmentController::class);
    Route::get('/assignments/{assignment}/download', [AssignmentController::class, 'download'])->name('assignments.download');
    
    // Submission routes
    Route::get('/assignments/{assignment}/submissions/create', [SubmissionController::class, 'create'])->name('submissions.create');
    Route::post('/assignments/{assignment}/submissions', [SubmissionController::class, 'store'])->name('submissions.store');
    Route::get('/submissions/{submission}/download', [SubmissionController::class, 'download'])->name('submissions.download');
    
    // Challenge routes
    Route::resource('challenges', ChallengeController::class)->except(['edit', 'update']);
    Route::post('/challenges/{challenge}/solve', [ChallengeController::class, 'solve'])->name('challenges.solve');
});
