<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Batch Transmittal Slip - {{ $batch->batch_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body class="bg-light p-4" onload="window.print()">
    <div class="container bg-white p-5 border shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <div>
                <h4 class="fw-bold mb-0 text-uppercase">Division Document Tracking System</h4>
                <h5 class="text-primary mb-0">BATCH TRANSMITTAL SLIP</h5>
                <small class="text-muted">Ref: {{ $batch->batch_number }} | Dispatched:
                    {{ $batch->created_at->format('M d, Y h:i A') }}</small>
            </div>
            <!-- Master QR Code for Receiving Office Scanning -->
            <div class="text-center p-2 bg-white border rounded">
                {!! QrCode::size(120)->generate($batch->batch_number) !!}
                <small class="d-block text-muted font-monospace mt-1">SCAN TO RECEIVE</small>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-6">
                <strong>From Office:</strong> {{ $batch->fromOffice->name }}<br>
                <strong>Released By:</strong> {{ $batch->dispatcher->name }}
            </div>
            <div class="col-6 text-end">
                <strong>To Destination Office:</strong> {{ $batch->toOffice->name }}<br>
                <strong>Total Attached Items:</strong> {{ $batch->routes->count() }} Documents
            </div>
        </div>

        <!-- Document Manifest Table -->
        <table class="table table-bordered align-middle mb-4">
            <thead class="table-light">
                <tr>
                    <th style="width: 40px;">#</th>
                    <th>Tracking Number</th>
                    <th>Document Title</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($batch->routes as $index => $route)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="fw-bold text-primary">{{ $route->document->tracking_number }}</td>
                        <td>{{ $route->document->title }}</td>
                        <td>{{ $route->document->documentType->name ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="row mt-5 pt-3">
            <div class="col-6 text-center">
                <p class="mb-0">Released / Delivered By:</p>
                <div style="border-bottom: 1px solid #000; width: 200px; margin: 40px auto 5px;"></div>
                <p class="fw-bold mb-0">{{ $batch->dispatcher->name }}</p>
            </div>
            <div class="col-6 text-center">
                <p class="mb-0">Received By (Destination Personnel):</p>
                <div style="border-bottom: 1px solid #000; width: 200px; margin: 40px auto 5px;"></div>
                <p class="fw-bold mb-0">Signature over Printed Name</p>
            </div>
        </div>
    </div>
</body>

</html>
