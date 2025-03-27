@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">Submit Assignment: {{ $assignment->title }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('submissions.store', $assignment->id) }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="comment" class="form-label">Comment (optional)</label>
                            <textarea class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment" rows="4">{{ old('comment') }}</textarea>
                            @error('comment')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="submission_file" class="form-label">Your Submission</label>
                            <input type="file" class="form-control @error('submission_file') is-invalid @enderror" id="submission_file" name="submission_file" required>
                            @error('submission_file')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Submit Assignment</button>
                            <a href="{{ route('assignments.show', $assignment->id) }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
