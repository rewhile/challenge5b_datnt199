@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary text-white">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h2 class="mb-4">Welcome, {{ Auth::user()->fullname }}!</h2>

                    <!-- Stats Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Users</h5>
                                    <p class="card-text display-6">{{ $stats['users'] }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Assignments</h5>
                                    <p class="card-text display-6">{{ $stats['assignments'] }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Challenges</h5>
                                    <p class="card-text display-6">{{ $stats['challenges'] }}</p>
                                </div>
                            </div>
                        </div>
                        
                        @if(Auth::user()->isTeacher())
                            <div class="col-md-3 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">My Assignments</h5>
                                        <p class="card-text display-6">{{ $stats['my_assignments'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-md-3 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">My Submissions</h5>
                                        <p class="card-text display-6">{{ $stats['my_submissions'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Quick Links -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4 class="mb-3">Quick Links</h4>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('users.index') }}" class="btn btn-outline-primary">Users</a>
                                <a href="{{ route('assignments.index') }}" class="btn btn-outline-primary">Assignments</a>
                                <a href="{{ route('challenges.index') }}" class="btn btn-outline-primary">Challenges</a>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    @if(Auth::user()->isTeacher() && $recentSubmissions && count($recentSubmissions) > 0)
                        <div class="row">
                            <div class="col-12">
                                <h4 class="mb-3">Recent Submissions</h4>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Assignment</th>
                                                <th>Student</th>
                                                <th>Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentSubmissions as $submission)
                                                <tr>
                                                    <td>{{ $submission->assignment->title }}</td>
                                                    <td>{{ $submission->student->fullname }}</td>
                                                    <td>{{ $submission->created_at->format('M d, Y H:i') }}</td>
                                                    <td>
                                                        <a href="{{ route('submissions.download', $submission->id) }}" class="btn btn-sm btn-primary">Download</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @elseif(Auth::user()->isStudent() && $recentAssignments && count($recentAssignments) > 0)
                        <div class="row">
                            <div class="col-12">
                                <h4 class="mb-3">Recent Assignments</h4>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Due Date</th>
                                                <th>Teacher</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentAssignments as $assignment)
                                                <tr>
                                                    <td>{{ $assignment->title }}</td>
                                                    <td>
                                                        @if($assignment->due_date)
                                                            {{ $assignment->due_date->format('M d, Y') }}
                                                        @else
                                                            No due date
                                                        @endif
                                                    </td>
                                                    <td>{{ $assignment->teacher->fullname }}</td>
                                                    <td>
                                                        <a href="{{ route('assignments.show', $assignment->id) }}" class="btn btn-sm btn-info">View</a>
                                                        <a href="{{ route('assignments.download', $assignment->id) }}" class="btn btn-sm btn-secondary">Download</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
