<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Authentication') | Division Document Tracking System</title>

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('images/favicon/site.webmanifest') }}">
    <link href="{{ asset('static/css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
    <main class="d-flex w-100 h-100">
        <div class="container d-flex flex-column">
            <div class="row vh-100">
                <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
                    <div class="d-table-cell align-middle">

                        <div class="text-center mt-4 mb-3">
                            <!-- Division / DepEd Branding Logo -->
                            <img src="{{ asset('images/davao-del-sur-logo.png') }}" alt="DepEd Davao del Sur Logo"
                                class="img-fluid mb-3" style="max-height: 80px; width: auto;">

                            <h1 class="h2 font-weight-bold text-primary">Document Tracking System</h1>

                        </div>

                        <div class="card shadow-sm border-0">
                            <div class="card-body p-4 p-md-5">
                                @yield('content')
                            </div>
                        </div>

                        <div class="text-center text-muted mt-3">
                            <small>&copy; {{ date('Y') }} Division Office of Davao del Sur. All rights
                                reserved.</small>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- AdminKit JS -->
    <script src="{{ asset('static/js/app.js') }}"></script>
</body>

</html>
