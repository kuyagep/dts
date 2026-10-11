<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Division Document Tracking System (DDTS) | Official Portal</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts: Inter & JetBrains Mono -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap"
        rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('images/favicon/site.webmanifest') }}">
    <style>
        :root {
            --primary-slate: #0f172a;
            --secondary-slate: #1e293b;
            --accent-blue: #2563eb;
            --accent-blue-hover: #1d4ed8;
            --accent-cyan: #06b6d4;
            --bg-light: #f8fafc;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(226, 232, 240, 0.8);
            --card-shadow: 0 10px 30px -10px rgba(15, 23, 42, 0.08);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        .mono-font {
            font-family: 'JetBrains Mono', monospace;
        }

        .navbar {
            background-color: rgba(255, 255, 255, 0.9) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            transition: all 0.3s ease;
        }

        .navbar-brand .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--accent-blue), var(--accent-cyan));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.2rem;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .nav-link {
            font-weight: 500;
            color: #334155 !important;
            transition: color 0.2s ease;
        }

        .nav-link:hover {
            color: var(--accent-blue) !important;
        }

        .hero-section {
            background: radial-gradient(circle at 10% 20%, rgba(37, 99, 235, 0.06) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(6, 182, 212, 0.06) 0%, transparent 40%),
                linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            padding-top: 8rem;
            padding-bottom: 5rem;
            position: relative;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 16px;
            border-radius: 50px;
            background: rgba(37, 99, 235, 0.08);
            color: var(--accent-blue);
            font-size: 0.875rem;
            font-weight: 600;
            border: 1px solid rgba(37, 99, 235, 0.2);
        }

        .hero-title {
            font-size: 3.25rem;
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -0.02em;
            color: var(--primary-slate);
        }

        .hero-subtitle {
            font-size: 1.2rem;
            color: var(--text-muted);
            line-height: 1.6;
        }

        .tracker-card {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid var(--glass-border);
            box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.12);
            padding: 2rem;
            position: relative;
        }

        .tracker-input-group .form-control {
            border-radius: 12px 0 0 12px;
            padding: 0.9rem 1.2rem;
            font-size: 1.05rem;
            border: 1px solid #cbd5e1;
        }

        .tracker-input-group .form-control:focus {
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
        }

        .tracker-input-group .btn-track {
            border-radius: 0 12px 12px 0;
            padding: 0 1.8rem;
            background: var(--accent-blue);
            color: #fff;
            font-weight: 600;
            border: none;
            transition: all 0.2s ease;
        }

        .tracker-input-group .btn-track:hover {
            background: var(--accent-blue-hover);
            transform: translateY(-1px);
        }

        /* Timeline Tracker Visuals */
        .timeline-stepper {
            position: relative;
            padding-left: 20px;
            border-left: 2px solid #e2e8f0;
            margin-top: 1.5rem;
        }

        .timeline-step {
            position: relative;
            margin-bottom: 1.5rem;
            padding-left: 15px;
        }

        .timeline-step:last-child {
            margin-bottom: 0;
        }

        .timeline-step::before {
            content: '';
            position: absolute;
            left: -27px;
            top: 2px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #cbd5e1;
            border: 2px solid #fff;
        }

        .timeline-step.active::before {
            background: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.25);
        }

        .timeline-step.completed::before {
            background: #10b981;
        }

        .feature-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 2rem;
            border: 1px solid var(--glass-border);
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 35px -10px rgba(15, 23, 42, 0.12);
            border-color: rgba(37, 99, 235, 0.3);
        }

        .feature-icon-wrapper {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1.25rem;
        }

        .workflow-step-number {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--primary-slate);
            color: #fff;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .analytics-section {
            background: var(--primary-slate);
            color: #ffffff;
            border-radius: 24px;
            padding: 4rem 2rem;
            position: relative;
            overflow: hidden;
        }

        .analytics-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.25) 0%, transparent 70%);
            pointer-events: none;
        }

        .metric-counter {
            font-size: 2.75rem;
            font-weight: 800;
            color: #ffffff;
            font-family: 'JetBrains Mono', monospace;
        }

        .accordion-button:not(.collapsed) {
            background-color: rgba(37, 99, 235, 0.05);
            color: var(--accent-blue);
            box-shadow: none;
        }

        .accordion-button:focus {
            box-shadow: none;
            border-color: rgba(37, 99, 235, 0.25);
        }

        footer {
            background-color: var(--primary-slate);
            color: #94a3b8;
            padding-top: 4rem;
            padding-bottom: 2rem;
        }

        footer a {
            color: #cbd5e1;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        footer a:hover {
            color: #ffffff;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg fixed-top py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold fs-4 text-dark" href="#">
                <div class="logo-icon">
                    <i class="fa-solid fa-folder-tree"></i>
                </div>
                <span>DDTS<span class="text-primary">.gov</span></span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link px-3" href="#features">Features</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#workflow">How It Works</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#tracker">Live Tracker</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#analytics">Analytics</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#faq">FAQ & Support</a></li>
                </ul>
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-outline-secondary px-3 py-2 fw-semibold border-0" data-bs-toggle="modal"
                        data-bs-target="#quickSearchModal">
                        <i class="fa-solid fa-magnifying-glass me-1"></i> Quick Search
                    </button>
                    <a href="/login" class="btn btn-primary px-4 py-2 fw-semibold rounded-3 shadow-sm">
                        <i class="fa-solid fa-right-to-bracket me-1"></i> Portal Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <section class="hero-section" id="hero">
        <div class="container">
            <div class="row align-items-center gy-5">
                <div class="col-lg-6">
                    <div class="hero-badge mb-3">
                        <i class="fa-solid fa-shield-halved me-2"></i> Division Document Tracking System v2.4
                    </div>
                    <h1 class="hero-title mb-3">
                        Enterprise Grade Document Routing & Transit Control.
                    </h1>
                    <p class="hero-subtitle mb-4">
                        Streamline document dispatching, eliminate lost transmittals, and enforce end-to-end audit
                        accountability across all division offices and units.
                    </p>
                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <a href="#tracker" class="btn btn-primary btn-lg px-4 py-3 fw-bold rounded-3 shadow-sm">
                            <i class="fa-solid fa-barcode me-2"></i> Track Document Now
                        </a>
                        <a href="#workflow" class="btn btn-outline-dark btn-lg px-4 py-3 fw-semibold rounded-3">
                            <i class="fa-solid fa-circle-play me-2"></i> System Overview
                        </a>
                    </div>
                    <div class="mt-4 pt-3 d-flex align-items-center gap-4 text-muted small">
                        <div><i class="fa-solid fa-check-circle text-success me-1"></i> Real-Time Alerts</div>
                        <div><i class="fa-solid fa-check-circle text-success me-1"></i> Spatie RBAC Security</div>
                        <div><i class="fa-solid fa-check-circle text-success me-1"></i> Batch Slip Printing</div>
                    </div>
                </div>

                <!-- Live Tracking Simulation Interactive Card -->
                <div class="col-lg-6" id="tracker">
                    <div class="tracker-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0 text-dark">
                                <i class="fa-solid fa-satellite-dish text-primary me-2"></i> Live Tracking Simulator
                            </h5>
                            <span
                                class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">
                                System Active
                            </span>
                        </div>
                        <p class="text-muted small mb-3">Input a tracking number or select a test code below to simulate
                            real-time routing check.</p>

                        <div class="input-group tracker-input-group mb-3">
                            <input type="text" id="trackingInput" class="form-control mono-font"
                                placeholder="e.g. DOC-2026-8849" value="DOC-2026-8849">
                            <button class="btn btn-track" type="button" id="btnTrackNow">
                                <i class="fa-solid fa-search me-1"></i> Track
                            </button>
                        </div>

                        <!-- Quick Sample Preset Pills -->
                        <div class="d-flex flex-wrap gap-2 mb-4 align-items-center">
                            <small class="text-muted fw-semibold me-1">Try Presets:</small>
                            <button class="btn btn-sm btn-light border px-2 py-1 mono-font small sample-btn"
                                data-code="DOC-2026-8849">DOC-2026-8849</button>
                            <button class="btn btn-sm btn-light border px-2 py-1 mono-font small sample-btn"
                                data-code="DOC-2026-1023">DOC-2026-1023</button>
                            <button class="btn btn-sm btn-light border px-2 py-1 mono-font small sample-btn"
                                data-code="DOC-2026-9041">DOC-2026-9041</button>
                        </div>

                        <!-- Dynamic Result Display Container -->
                        <div id="trackingResult" class="p-3 bg-light rounded-3 border">
                            <!-- Populated dynamically via JS -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-white" id="features">
        <div class="container py-4">
            <div class="text-center max-w-2xl mx-auto mb-5">
                <span class="text-primary fw-bold text-uppercase small tracking-wider">Enterprise Architecture</span>
                <h2 class="fw-bold fs-1 mt-1">Designed for High-Volume Division Workflows</h2>
                <p class="text-muted">Built specifically for division administrative offices requiring fast, precise,
                    and secure document tracking.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper bg-primary-subtle text-primary">
                            <i class="fa-solid fa-route"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Smart Queue Routing</h5>
                        <p class="text-muted small mb-0">Automatic sorting into Incoming, Pending Action, In Transit,
                            and Completed division queues.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper bg-info-subtle text-info">
                            <i class="fa-solid fa-print"></i>
                        </div>
                        <h5 class="fw-bold mb-2">AJAX Batch Printing</h5>
                        <p class="text-muted small mb-0">Select multiple in-transit items and generate printable
                            transmittal slips directly via AJAX iframe.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper bg-warning-subtle text-warning">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Complete Audit Trail</h5>
                        <p class="text-muted small mb-0">Immutable tracking history detailing every release, receiving
                            officer, and timestamp.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper bg-success-subtle text-success">
                            <i class="fa-solid fa-user-shield"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Role-Based Access</h5>
                        <p class="text-muted small mb-0">Granular Spatie permissions separating Office Custodians,
                            Division Heads, and Authors.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5" id="workflow">
        <div class="container py-4">
            <div class="text-center max-w-2xl mx-auto mb-5">
                <span class="text-primary fw-bold text-uppercase small tracking-wider">Simple 4-Step Process</span>
                <h2 class="fw-bold fs-1 mt-1">How DDTS Manages Division Transmittals</h2>
                <p class="text-muted">A standardized lifecycle guaranteeing complete visibility from creation to
                    archiving.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="p-4 bg-white rounded-4 border h-100 position-relative">
                        <div class="workflow-step-number">1</div>
                        <h5 class="fw-bold mb-2">Document Tagging</h5>
                        <p class="text-muted small mb-0">The originating office creates a record and attaches a unique
                            system tracking barcode.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="p-4 bg-white rounded-4 border h-100 position-relative">
                        <div class="workflow-step-number">2</div>
                        <h5 class="fw-bold mb-2">Dispatch In Transit</h5>
                        <p class="text-muted small mb-0">Document is dispatched with a batch transmittal slip and
                            marked 'In Transit' in the system.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="p-4 bg-white rounded-4 border h-100 position-relative">
                        <div class="workflow-step-number">3</div>
                        <h5 class="fw-bold mb-2">Receiving & Action</h5>
                        <p class="text-muted small mb-0">Destination office scans barcode to receive, automatically
                            updating queue status to Pending Action.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="p-4 bg-white rounded-4 border h-100 position-relative">
                        <div class="workflow-step-number">4</div>
                        <h5 class="fw-bold mb-2">Archiving Vault</h5>
                        <p class="text-muted small mb-0">Upon completion, document is officially signed off and vaulted
                            into permanent digital archives.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="container py-4" id="analytics">
        <div class="analytics-section shadow-lg">
            <div class="row align-items-center gy-4">
                <div class="col-lg-5">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 mb-2">Live
                        Statistics</span>
                    <h2 class="fw-bold text-white fs-1">Division Impact in Real-Time</h2>
                    <p class="text-slate-300 me-lg-3">
                        Our centralized tracking engine ensures division-wide compliance and drastically reduces
                        document processing turnaround time.
                    </p>
                </div>
                <div class="col-lg-7">
                    <div class="row g-4 text-center">
                        <div class="col-6 col-md-3">
                            <div class="metric-counter" id="counterDocs">14,280</div>
                            <div class="text-slate-400 small fw-medium mt-1">Total Processed</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="metric-counter" id="counterOffices">48</div>
                            <div class="text-slate-400 small fw-medium mt-1">Division Offices</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="metric-counter" id="counterTime">1.4h</div>
                            <div class="text-slate-400 small fw-medium mt-1">Avg Transit Time</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="metric-counter" id="counterRate">99.8%</div>
                            <div class="text-slate-400 small fw-medium mt-1">Traceability</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-white mt-5" id="faq">
        <div class="container py-4">
            <div class="text-center max-w-2xl mx-auto mb-5">
                <span class="text-primary fw-bold text-uppercase small tracking-wider">Help & Resources</span>
                <h2 class="fw-bold fs-1 mt-1">Frequently Asked Questions</h2>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item border rounded-3 mb-2 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq1">
                                    How do I receive an incoming document sent from another office?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Simply navigate to your office's <strong>Incoming Documents</strong> queue, click on
                                    the document tracking number, verify physical presence, and click <strong>"Receive
                                        Document"</strong>. You can also scan the transmittal barcode.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border rounded-3 mb-2 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Can I print multiple transmittal slips at once?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Yes! Under the <strong>In Transit Documents</strong> view, use the checkbox column
                                    to select individual documents or click <strong>Select All</strong>, then hit
                                    <strong>Print Transmittal</strong>. An automatic printable slip will open in a
                                    silent iframe.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border rounded-3 mb-2 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faq3">
                                    Who can edit or modify office records?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Only users assigned System Administrator roles can edit master division offices and
                                    departments. Document authors can update draft details before official dispatch.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="row gy-4 mb-4">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center gap-2 fw-bold fs-4 text-white mb-3">
                        <div class="logo-icon me-1">
                            <i class="fa-solid fa-folder-tree"></i>
                        </div>
                        <span>DDTS<span class="text-primary">.gov</span></span>
                    </div>
                    <p class="small text-slate-400">
                        Official Division Document Tracking System designed to manage, record, and audit document
                        workflows across all administrative units.
                    </p>
                </div>
                <div class="col-6 col-lg-2">
                    <h6 class="text-white fw-bold mb-3">Quick Queues</h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2">
                        <li><a href="#">Incoming Queue</a></li>
                        <li><a href="#">In Transit List</a></li>
                        <li><a href="#">Pending Actions</a></li>
                        <li><a href="#">Archived Vault</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2">
                    <h6 class="text-white fw-bold mb-3">Management</h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2">
                        <li><a href="#">My Documents</a></li>
                        <li><a href="#">Office Directory</a></li>
                        <li><a href="#">Departments</a></li>
                        <li><a href="#">Transmittal Print</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h6 class="text-white fw-bold mb-3">Division Office Desk</h6>
                    <p class="small text-slate-400 mb-2">
                        <i class="fa-solid fa-building me-2"></i> Administrative Building, Division Main Campus
                    </p>
                    <p class="small text-slate-400 mb-2">
                        <i class="fa-solid fa-envelope me-2"></i> support@ddts-division.gov
                    </p>
                    <p class="small text-slate-400">
                        <i class="fa-solid fa-phone me-2"></i> (083) 552-8200 local 104
                    </p>
                </div>
            </div>
            <div
                class="border-top border-slate-700 pt-4 d-flex flex-column flex-sm-row justify-content-between align-items-center small">
                <p class="mb-0">&copy; 2026 Division Document Tracking System (DDTS). All rights reserved.</p>
                <div class="d-flex gap-3 mt-2 mt-sm-0">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                    <a href="#">Security Audit</a>
                </div>
            </div>
        </div>
    </footer>

    <div class="modal fade" id="quickSearchModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Quick Document Search</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-white border-end-0"><i
                                class="fa-solid fa-search text-muted"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0"
                            placeholder="Type title or tracking number...">
                    </div>
                    <small class="text-muted">Press enter to search across all division records.</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mock Tracking Database
            const mockDatabase = {
                'DOC-2026-8849': {
                    title: 'FY 2026 Division Budget Allocation Request',
                    type: 'Financial / Budget',
                    origin: 'Accounting Division',
                    currentCustodian: 'Office of the Division Superintendent',
                    status: 'In Transit',
                    statusBadge: 'bg-info text-dark',
                    timeline: [{
                            stage: 'Document Created',
                            office: 'Accounting Division',
                            date: 'Oct 10, 2026 09:15 AM',
                            status: 'completed'
                        },
                        {
                            stage: 'Released / Dispatched',
                            office: 'Accounting Division',
                            date: 'Oct 10, 2026 11:30 AM',
                            status: 'completed'
                        },
                        {
                            stage: 'In Transit to Superintendent',
                            office: 'Courier Desk',
                            date: 'Oct 11, 2026 08:45 AM',
                            status: 'active'
                        },
                        {
                            stage: 'Awaiting Reception',
                            office: 'Office of the Superintendent',
                            date: 'Pending',
                            status: 'pending'
                        }
                    ]
                },
                'DOC-2026-1023': {
                    title: 'School Inspection & Facilities Report Q3',
                    type: 'Inspection Report',
                    origin: 'Physical Facilities Unit',
                    currentCustodian: 'Planning & Research Section',
                    status: 'Received / Pending',
                    statusBadge: 'bg-primary',
                    timeline: [{
                            stage: 'Document Created',
                            office: 'Physical Facilities Unit',
                            date: 'Oct 08, 2026 02:00 PM',
                            status: 'completed'
                        },
                        {
                            stage: 'Dispatched via Batch Slip',
                            office: 'Physical Facilities Unit',
                            date: 'Oct 09, 2026 08:15 AM',
                            status: 'completed'
                        },
                        {
                            stage: 'Received at Destination',
                            office: 'Planning & Research Section',
                            date: 'Oct 09, 2026 10:20 AM',
                            status: 'completed'
                        },
                        {
                            stage: 'Under Executive Review',
                            office: 'Planning & Research Section',
                            date: 'Oct 11, 2026 09:00 AM',
                            status: 'active'
                        }
                    ]
                },
                'DOC-2026-9041': {
                    title: 'Appointment Papers - Secondary School Principal II',
                    type: 'Personnel / HR',
                    origin: 'Human Resource Management Office',
                    currentCustodian: 'Records Management Vault',
                    status: 'Completed / Archived',
                    statusBadge: 'bg-success',
                    timeline: [{
                            stage: 'Document Created',
                            office: 'HR Management Office',
                            date: 'Oct 01, 2026 10:00 AM',
                            status: 'completed'
                        },
                        {
                            stage: 'Approved & Signed',
                            office: 'Division Superintendent',
                            date: 'Oct 03, 2026 04:30 PM',
                            status: 'completed'
                        },
                        {
                            stage: 'Archived into Vault',
                            office: 'Records Management Vault',
                            date: 'Oct 05, 2026 01:15 PM',
                            status: 'completed'
                        }
                    ]
                }
            };

            const trackingInput = document.getElementById('trackingInput');
            const btnTrackNow = document.getElementById('btnTrackNow');
            const trackingResult = document.getElementById('trackingResult');
            const sampleBtns = document.querySelectorAll('.sample-btn');

            function renderTrackingData(code) {
                const query = code.trim().toUpperCase();
                const record = mockDatabase[query];

                if (!record) {
                    trackingResult.innerHTML = `
                        <div class="text-center py-4">
                            <i class="fa-solid fa-triangle-exclamation text-warning fs-2 mb-2"></i>
                            <h6 class="fw-bold text-dark">No Document Record Found</h6>
                            <p class="text-muted small mb-0">The tracking code <strong>"${query}"</strong> was not found in the live dispatch buffer.</p>
                        </div>
                    `;
                    return;
                }

                let timelineHtml = '';
                record.timeline.forEach(item => {
                    const activeClass = item.status === 'active' ? 'active' : (item.status === 'completed' ?
                        'completed' : '');
                    timelineHtml += `
                        <div class="timeline-step ${activeClass}">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold small text-dark">${item.stage}</span>
                                <span class="text-muted extra-small mono-font">${item.date}</span>
                            </div>
                            <small class="text-muted d-block">${item.office}</small>
                        </div>
                    `;
                });

                trackingResult.innerHTML = `
                    <div class="mb-3 border-bottom pb-2">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="badge ${record.statusBadge} mb-1">${record.status}</span>
                                <h6 class="fw-bold text-dark mb-0">${record.title}</h6>
                                <small class="text-muted">Type: ${record.type}</small>
                            </div>
                        </div>
                    </div>
                    <div class="small mb-3">
                        <div class="row g-2">
                            <div class="col-6">
                                <span class="text-muted d-block extra-small">Originating Office:</span>
                                <strong>${record.origin}</strong>
                            </div>
                            <div class="col-6">
                                <span class="text-muted d-block extra-small">Current Custodian:</span>
                                <strong>${record.currentCustodian}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="timeline-stepper">
                        ${timelineHtml}
                    </div>
                `;
            }

            // Initial render
            renderTrackingData(trackingInput.value);

            // Button click search
            btnTrackNow.addEventListener('click', function() {
                renderTrackingData(trackingInput.value);
            });

            // Sample preset clicks
            sampleBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const code = this.getAttribute('data-code');
                    trackingInput.value = code;
                    renderTrackingData(code);
                });
            });
        });
    </script>
</body>

</html>
