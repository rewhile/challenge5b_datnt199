@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span>Assignments</span>
                    @if(Auth::user()->isTeacher())
                        <a href="{{ route('assignments.create') }}" class="btn btn-sm btn-light">
                            <i class="fas fa-plus"></i> Create Assignment
                        </a>
                    @endif
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
                    
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Due Date</th>
                                    <th>Teacher</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($assignments as $assignment)
                                    <tr>
                                        <td>{{ $assignment->title }}</td>
                                        <td>{{ Str::limit($assignment->description, 50) }}</td>
                                        <td>
                                            @if($assignment->due_date && !is_null($assignment->due_date))
                                                {{ \Carbon\Carbon::parse($assignment->due_date)->format('M d, Y') }}
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
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No assignments available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
