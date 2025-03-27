@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span>Assignment: {{ $assignment->title }}</span>
                    <div>
                        @if(Auth::id() == $assignment->teacher_id)
                            <a href="{{ route('assignments.edit', $assignment->id) }}" class="btn btn-sm btn-light">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('assignments.destroy', $assignment->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this assignment?')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('assignments.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <div class="mb-4">
                        <h5>Description:</h5>
                        <p>{{ $assignment->description }}</p>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Due Date:</h5>
                        <p>
                            @if($assignment->due_date)
                                {{ $assignment->due_date->format('F d, Y') }}
                            @else
                                No due date set
                            @endif
                        </p>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Assignment File:</h5>
                        <a href="{{ route('assignments.download', $assignment->id) }}" class="btn btn-primary">
                            <i class="fas fa-download"></i> Download Assignment
                        </a>
                    </div>
                    
                    @if(Auth::user()->isStudent())
                        <div class="mt-4">
                            <a href="{{ route('submissions.create', $assignment->id) }}" class="btn btn-success">
                                <i class="fas fa-upload"></i> Submit Assignment
                            </a>
                        </div>
                    @endif
                    
                    @if(Auth::user()->isTeacher() && isset($assignment->submissions) && $assignment->submissions->count() > 0)
                        <div class="mt-5">
                            <h4>Submissions</h4>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Student</th>
                                            <th>Comment</th>
                                            <th>Submitted At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($assignment->submissions as $submission)
                                            <tr>
                                                <td>{{ $submission->student->fullname }}</td>
                                                <td>{{ Str::limit($submission->comment, 50) }}</td>
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
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
