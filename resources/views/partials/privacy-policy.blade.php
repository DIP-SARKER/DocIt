@extends('layouts.guest')

@section('title', 'Privacy Policy - DocIt')

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

            <h2 class="mb-1">Privacy Policy</h2>
            <p class="text-muted mb-3">
                This Privacy Policy explains how DocIt collects, uses, and protects your information when you use the
                Service.
            </p>

            <!-- Notice -->
            <div class="alert alert-info mb-4">
                <i class="fas fa-shield-alt"></i>
                <strong>Summary:</strong> We collect only what is necessary to provide DocIt. Passwords are securely
                hashed, and we do not sell your personal data.
            </div>

            <!-- Content -->
            <div class="grid gap-3">
                <div class="card">
                    <h3 class="mb-1">1. Information We Collect</h3>
                    <ul class="text-muted mb-0" style="padding-left: var(--space-lg);">
                        <li><strong>Account Information:</strong> username, email address, and encrypted password.</li>
                        <li><strong>User Content:</strong> document links, short links, titles, descriptions, and related
                            metadata.</li>
                        <li><strong>Usage Data:</strong> click counts on short links (if tracking is enabled).</li>
                    </ul>
                </div>

                <div class="card">
                    <h3 class="mb-1">2. How We Use Your Information</h3>
                    <ul class="text-muted mb-0" style="padding-left: var(--space-lg);">
                        <li>To create and manage your account.</li>
                        <li>To store and display your document links and short URLs.</li>
                        <li>To track link usage when click tracking is enabled.</li>
                        <li>To improve performance, reliability, and user experience.</li>
                    </ul>
                </div>

                <div class="card">
                    <h3 class="mb-1">3. Passwords & Security</h3>
                    <p class="text-muted mb-0">
                        Passwords are never stored in plain text. We use secure hashing techniques to protect your login
                        credentials. You are responsible for keeping your password confidential.
                    </p>
                </div>

                <div class="card">
                    <h3 class="mb-1">4. Public vs Private Content</h3>
                    <p class="text-muted mb-2">
                        DocIt allows you to control visibility of your content.
                    </p>
                    <ul class="text-muted mb-0" style="padding-left: var(--space-lg);">
                        <li>Documents marked as <strong>Locked</strong> are intended to remain private.</li>
                        <li>Only <strong>unlocked</strong> documents may be shown on public profile pages.</li>
                        <li>Short links are publicly accessible by design.</li>
                    </ul>
                </div>

                <div class="card">
                    <h3 class="mb-1">5. Cookies & Tracking</h3>
                    <p class="text-muted mb-0">
                        DocIt may use essential cookies to maintain login sessions. We do not use advertising cookies.
                        Click tracking applies only to short links where the feature is enabled.
                    </p>
                </div>

                <div class="card">
                    <h3 class="mb-1">6. Data Sharing</h3>
                    <p class="text-muted mb-0">
                        We do not sell, rent, or trade your personal information. Data may be shared only when required
                        by law or to protect the security and integrity of the Service.
                    </p>
                </div>

                <div class="card">
                    <h3 class="mb-1">7. Third-Party Services</h3>
                    <p class="text-muted mb-0">
                        DocIt stores links that may point to third-party services (e.g., cloud storage providers). We are
                        not responsible for the privacy practices or content of those external services.
                    </p>
                </div>

                <div class="card">
                    <h3 class="mb-1">8. Data Retention</h3>
                    <p class="text-muted mb-0">
                        We retain your data for as long as your account remains active. If you delete your account,
                        associated data may be permanently removed, subject to legal or operational requirements.
                    </p>
                </div>

                <div class="card">
                    <h3 class="mb-1">9. Your Rights</h3>
                    <ul class="text-muted mb-0" style="padding-left: var(--space-lg);">
                        <li>You may update or delete your content at any time.</li>
                        <li>You may request account deletion.</li>
                        <li>You may contact us regarding access to or correction of your data.</li>
                    </ul>
                </div>

                <div class="card">
                    <h3 class="mb-1">10. Children’s Privacy</h3>
                    <p class="text-muted mb-0">
                        DocIt is not intended for use by individuals under the age of 13. We do not knowingly collect
                        personal information from children.
                    </p>
                </div>

                <div class="card">
                    <h3 class="mb-1">11. Changes to This Policy</h3>
                    <p class="text-muted mb-0">
                        We may update this Privacy Policy from time to time. Continued use of the Service after changes
                        means you accept the updated policy.
                    </p>
                </div>

                <div class="card">
                    <h3 class="mb-1">12. Contact</h3>
                    <p class="text-muted mb-0">
                        If you have questions or concerns about this Privacy Policy, please contact us through the
                        application or via the support information provided on the website.
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="d-flex align-center mt-4 gap-2" style="justify-content: center">
                <a href="{{ route('register') }}" class="btn btn-outline">
                    <i class="fas fa-user-plus"></i> Back to Register
                </a>
            </div>

            <p class="text-muted mt-3" style="font-size: var(--font-size-sm);">
                <i class="fas fa-info-circle"></i>
                This Privacy Policy is provided for general informational purposes and does not constitute legal advice.
            </p>
        </div>
    </div>
@endsection
