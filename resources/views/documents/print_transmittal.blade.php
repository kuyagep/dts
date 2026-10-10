<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Document Transmittal Slip</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                font-size: 12pt;
                background: #fff !important;
            }

            .container {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }
        }

        .header-logo {
            max-height: 60px;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            width: 220px;
            display: inline-block;
            margin-top: 40px;
        }
    </style>
</head>

<body class="bg-light p-4">
    <div class="container bg-white p-5 border shadow-sm">
        <!-- Print Header Action Button -->
        <div class="d-flex justify-content-end mb-4 no-print">
            <button onclick="window.print()" class="btn btn-primary me-2">Print Transmittal</button>
            <button onclick="window.close()" class="btn btn-secondary">Close</button>
        </div>

        <!-- Official Header -->
        <div class="text-center mb-4 border-bottom pb-3">
            <h4 class="fw-bold text-uppercase mb-1">Division Document Tracking System (DDTS)</h4>
            <h5 class="fw-normal mb-0">DOCUMENT TRANSMITTAL SLIP</h5>
            <small class="text-muted">Date: {{ now()->format('F d, Y h:i A') }}</small>
        </div>

        <!-- Metadata -->
        <div class="row mb-4">
            <div class="col-6">
                <strong>Dispatching Office:</strong> {{ auth()->user()->office->name ?? 'N/A' }}<br>
                <strong>Released By:</strong> {{ auth()->user()->name }}
            </div>
            <div class="col-6 text-end">
                <strong>Total Items:</strong> {{ $documents->count() }}<br>
                <strong>Transmittal Ref:</strong> TR-{{ strtoupper(Str::random(8)) }}
            </div>
        </div>

        <!-- Document Table -->
        <table class="table table-bordered align-middle mb-5">
            <thead class="table-light">
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Tracking Number</th>
                    <th>Document Title</th>
                    <th>Type</th>
                    <th>Destination Office</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($documents as $index => $doc)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="fw-bold">{{ $doc->tracking_number }}</td>
                        <td>{{ $doc->title }}</td>
                        <td>{{ $doc->documentType->name ?? 'N/A' }}</td>
                        <td>{{ $doc->destinationOffice->name ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Signature Section -->
        <div class="row mt-5 pt-3">
            <div class="col-6 text-center">
                <p class="mb-0">Dispatched / Released By:</p>
                <div class="signature-line"></div>
                <p class="fw-bold mt-1 mb-0">{{ auth()->user()->name }}</p>
                <small class="text-muted">{{ auth()->user()->office->code ?? 'Sender Office' }}</small>
            </div>
            <div class="col-6 text-center">
                <p class="mb-0">Received By (Signature over Printed Name):</p>
                <div class="signature-line"></div>
                <p class="fw-bold mt-1 mb-0">___________________________</p>
                <small class="text-muted">Date & Time Received</small>
            </div>
        </div>
    </div>
</body>

</html>
