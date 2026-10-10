<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Authentication') | Division Document Tracking System</title>

    <!-- AdminKit CSS -->
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
                            <h1 class="h2 font-weight-bold text-primary">Division DocTracker</h1>
                            <p class="lead text-muted">
                                Document Tracking & Routing Management System
                            </p>
                        </div>

                        <div class="card shadow-sm border-0">
                            <div class="card-body p-4 p-md-5">
                                @yield('content')
                            </div>
                        </div>

                        <div class="text-center text-muted mt-3">
                            <small>&copy; {{ date('Y') }} Division Office. All rights reserved.</small>
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
