@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 mb-4">
            <h2>Dashboard</h2>
            <p class="lead">Welcome back, {{ $user->fullname }}!</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Users</h5>
                            <h2 class="mb-0">{{ $userCount }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-users fa-3x"></i>
                        </div>
                    </div>
                    <a href="{{ route('users.index') }}" class="btn btn-light btn-sm mt-3">View Users</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Assignments</h5>
                            <h2 class="mb-0">{{ $assignmentCount }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-book fa-3x"></i>
                        </div>
                    </div>
                    <a href="{{ route('assignments.index') }}" class="btn btn-light btn-sm mt-3">View Assignments</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Challenges</h5>
                            <h2 class="mb-0">{{ $challengeCount }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-puzzle-piece fa-3x"></i>
                        </div>
                    </div>
                    <a href="{{ route('challenges.index') }}" class="btn btn-light btn-sm mt-3">View Challenges</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @if($user->isTeacher())
                            <a href="{{ route('users.create') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-user-plus me-2"></i> Add New User
                            </a>
                            <a href="{{ route('assignments.create') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-file-upload me-2"></i> Create New Assignment
                            </a>
                            <a href="{{ route('challenges.create') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-plus-circle me-2"></i> Create New Challenge
                            </a>
                        @else
                            <a href="{{ route('assignments.index') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-download me-2"></i> View Assignments
                            </a>
                            <a href="{{ route('challenges.index') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-puzzle-piece me-2"></i> View Challenges
                            </a>
                            <a href="{{ route('users.show', $user->id) }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-user-edit me-2"></i> Edit Your Profile
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">System Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Your Role:</strong> {{ ucfirst($user->role) }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Last Login:</strong> {{ now()->format('Y-m-d H:i:s') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
