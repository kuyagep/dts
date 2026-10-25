<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Authentication') | DTS</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('images/favicon/site.webmanifest') }}">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('static/css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Select2 CSS & Bootstrap 5 Theme -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />

    <!-- Custom Auth & Mobile Styles -->
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #222E3C;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Responsive Auth Card Padding & Radius */
        .auth-card {
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2) !important;
        }

        /* Brand Logo Responsive Scaling */
        .auth-logo {
            max-height: 85px;
            width: auto;
            transition: max-height 0.2s ease;
        }

        @media (min-width: 576px) {
            .auth-logo {
                max-height: 110px;
            }
        }

        /* Fix Select2 Full Width on Mobile Dropdowns */
        .select2-container {
            width: 100% !important;
        }

        .select2-container--bootstrap-5 .select2-selection {
            min-height: 48px;
            display: flex;
            align-items: center;
            border-radius: 6px;
        }
    </style>
</head>

<body>
    <main class="d-flex w-100 min-vh-100 align-items-center py-3 py-sm-5">
        <div class="container px-3 px-sm-4">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">

                    <!-- Division / DepEd Branding Logo -->
                    <div class="text-center mb-3 mb-sm-4">
                        <img src="{{ asset('images/DTS_LOGO.png') }}" alt="DepEd Davao del Sur Logo"
                            class="img-fluid auth-logo mb-2">
                    </div>

                    <!-- Auth Form Card Container -->
                    <div class="card auth-card border-0">
                        <div class="card-body p-3 p-sm-4 p-md-5">
                            @yield('content')
                        </div>
                    </div>

                    <!-- Footer Copyright -->
                    <div class="text-center text-secondary mt-3">
                        <small class="small" style="font-size: 0.8rem;">
                            &copy; {{ date('Y') }} Division Office of Davao del Sur. All rights reserved.
                        </small>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset('static/js/app.js') }}"></script>
    <!-- jQuery & Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if ($('#office_id').length) {
                $('#office_id').select2({
                    theme: 'bootstrap-5',
                    placeholder: 'Select your office...',
                    allowClear: true,
                    width: '100%'
                });
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
