@extends('layouts.guest')

@section('title', 'Link Expired | DocIt')

@section('content')
    <div class="card text-center error-page">
        <div class="card-body">
            <div class="error-icon pulse rotation">
                <i class="fas fa-hourglass-end fa-4x text-accent"></i>
            </div>

            <div class="error-header mb-4">
                <h1 class="error-code">410</h1>
                <h2 class="error-title">Link Expired</h2>
            </div>

            <div class="error-body mb-4">
                <p class="error-description mb-3">
                    This link is no longer available. It may have been:
                </p>

                <ul class="error-list">
                    <li>
                        <i class="fas fa-clock text-warning"></i>
                        <span>Expired due to time limit</span>
                    </li>
                    <li>
                        <i class="fas fa-times-circle text-danger"></i>
                        <span>Manually deactivated by the creator</span>
                    </li>
                    <li>
                        <i class="fas fa-redo text-primary"></i>
                        <span>Replaced with a newer version</span>
                    </li>
                    <li>
                        <i class="fas fa-lock text-accent"></i>
                        <span>Set to single-use and already accessed</span>
                    </li>
                </ul>

                <div class="error-note mt-4">
                    <div class="d-flex align-start gap-2">
                        <i class="fas fa-info-circle"></i>
                        <div>
                            <p class="mb-0">
                                Links on DocIt can be set to expire after a certain time or
                                number of uses to ensure security and control over shared content.
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
