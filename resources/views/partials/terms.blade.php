@extends('layouts.guest')

@section('title', 'Terms & Conditions - DocIt')

@section('content')
    <div class="container" style="max-width: 980px;">
        <!-- Back -->
        <a href="{{ url()->previous() }}" class="d-flex align-center gap-1 mb-3 mt-3" style="color: var(--text-muted);">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>

        <div class="card" style="background: var(--background)">
            <!-- Header -->
            <div class="d-flex justify-between align-center flex-wrap gap-2 mb-3">
                <div class="d-flex align-center gap-2">
                    <div class="navbar-brand" style="font-size: var(--font-size-2xl); margin: 0;">
                        <i class="fas fa-file-alt"></i> DocIt
                    </div>
                    <span class="badge badge-primary">Legal</span>
                </div>

                <span class="text-muted" style="font-size: var(--font-size-sm);">
                    <i class="far fa-clock"></i> Last updated: {{ now()->format('M d, Y') }}
                </span>
            </div>

            <h2 class="mb-1">Terms & Conditions</h2>
            <p class="text-muted mb-3">
                These Terms govern access to and use of DocIt. By creating an account or using the Service, you agree to
                these Terms.
            </p>

            <!-- Notice -->
            <div class="alert alert-info mb-4">
                <i class="fas fa-shield-alt"></i>
                <strong>Summary:</strong> DocIt stores links you provide. You’re responsible for the content you link to.
                We may restrict abusive usage and do not guarantee third-party availability.
            </div>

            <!-- Content -->
            <div class="grid gap-3">
                <div class="card">
                    <h3 class="mb-1">1. Definitions</h3>
                    <p class="text-muted mb-0">
                        <strong>“Service”</strong> means the DocIt website and features (document links and URL shortener).
                        <strong>“You”</strong> means the user of the Service.
                        <strong>“Content”</strong> means links, titles, descriptions, and related data you submit.
                    </p>
                </div>

                <div class="card">
                    <h3 class="mb-1">2. Eligibility & Account</h3>
                    <ul class="text-muted mb-0" style="padding-left: var(--space-lg);">
                        <li>You must provide accurate registration information and keep it updated.</li>
                        <li>You are responsible for safeguarding your password and account access.</li>
                        <li>You must not impersonate others or use a misleading username.</li>
                    </ul>
                </div>

                <div class="card">
                    <h3 class="mb-1">3. Your Content & Responsibilities</h3>
                    <ul class="text-muted mb-0" style="padding-left: var(--space-lg);">
                        <li>You own or control the rights to the links and metadata you submit.</li>
                        <li>You will not store or share links that are unlawful, harmful, abusive, or deceptive.</li>
                        <li>You will not use the Service for phishing, malware distribution, spam, or harassment.</li>
                        <li>You are responsible for any consequences of sharing a link publicly.</li>
                    </ul>
                </div>

                <div class="card">
                    <h3 class="mb-1">4. Document Links (Locked vs Public)</h3>
                    <p class="text-muted mb-2">
                        DocIt stores document links and related metadata. DocIt does not host your files.
                    </p>
                    <ul class="text-muted mb-0" style="padding-left: var(--space-lg);">
                        <li>Documents marked <strong>Locked</strong> are intended for private access.</li>
                        <li>Only <strong>unlocked</strong> documents may be shown on public profile pages.</li>
                        <li>Because links point to third-party services, access may depend on those services’ rules.</li>
                    </ul>
                </div>

                <div class="card">
                    <h3 class="mb-1">5. URL Shortener</h3>
                    <ul class="text-muted mb-0" style="padding-left: var(--space-lg);">
                        <li>Short links redirect to the destination (“long”) URL you provide.</li>
                        <li>If click tracking is enabled, we store visit counts for your short links.</li>
                        <li>We may disable, remove, or block short links that violate these Terms or applicable laws.</li>
                        <li>Custom aliases may be restricted to avoid conflicts with system routes or reserved words.</li>
                    </ul>
                </div>

                <div class="card">
                    <h3 class="mb-1">6. Prohibited Activities</h3>
                    <ul class="text-muted mb-0" style="padding-left: var(--space-lg);">
                        <li>Reverse engineering, scraping, or attempting to bypass security features.</li>
                        <li>Automated requests that overload the Service.</li>
                        <li>Uploading or sharing malicious content, including malware or exploit links.</li>
                        <li>Using DocIt to distribute copyrighted content without permission.</li>
                    </ul>
                </div>

                <div class="card">
                    <h3 class="mb-1">7. Privacy & Data</h3>
                    <p class="text-muted mb-0">
                        We process account information and your submitted Content to provide the Service. Passwords are
                        stored using secure hashing. If click tracking is enabled, we store aggregated counts (not a
                        guarantee of unique users).
                    </p>
                </div>

                <div class="card">
                    <h3 class="mb-1">8. Service Availability</h3>
                    <p class="text-muted mb-0">
                        The Service may be updated, modified, or temporarily unavailable for maintenance. We do not
                        guarantee uptime. Third-party destinations (e.g., Google Drive) may change or become unavailable.
                    </p>
                </div>

                <div class="card">
                    <h3 class="mb-1">9. Termination</h3>
                    <p class="text-muted mb-0">
                        We may suspend or terminate access if you violate these Terms, create security risk, or abuse the
                        Service. You may stop using the Service at any time.
                    </p>
                </div>

                <div class="card">
                    <h3 class="mb-1">10. Disclaimer</h3>
                    <p class="text-muted mb-0">
                        The Service is provided “as is” and “as available”. We make no warranties that links will always
                        work or that third-party content will remain accessible.
                    </p>
                </div>

                <div class="card">
                    <h3 class="mb-1">11. Limitation of Liability</h3>
                    <p class="text-muted mb-0">
                        To the maximum extent permitted by law, DocIt is not liable for indirect, incidental, special, or
                        consequential damages arising from your use of the Service or reliance on any link.
                    </p>
                </div>

                <div class="card">
                    <h3 class="mb-1">12. Changes to These Terms</h3>
                    <p class="text-muted mb-0">
                        We may update these Terms from time to time. Continued use of the Service after changes means you
                        accept the updated Terms.
                    </p>
                </div>

                <div class="card">
                    <h3 class="mb-1">13. Contact</h3>
                    <p class="text-muted mb-0">
                        For questions about these Terms, contact support through the application or the contact details
                        shown on the website.
                    </p>
                </div>
            </div>

            <!-- Footer actions -->
            <div class="d-flex align-center mt-4 gap-2" style="justify-content: center">
                <a href="{{ route('register') }}" class="btn btn-outline">
                    <i class="fas fa-user-plus"></i> Back to Register
                </a>
            </div>

            <p class="text-muted mt-3" style="font-size: var(--font-size-sm);">
                <i class="fas fa-info-circle"></i>
                This page provides general terms for your app and is not legal advice. Consider reviewing with a legal
                professional if you plan to operate commercially.
            </p>
        </div>
    </div>
@endsection
