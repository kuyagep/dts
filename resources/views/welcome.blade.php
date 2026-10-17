<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Division DocTracker | DepEd Davao del Sur</title>

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts (Inter & Montserrat) -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@600;700;800&display=swap"
        rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --deped-navy: #002B66;
            --deped-blue-dark: #001A3E;
            --deped-gold: #FFB800;
            --deped-gold-hover: #e0a200;
            --deped-slate: #F8FAFC;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--deped-slate);
            color: #1E293B;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        h1,
        h2,
        h3,
        .brand-font {
            font-family: 'Montserrat', sans-serif;
        }

        /* Hero Wrapper */
        .hero-section {
            background: linear-gradient(135deg, var(--deped-blue-dark) 0%, var(--deped-navy) 65%, #0A438A 100%);
            color: #FFFFFF;
            position: relative;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 80px 0 100px 0;
            overflow: hidden;
        }

        /* Decorative Lighting Orbs */
        .hero-orb-1 {
            position: absolute;
            top: -15%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: rgba(255, 184, 0, 0.08);
            border-radius: 50%;
            filter: blur(100px);
            pointer-events: none;
        }

        .hero-orb-2 {
            position: absolute;
            bottom: -20%;
            left: -10%;
            width: 600px;
            height: 600px;
            background: rgba(255, 255, 255, 0.04);
            border-radius: 50%;
            filter: blur(120px);
            pointer-events: none;
        }

        /* DepEd Badge Pill */
        .agency-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 8px 18px;
            border-radius: 50px;
            color: #FFFFFF;
            font-size: 0.875rem;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .agency-icon {
            color: var(--deped-gold);
        }

        /* Main CTA Buttons */
        .btn-gold {
            background-color: var(--deped-gold);
            color: #000000;
            font-weight: 700;
            border: none;
            padding: 14px 32px;
            border-radius: 12px;
            transition: all 0.25s ease;
            box-shadow: 0 4px 18px rgba(255, 184, 0, 0.3);
        }

        .btn-gold:hover {
            background-color: var(--deped-gold-hover);
            color: #000000;
            transform: translateY(-2px);
            box-shadow: 0 6px 24px rgba(255, 184, 0, 0.45);
        }

        .btn-outline-custom {
            border: 2px solid rgba(255, 255, 255, 0.35);
            color: #FFFFFF;
            font-weight: 600;
            padding: 12px 28px;
            border-radius: 12px;
            transition: all 0.25s ease;
            backdrop-filter: blur(4px);
        }

        .btn-outline-custom:hover {
            background-color: #FFFFFF;
            color: var(--deped-navy);
            border-color: #FFFFFF;
            transform: translateY(-2px);
        }

        /* Feature Highlight Cards */
        .feature-card {
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            padding: 24px;
            transition: all 0.3s ease;
            height: 100%;
        }

        .feature-card:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 184, 0, 0.4);
            transform: translateY(-4px);
        }

        .feature-icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(255, 184, 0, 0.15);
            color: var(--deped-gold);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-bottom: 16px;
        }

        /* Footer Bar */
        .landing-footer {
            background-color: var(--deped-blue-dark);
            color: #94A3B8;
            font-size: 0.85rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding: 20px 0;
        }
    </style>
</head>

<body>

    <!-- Main Hero Section -->
    <section class="hero-section">
        <div class="hero-orb-1"></div>
        <div class="hero-orb-2"></div>

        <div class="container position-relative z-1">
            <div class="row justify-content-center text-center">
                <div class="col-lg-10 col-xl-9">

                    <!-- Header Seal / Agency Identification -->
                    <div class="mb-4">
                        <div class="agency-badge shadow-sm">
                            <i class="fa-solid fa-landmark agency-icon"></i>
                            <span>Department of Education &bull; Division of Davao del Sur</span>
                        </div>
                    </div>

                    <!-- Main Titles -->
                    <h1 class="display-4 fw-extrabold text-white mb-2 brand-font">
                        Division DocTracker
                    </h1>
                    <h2 class="h5 fw-medium text-white-50 mb-4 me-lg-3">
                        Document Tracking & Routing Management System
                    </h2>

                    <!-- Value Proposition -->
                    <p class="lead text-white-50 mb-5 max-w-2xl mx-auto fs-6 lh-lg px-md-4">
                        A centralized digital platform designed for seamless dispatching, transmittal generation,
                        multi-office accountability, and real-time barcode/QR-based routing across all offices in the
                        Division of Davao del Sur.
                    </p>

                    <!-- Primary Action Buttons -->
                    <div class="d-flex flex-wrap justify-content-center gap-3 mb-5 pb-3">
                        <a href="{{ route('login') }}" class="btn btn-gold d-inline-flex align-items-center">
                            <span>Access System / Login</span>
                            <i class="fa-solid fa-arrow-right-to-bracket ms-2"></i>
                        </a>
                        <a href="#guidance" class="btn btn-outline-custom d-inline-flex align-items-center"
                            data-bs-toggle="modal" data-bs-target="#supportModal">
                            <i class="fa-solid fa-circle-question me-2"></i>
                            <span>System Guidance & Support</span>
                        </a>
                    </div>

                    <!-- Feature Highlight Badges -->
                    <div class="row g-4 text-start pt-2">
                        <div class="col-md-4">
                            <div class="feature-card">
                                <div class="feature-icon-wrapper">
                                    <i class="fa-solid fa-bolt"></i>
                                </div>
                                <h3 class="h6 text-white fw-bold mb-2">100% Real-Time Tracking</h3>
                                <p class="text-white-50 small mb-0">
                                    Monitor status updates, office-to-office handoffs, and pending actions instantly.
                                </p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="feature-card">
                                <div class="feature-icon-wrapper">
                                    <i class="fa-solid fa-qrcode"></i>
                                </div>
                                <h3 class="h6 text-white fw-bold mb-2">Barcode & QR Verification</h3>
                                <p class="text-white-50 small mb-0">
                                    Seamless integration with HID barcode scanners and mobile QR code verification.
                                </p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="feature-card">
                                <div class="feature-icon-wrapper">
                                    <i class="fa-solid fa-file-pdf"></i>
                                </div>
                                <h3 class="h6 text-white fw-bold mb-2">A4 Transmittal Printing</h3>
                                <p class="text-white-50 small mb-0">
                                    Automated generation of official batch transmittal slips ready for physical
                                    sign-off.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- System Guidance & Support Modal -->
    <div class="modal fade" id="supportModal" tabindex="-1" aria-labelledby="supportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark" id="supportModalLabel">
                        <i class="fa-solid fa-headset text-primary me-2"></i>System Guidance & Support
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <h6 class="fw-bold text-dark mb-1">ICT / Records Unit Helpdesk</h6>
                        <p class="text-muted small mb-3">For account provisioning, password resets, or system technical
                            assistance, please reach out to the Records Section.</p>
                    </div>
                    <ul class="list-group list-group-flush mb-3 small">
                        <li class="list-group-item px-0 d-flex align-items-center justify-content-between">
                            <span class="text-muted"><i class="fa-solid fa-envelope me-2 text-primary"></i> Email
                                Support:</span>
                            <span class="fw-semibold">support.davsur@deped.gov.ph</span>
                        </li>
                        <li class="list-group-item px-0 d-flex align-items-center justify-content-between">
                            <span class="text-muted"><i class="fa-solid fa-building me-2 text-primary"></i> Division
                                Office:</span>
                            <span class="fw-semibold">Records Section, ICT Unit</span>
                        </li>
                    </ul>
                    <a href="{{ route('login') }}" class="btn btn-primary w-100 fw-bold py-2"
                        style="background-color: var(--deped-navy);">
                        Proceed to Login
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Official Footer -->
    <footer class="landing-footer text-center">
        <div class="container">
            <p class="mb-1">&copy; {{ date('Y') }} Department of Education &bull; Division of Davao del Sur.</p>
            <small class="text-muted">Division Document Tracking & Routing Management System (DocTracker)</small>
        </div>
    </footer>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
