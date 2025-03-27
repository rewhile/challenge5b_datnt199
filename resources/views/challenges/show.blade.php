@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span>Challenge: {{ $challenge->title }}</span>
                    <a href="{{ route('challenges.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Challenges
                    </a>
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
                    @if (session('content'))
                        <div class="alert alert-info" role="alert">
                            <pre>{{ session('content') }}</pre>
                        </div>
                    @endif
                    
                    <div class="mb-4">
                        <h5>Hint:</h5>
                        <p class="lead">{{ $challenge->hint }}</p>
                    </div>
                    
                    <div class="mt-4">
                        <form method="POST" action="{{ route('challenges.solve', $challenge->id) }}">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="answer" class="form-label">Your Answer:</label>
                                <input type="text" class="form-control @error('answer') is-invalid @enderror" id="answer" name="answer" value="{{ old('answer') }}" required>
                                @error('answer')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Submit Answer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
