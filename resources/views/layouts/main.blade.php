<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="Geperson Mamalias">
    <title>@yield('title', 'Dashboard') | Division Document Tracking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- AdminKit CSS -->
    <link href="{{ asset('static/css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    @stack('styles')
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        @include('layouts.partials.sidebar')

        <div class="main">
            <!-- Navbar -->
            @include('layouts.partials.navbar')

            <!-- Main Content Area -->
            <main class="content">
                <div class="container-fluid p-0">

                    @yield('content')
                </div>
            </main>

            <!-- Footer -->
            @include('layouts.partials.footer')

        </div>
    </div>
    <!-- Global QR Camera Scanner Modal -->
    <div class="modal fade" id="qrScanModal" tabindex="-1" aria-labelledby="qrScanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="qrScanModalLabel">
                        <i data-feather="qr-code" class="me-1"></i> Scan Batch Transmittal QR Code
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"
                        id="closeQrModalBtn"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <p class="text-muted small mb-3">
                        Point your device camera at the <strong>Master QR Code</strong> on the physical transmittal
                        slip.
                    </p>

                    <!-- Camera Stream Container -->
                    <div id="qr-reader" class="border rounded bg-dark overflow-hidden mx-auto"
                        style="width: 100%; max-width: 380px;"></div>

                    <!-- Manual Code Fallback Input -->
                    <div class="mt-4 pt-3 border-top">
                        <label for="manualBatchInput" class="form-label small fw-bold text-muted">Or enter Batch Code
                            manually:</label>
                        <div class="input-group">
                            <input type="text" id="manualBatchInput"
                                class="form-control text-uppercase font-monospace" placeholder="e.g. TR-20261011-89A2">
                            <button type="button" id="manualBatchBtn" class="btn btn-secondary">Open Batch</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- AdminKit JS -->
    <script src="{{ asset('static/js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- html5-qrcode Library for Web Camera Access -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const qrModal = document.getElementById('qrScanModal');
            let html5QrCode = null;

            if (qrModal) {
                // Start Camera when Modal Opens
                qrModal.addEventListener('shown.bs.modal', function() {
                    html5QrCode = new Html5Qrcode("qr-reader");

                    const config = {
                        fps: 10,
                        qrbox: {
                            width: 250,
                            height: 250
                        }
                    };

                    html5QrCode.start({
                            facingMode: "environment"
                        }, // Prefers rear camera on mobile
                        config,
                        onScanSuccess
                    ).catch(err => {
                        console.warn("Camera access denied or unreadable:", err);
                    });
                });

                // Stop Camera when Modal Closes
                qrModal.addEventListener('hidden.bs.modal', function() {
                    if (html5QrCode && html5QrCode.isScanning) {
                        html5QrCode.stop().then(() => html5QrCode.clear()).catch(err => console.error(err));
                    }
                });
            }

            // Success Handler when QR Code is read
            function onScanSuccess(decodedText) {
                if (decodedText) {
                    // Stop scanning immediately
                    if (html5QrCode) {
                        html5QrCode.stop().then(() => {
                            // Redirect to scanned URL (e.g., route('batches.receive.view'))
                            window.location.href = decodedText;
                        });
                    } else {
                        window.location.href = decodedText;
                    }
                }
            }

            // Manual Input Handler
            const manualBtn = document.getElementById('manualBatchBtn');
            if (manualBtn) {
                manualBtn.addEventListener('click', function() {
                    const batchNum = document.getElementById('manualBatchInput').value.trim();
                    if (batchNum) {
                        window.location.href = "/batches/receive/" + encodeURIComponent(batchNum);
                    }
                });
            }
        });
    </script>
    <script>
        // Global Toastr Configuration
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "4000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        // Global Flash Notifications
        @if (session('success'))
            toastr.success("{{ session('success') }}", "Success");
        @endif

        @if (session('error'))
            toastr.error("{{ session('error') }}", "Error");
        @endif

        @if (session('warning'))
            toastr.warning("{{ session('warning') }}", "Warning");
        @endif

        @if (session('info'))
            toastr.info("{{ session('info') }}", "Information");
        @endif

        // Handle Laravel Validation Errors automatically
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}", "Validation Error");
            @endforeach
        @endif
    </script>
    @stack('scripts')
</body>

</html>
