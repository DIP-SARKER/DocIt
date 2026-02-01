<!-- Shortener Form -->
<div class="card mb-4 hidden" id="addUrl">
    <div class="d-flex justify-between align-center mb-3">
        <h3 class="card-title">Shorten a URL</h3>
    </div>
    <form id="shortenerForm" method="POST" action="{{ route('shortlinks.store') }}">
        @csrf

        <div class="form-group">
            <label for="linkTitle" class="form-label">Title</label>
            <input type="text" id="linkTitle" name="title" class="form-control"
                placeholder="Set a title for your link" required>
        </div>

        <div class="form-group">
            <label for="longUrl" class="form-label">Long URL</label>
            <input type="url" id="longUrl" name="long_url" class="form-control"
                placeholder="https://www.example.com/..." required>
        </div>

        <div class="form-group">
            <label for="customAlias" class="form-label">Custom Alias (Optional)</label>
            <div class="d-flex align-center gap-1">
                <span class="mr-2" style="color: var(--text-muted);">{{ config('app.public_url') }}/</span>
                <input type="text" id="customAlias" name="alias" class="form-control" placeholder="my-link">
            </div>
        </div>

        <div class="d-flex justify-between align-center mt-3">
            <div class="form-check">
                <input type="checkbox" id="trackClicks" name="track_clicks" value="1" class="form-check-input">
                <label for="trackClicks" class="form-check-label">Track clicks on this link</label>
            </div>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline" id="cancelUrlFormBtn">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-link"></i>
                    Shorten URL
                </button>
            </div>

        </div>
    </form>


    <!-- Result Section -->
    <div id="resultSection" class="mt-4 hidden">
        <h4 class="mb-2">Your Short URL</h4>
        <div class="short-url-item">
            <div class="short-url-info">
                <div class="short-url-result">
                    <span id="shortUrlResult">docit.app/abc123</span>
                    <span class="badge badge-accent" id="clickCount">0 clicks</span>
                </div>
                <div class="short-url-original" id="originalUrl">
                    https://www.example.com/very-long-url-that-needs-to-be-shortened</div>
            </div>
            <button class="btn btn-primary" id="copyShortUrlBtn">
                <i class="far fa-copy"></i>
                Copy
            </button>
        </div>
    </div>
</div>
