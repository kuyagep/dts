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
                        Point your device camera at the <strong>QR Code</strong> on the physical transmittal
                        slip.
                    </p>

                    <!-- Camera Stream Container -->
                    <div id="qr-reader" class="border rounded bg-dark overflow-hidden mx-auto"
                        style="width: 100%; max-width: 380px; min-height: 280px;"></div>
                    <div id="qr-camera-status" class="text-danger small mt-2 d-none"></div>

                    <!-- Manual Code Fallback Input -->
                    <div class="mt-4 pt-3 border-top">
                        <label for="manualBatchInput" class="form-label small fw-bold text-muted">Or enter Batch Code
                            manually:</label>
                        <div class="input-group">
                            <input type="text" id="manualBatchInput" autofocus
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
            const statusDiv = document.getElementById('qr-camera-status');
            let html5QrCode = null;

            if (!qrModal) return;

            qrModal.addEventListener('shown.bs.modal', function() {
                if (statusDiv) {
                    statusDiv.classList.add('d-none');
                    statusDiv.textContent = '';
                }

                // Initialize instance
                if (!html5QrCode) {
                    html5QrCode = new Html5Qrcode("qr-reader");
                }

                const config = {
                    fps: 10,
                    qrbox: {
                        width: 220,
                        height: 220
                    },
                    aspectRatio: 1.0
                };

                // Try using back camera first; fall back to any available camera
                html5QrCode.start({
                        facingMode: "environment"
                    },
                    config,
                    onScanSuccess
                ).catch(err => {
                    console.warn("Back camera failed, trying default camera...", err);

                    // Fallback: Start with user/default camera
                    html5QrCode.start({
                            facingMode: "user"
                        },
                        config,
                        onScanSuccess
                    ).catch(fatalErr => {
                        console.error("Camera start failed:", fatalErr);
                        if (statusDiv) {
                            statusDiv.classList.remove('d-none');
                            if (location.protocol !== 'https:' && location.hostname !==
                                'localhost' && location.hostname !== '127.0.0.1') {
                                statusDiv.innerHTML =
                                    "<strong>Camera blocked:</strong> Web cameras require <strong>HTTPS</strong> or <code>localhost</code>.";
                            } else {
                                statusDiv.innerHTML =
                                    "<strong>Camera access failed:</strong> Please allow camera permissions in your browser.";
                            }
                        }
                    });
                });
            });

            // Cleanup on Modal Close
            qrModal.addEventListener('hidden.bs.modal', function() {
                if (html5QrCode && html5QrCode.isScanning) {
                    html5QrCode.stop().then(() => {
                        html5QrCode.clear();
                    }).catch(err => console.error("Error stopping scanner:", err));
                }
            });

            function onScanSuccess(decodedText) {
                if (!decodedText) return;

                if (html5QrCode && html5QrCode.isScanning) {
                    html5QrCode.stop().then(() => processScannedText(decodedText));
                } else {
                    processScannedText(decodedText);
                }
            }

            function processScannedText(scannedText) {
                const cleanText = scannedText.trim();
                if (cleanText.startsWith('http://') || cleanText.startsWith('https://')) {
                    window.location.href = cleanText;
                } else {
                    window.location.href = "/batches/receive/" + encodeURIComponent(cleanText);
                }
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let scannedBuffer = "";
            let scanTimeout = null;

            // Listen for global keypresses (USB Scanner acts as high-speed keyboard input)
            document.addEventListener('keydown', function(e) {
                // Ignore keypresses if the user is actively typing inside an input/textarea
                const activeElem = document.activeElement;
                const isInputField = activeElem && (
                    activeElem.tagName === 'INPUT' ||
                    activeElem.tagName === 'TEXTAREA' ||
                    activeElem.tagName === 'SELECT'
                );

                // Allow typing inside designated manual search inputs
                if (isInputField && activeElem.id !== 'globalUsbScanInput') {
                    return;
                }

                // When the USB scanner sends 'Enter' (Keycode 13), process the buffered string
                if (e.key === 'Enter') {
                    if (scannedBuffer.length > 3) {
                        e.preventDefault();
                        processScannedBatchCode(scannedBuffer.trim());
                        scannedBuffer = "";
                    }
                    return;
                }

                // Accumulate scanned characters (skipping modifier keys)
                if (e.key.length === 1) {
                    scannedBuffer += e.key;

                    // Reset buffer if user typing is too slow (human speed vs USB scanner speed)
                    clearTimeout(scanTimeout);
                    scanTimeout = setTimeout(() => {
                        scannedBuffer = "";
                    }, 100); // USB scanners enter all chars in under 50ms
                }
            });

            /**
             * Redirects to batch processing route based on scanned input
             */
            function processScannedBatchCode(scannedText) {
                if (!scannedText) return;

                // Clean up scanner prefixes/suffixes if present
                let cleanText = scannedText.trim();

                // 1. If scanned text is a full URL (e.g. http://ddts.test/batches/receive/TR-20261011-89A2)
                if (cleanText.startsWith('http://') || cleanText.startsWith('https://')) {
                    window.location.href = cleanText;
                    return;
                }

                // 2. If scanned text is a raw batch number (e.g. TR-20261011-89A2)
                window.location.href = "/batches/receive/" + encodeURIComponent(cleanText);
            }
        });
    </script>

    {{-- <script>
        // Auto-submit USB Input when Enter is pressed in the navbar field
        document.getElementById('globalUsbScanInput')?.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const code = this.value.trim();
                if (code) {
                    if (code.startsWith('http://') || code.startsWith('https://')) {
                        window.location.href = code;
                    } else {
                        window.location.href = "/batches/receive/" + encodeURIComponent(code);
                    }
                }
            }
        });
    </script> --}}
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
