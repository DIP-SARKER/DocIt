@extends('layouts.app')

@section('title', 'DocIt - Your work. Anywhere.')

@section('content')

    <!-- Hero Section -->
    <main class="main-content">
        <section class="container">
            <div class="text-center" style="max-width: 800px; margin: 0 auto;">
                <h1 class="mb-2">Your work. <span class="text-accent">Anywhere.</span></h1>
                <p class="text-muted mb-4" style="font-size: var(--font-size-xl);">
                    Securely manage tasks, documents, and links from any device. Perfect for public computer use.
                </p>

                <div class="d-flex justify-center gap-2 flex-wrap">
                    <a href="#cta" class="btn btn-outline">
                        Start For Free
                    </a>
                </div>
            </div>
        </section>

        <!-- Feature Cards -->
        <section class="container mt-4" id="features">
            <h2 class="text-center mb-4">Everything you need in one place</h2>

            <div class="grid grid-3">
                <!-- Task Management -->
                <div class="card">
                    <div class="document-icon mb-1">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <h4>Task Management</h4>
                    <p class="text-muted">Create, organize, and track your to-do lists with priority levels and due
                        dates.</p>
                    <ul class="text-muted" style="padding-left: 1.25rem;">
                        <li>Priority indicators</li>
                        <li>Due date tracking</li>
                        <li>Progress monitoring</li>
                    </ul>
                </div>

                <!-- Document Links -->
                <div class="card">
                    <div class="document-icon mb-1">
                        <i class="fas fa-folder"></i>
                    </div>
                    <h4>Secure Document Links</h4>
                    <p class="text-muted">Store and access important document links securely from any public computer.
                    </p>
                    <ul class="text-muted" style="padding-left: 1.25rem;">
                        <li>Link-only storage (no files)</li>
                        <li>Quick access with QR codes</li>
                        <li>Organized by category</li>
                    </ul>
                </div>

                <!-- URL Shortener -->
                <div class="card">
                    <div class="document-icon mb-1">
                        <i class="fas fa-link"></i>
                    </div>
                    <h4>URL Shortener</h4>
                    <p class="text-muted">Create short, memorable links for quick sharing and easy access to important
                        resources.</p>
                    <ul class="text-muted" style="padding-left: 1.25rem;">
                        <li>Custom short URLs</li>
                        <li>Link expiry control</li>
                        <li>Click tracking</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- How it works -->
        <section class="container mt-4" id="how-it-works">

            <h2 class="text-center">Designed for public computer security</h2>
            <p class="text-center text-muted mb-4">DocIt follows security best practices to keep your data safe when
                using public computers.</p>

            <div class="grid grid-2 mt-3">
                <div>
                    <h4><i class="fas fa-shield-alt text-accent"></i> No file storage</h4>
                    <p>We only store document links, not the files themselves. Your sensitive documents remain where
                        you keep them.</p>

                    <h4 class="mt-3"><i class="fas fa-clock text-accent"></i> Auto-logout</h4>
                    <p>Set automatic logout timers to protect your session on shared devices.</p>
                </div>

                <div style="text-align: end">
                    <h4>Fast access <i class="fas fa-bolt text-accent"></i> </h4>
                    <p>Quickly access your important links with a single click or scan a QR code. One line more</p>

                    <h4 class="mt-3">Sync across devices <i class="fas fa-sync text-accent"></i></h4>
                    <p>Access your tasks and links from any device with a secure login.</p>
                </div>
            </div>

        </section>

        <!-- CTA -->
        <section class="container mt-1 text-center" id="cta">
            <div class="text-center" style="border-top: 0.5px solid var(--text-muted); padding-top: 1rem;">
                <h2>Ready to Boost Your Productivity?</h2>
                <p>Start using DocIt today for free.</p>
                <a href="{{ route('register') }}" class="btn btn-primary">
                    <i class="fas fa-rocket"></i>
                    Sign Up Now
                </a>
            </div>
        </section>

    @endsection
