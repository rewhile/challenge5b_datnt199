@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- User Profile Card -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span>User Profile</span>
                    <div>
                        @if(Auth::id() == $user->id || Auth::user()->role == 'teacher')
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-light">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        @endif
                        @if(Auth::user()->role == 'teacher')
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-4 text-md-end fw-bold">Username:</div>
                        <div class="col-md-8">{{ $user->username }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-md-end fw-bold">Full Name:</div>
                        <div class="col-md-8">{{ $user->fullname }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-md-end fw-bold">Email:</div>
                        <div class="col-md-8">{{ $user->email }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-md-end fw-bold">Phone:</div>
                        <div class="col-md-8">{{ $user->phone }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-md-end fw-bold">Role:</div>
                        <div class="col-md-8">
                            <span class="badge {{ $user->role == 'teacher' ? 'bg-danger' : 'bg-primary' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Messages Card -->
            <div class="card">
                <div class="card-header bg-info text-white">Messages</div>
                <div class="card-body">
                    <!-- List messages -->
                    @if(isset($user->receivedMessages) && $user->receivedMessages->count() > 0)
                        <div class="messages-list mb-4">
                            @foreach($user->receivedMessages as $message)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <p>{{ $message->content }}</p>
                                        <div class="d-flex justify-content-between">
                                            <small class="text-muted">From: {{ $message->sender->username }} on {{ $message->created_at->format('M d, Y H:i') }}</small>
                                            
                                            @if(Auth::id() == $message->from_user_id)
                                                <div>
                                                    <a href="{{ route('messages.edit', $message->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                                    <form action="{{ route('messages.destroy', $message->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No messages yet.</p>
                    @endif

                    <!-- Message form -->
                    <form action="{{ route('messages.store', $user->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="content" class="form-label">Leave a message for {{ $user->fullname }}</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="3" required></textarea>
                            @error('content')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
