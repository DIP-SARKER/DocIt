@extends('layouts.guest')

@section('title', 'Invalid Link | DocIt')

@section('content')

    <div class="card text-center error-page">
        <div class="card-body">
            <div class="error-icon shake">
                <i class="fas fa-unlink fa-4x text-warning"></i>
            </div>

            <div class="error-header mb-4">
                <h1 class="error-code">404</h1>
                <h2 class="error-title">Invalid Link</h2>
            </div>

            <div class="error-body mb-4">
                <p class="error-description mb-3">
                    The link you're trying to access doesn't exist or is malformed.
                    Possible reasons:
                </p>

                <ul class="error-list">
                    <li>
                        <i class="fas fa-exclamation-triangle text-warning"></i>
                        <span>Typo in the URL or missing characters</span>
                    </li>
                    <li>
                        <i class="fas fa-ban text-danger"></i>
                        <span>Link was never created or has been deleted</span>
                    </li>
                    <li>
                        <i class="fas fa-copy text-primary"></i>
                        <span>Copied incorrectly or truncated</span>
                    </li>
                    <li>
                        <i class="fas fa-server text-accent"></i>
                        <span>Temporary server issue (try refreshing)</span>
                    </li>
                </ul>

                <div class="error-note warning mt-4">
                    <div class="d-flex align-start gap-2">
                        <i class="fas fa-shield-alt"></i>
                        <div>
                            <p class="note-title">Security Tip:</p>
                            <p class="mb-0">
                                Be cautious of links from unknown sources. DocIt links always start with
                                <code>{{ config('app.public_url') }}/</code>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="error-actions">
                <a href="{{ config('app.public_url') }}" class="btn btn-outline btn-lg">
                    <i class="fas fa-file-alt"></i>
                    Visit DocIt
                </a>
            </div>
        </div>
    </div>
@endsection
