@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span>Challenges</span>
                    @if(Auth::user()->isTeacher())
                        <a href="{{ route('challenges.create') }}" class="btn btn-sm btn-light">
                            <i class="fas fa-plus"></i> Create Challenge
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
                                    <th>Hint</th>
                                    <th>Created By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($challenges as $challenge)
                                    <tr>
                                        <td>{{ $challenge->title }}</td>
                                        <td>{{ Str::limit($challenge->hint, 50) }}</td>
                                        <td>{{ $challenge->teacher->fullname }}</td>
                                        <td>
                                            <a href="{{ route('challenges.show', $challenge->id) }}" class="btn btn-sm btn-info">
                                                Solve
                                            </a>
                                            @if(Auth::user()->isTeacher() && Auth::id() === $challenge->teacher_id)
                                                <form action="{{ route('challenges.destroy', $challenge->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this challenge?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No challenges available</td>
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
