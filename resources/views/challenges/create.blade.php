@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">Create Challenge</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('challenges.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Challenge Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="hint" class="form-label">Challenge Hint</label>
                            <textarea class="form-control @error('hint') is-invalid @enderror" id="hint" name="hint" rows="4" required>{{ old('hint') }}</textarea>
                            <small class="form-text text-muted">
                                Provide a hint that will help students solve the challenge.
                            </small>
                            @error('hint')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="challenge_file" class="form-label">Text File (poem, story, etc.)</label>
                            <input type="file" class="form-control @error('challenge_file') is-invalid @enderror" id="challenge_file" name="challenge_file" accept=".txt" required>
                            <small class="form-text text-muted">
                                The filename (without extension) will be the answer to the challenge. Make sure to use a descriptive name without spaces or accents.
                            </small>
                            @error('challenge_file')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Create Challenge</button>
                            <a href="{{ route('challenges.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
