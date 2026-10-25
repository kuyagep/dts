<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Primary Search Engine SEO -->
    <title>Division Document Tracking System | DepEd Davao del Sur</title>
    <meta name="title" content="Division Document Tracking System - DepEd Davao del Sur">
    <meta name="description"
        content="Official Division Document Tracking System (DTS) for DepEd Schools Division of Davao del Sur. Streamline document routing, QR tracking, and transmittals.">
    <meta name="keywords"
        content="DepEd, Davao del Sur, Document Tracking System, DTS, DDTS, DepEd Davao del Sur, Document Management, Government Tracking">
    <meta name="author" content="Geperson Mamalias">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook / Social Sharing -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Division Document Tracking System">
    <meta property="og:description"
        content="Official Document Tracking System for DepEd Schools Division of Davao del Sur. Fast, secure, and transparent document routing.">
    <meta property="og:image" content="{{ asset('images/deped-davao-del-sur-og.png') }}">

    <!-- Twitter Card -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ config('app.name', 'DTS') }} | Division Document Tracking System">
    <meta property="twitter:description"
        content="Official Document Tracking System for DepEd Schools Division of Davao del Sur.">
    <meta property="twitter:image" content="{{ asset('images/deped-davao-del-sur-og.png') }}">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('images/favicon/site.webmanifest') }}">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome & Feather Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://unpkg.com/feather-icons"></script>
    <link href="{{ asset('static/css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Custom Branding Styles -->
    <style>
        :root {
            --deped-navy: #001A3E;
            --deped-gold: #FFC107;
            --deped-slate: #f8f9fa;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--deped-slate);
            color: #333;
            overflow-x: hidden;
        }

        /* Hero Header Customization */
        .hero-section {
            background: linear-gradient(135deg, #034b27 0%, #001A3E 100%);
            color: #ffffff;
            padding: 50px 0 40px 0;
            position: relative;
            border-bottom: 4px solid var(--deped-gold);
        }

        @media (min-width: 768px) {
            .hero-section {
                padding: 80px 0 60px 0;
            }
        }

        .brand-badge {
            background-color: rgba(255, 193, 7, 0.15);
            color: var(--deped-gold);
            border: 1px solid var(--deped-gold);
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            white-space: normal;
            word-break: break-word;
        }

        @media (min-width: 576px) {
            .brand-badge {
                font-size: 0.85rem;
            }
        }

        /* AdminKit Style Feature Cards */
        .feature-card {
            border: none;
            border-radius: 10px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
        }

        .icon-square {
            width: 52px;
            height: 52px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        /* Mobile Typography Scale */
        @media (max-width: 575.98px) {
            .hero-title {
                font-size: 1.85rem !important;
                line-height: 1.25;
            }

            .hero-lead {
                font-size: 0.95rem !important;
            }

            .modal-dialog {
                margin: 0.75rem;
            }
        }
    </style>
</head>

<body>

    <!-- Hero Section -->
    <section class="hero-section text-center text-md-start">
        <div class="container px-3 px-sm-4">
            <div class="row align-items-center g-4">
                <div class="col-12 col-lg-7">
                    <span
                        class="badge brand-badge px-3 py-2 rounded-pill text-uppercase mb-3 fw-semibold d-inline-block max-w-100">
                        Schools Division of Davao del Sur
                    </span>
                    <h1 class="display-5 fw-bold text-white mb-3 hero-title">
                        Division Document Tracking System
                    </h1>
                    <p class="lead text-light opacity-75 mb-4 hero-lead">
                        A secure, centralized lifecycle portal designed to streamline administrative routing,
                        transmittals, and real-time tracking across division offices and schools.
                    </p>

                    <!-- Responsive Action Buttons -->
                    <div class="d-grid gap-2 d-sm-flex justify-content-sm-center justify-content-md-start">
                        @auth
                            <a href="{{ route('dashboard') }}"
                                class="btn btn-warning btn-lg fw-bold px-4 shadow-sm w-100 w-sm-auto"
                                style="color: #002B66;">
                                <i data-feather="grid" class="me-1"></i> Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="btn btn-warning btn-lg fw-bold px-4 shadow-sm w-100 w-sm-auto"
                                style="color: #002B66;">
                                <i data-feather="log-in" class="me-1"></i> Account Sign In
                            </a>
                            <button type="button" class="btn btn-outline-light btn-lg px-4 w-100 w-sm-auto"
                                data-bs-toggle="modal" data-bs-target="#systemInfoModal">
                                <i data-feather="info" class="me-1"></i> System Guidance
                            </button>
                        @endauth
                    </div>
                </div>

                <div class="col-12 col-lg-5 text-center">
                    <div class="card border-0 shadow-lg bg-white p-3 p-sm-4 rounded-4">
                        <div class="card-body p-2 p-sm-3">
                            <div class="icon-square bg-primary text-white mb-3 shadow-sm"
                                style="background-color: var(--deped-navy) !important;">
                                <i class="fa-solid fa-file-shield"></i>
                            </div>
                            <h4 class="fw-bold text-dark mb-2 fs-5 fs-sm-4">Track & Manage</h4>
                            <p class="text-muted small mb-4">
                                Secure access is restricted to verified DepEd Davao del Sur division personnel and
                                school heads.
                            </p>
                            @guest
                                <a href="{{ route('register') }}" class="btn btn-outline-primary w-100 fw-semibold py-2">
                                    Request Account Access
                                </a>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Key Highlights Section -->
    <section class="py-4 py-md-5">
        <div class="container py-2 py-md-4 px-3 px-sm-4">
            <div class="text-center mb-4 mb-md-5">
                <h2 class="fw-bold text-dark fs-3 fs-md-2">Features</h2>
                <p class="text-muted small fs-6">Built for speed, accountability, and seamless administrative workflow.
                </p>
            </div>

            <div class="row g-3 g-md-4">
                <!-- Feature 1 -->
                <div class="col-12 col-md-4">
                    <div class="card h-100 feature-card shadow-sm p-2 p-sm-3">
                        <div class="card-body">
                            <div class="icon-square bg-primary-subtle text-primary mb-3">
                                <i data-feather="navigation"></i>
                            </div>
                            <h5 class="fw-bold text-dark fs-5">Real-Time Routing</h5>
                            <p class="text-muted small mb-0">
                                Monitor step-by-step document lifecycle status across various division sections,
                                offices, and schools.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="col-12 col-md-4">
                    <div class="card h-100 feature-card shadow-sm p-2 p-sm-3">
                        <div class="card-body">
                            <div class="icon-square bg-warning-subtle text-warning mb-3">
                                <i class="fa-solid fa-qrcode"></i>
                            </div>
                            <h5 class="fw-bold text-dark fs-5">QR Code Verification</h5>
                            <p class="text-muted small mb-0">
                                Instant modal-based QR Code scanning allows quick receiving and releasing of
                                official documents.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="col-12 col-md-4">
                    <div class="card h-100 feature-card shadow-sm p-2 p-sm-3">
                        <div class="card-body">
                            <div class="icon-square bg-success-subtle text-success mb-3">
                                <i data-feather="printer"></i>
                            </div>
                            <h5 class="fw-bold text-dark fs-5">Auto Generated Transmittal</h5>
                            <p class="text-muted small mb-0">
                                Generate standardized printable transmittal slips and document routing slips formatted
                                for division use.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- System Guidance Modal -->
    <div class="modal fade" id="systemInfoModal" tabindex="-1" aria-labelledby="systemInfoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header text-white" style="background-color: var(--deped-navy);">
                    <h5 class="modal-title fw-bold fs-6 fs-sm-5" id="systemInfoModalLabel">
                        <i data-feather="help-circle" class="me-1"></i> Account Access Notice
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-3 p-sm-4">
                    <p class="text-secondary small fs-sm-6">
                        The <strong>Document Tracking System (DTS)</strong> requires account registration with
                        an official <code>@deped.gov.ph</code> email.
                    </p>
                    <ul class="text-secondary mb-3 small">
                        <li>New accounts undergo email verification upon registration.</li>
                        <li>All user accounts remain <strong>Pending</strong> until approved by an administrator.</li>
                        <li>Unapproved or deactivated accounts will be restricted from system access.</li>
                    </ul>
                </div>
                <div class="modal-footer bg-light p-2 p-sm-3">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Proceed to Sign In</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-top py-3 py-md-4 text-center text-muted small">
        <div class="container px-3">
            <p class="mb-1">&copy; {{ date('Y') }} DepEd Schools Division of Davao del Sur. All Rights Reserved.
            </p>
            <p class="mb-0 text-secondary">Document Tracking System | DTS</p>
        </div>
    </footer>

    <script src="{{ asset('static/js/app.js') }}"></script>
    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Feather Icons Init -->
    <script>
        feather.replace();
    </script>
</body>

</html>
