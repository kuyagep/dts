<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Document Transmittal Slip - Landscape</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Exact A4 Landscape Printable Area with Reserved Footer Margin */
        @page {
            size: A4 landscape;
            margin-top: 10mm;
            margin-left: 15mm;
            margin-right: 15mm;
            margin-bottom: 22mm;

            /* Left Footer: System Timestamp & User Metadata */
            @bottom-left {
                content: "DepEd DDTS System Generated Document | Generated on: {{ now()->format('F d, Y h:i:s A') }} | By: {{ auth()->user()->name }}";
                font-family: Arial, Helvetica, sans-serif;
                font-size: 8pt;
                color: #555;
            }

            /* Right Footer: Actual Page Number and Page Total */
            @bottom-right {
                content: "Page " counter(page) " of " counter(pages);
                font-family: Arial, Helvetica, sans-serif;
                font-size: 8pt;
                font-weight: bold;
                color: #555;
            }
        }

        html,
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            background-color: #fff !important;
            color: #000;
            padding: 0;
            margin: 0;
        }

        .transmittal-container {
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
            background: #fff;
        }

        /* Fixed Repeating Footer Container for standard browser print fallbacks */
        .print-footer {
            display: none;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                padding: 0 !important;
                margin: 0 !important;
            }

            .transmittal-container {
                padding: 0 !important;
                box-shadow: none !important;
            }

            .table-light {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .screen-footer {
                display: none !important;
            }

            /* Fixed footer bar for Chrome/Edge web print engine */
            .print-footer {
                display: flex !important;
                position: fixed;
                bottom: -15mm;
                left: 0;
                right: 0;
                width: 100%;
                border-top: 1px dashed #bbb;
                padding-top: 5px;
                font-size: 8pt;
                color: #555;
                background-color: #fff;
                justify-content: space-between;
                align-items: center;
            }

            .page-counter::after {
                content: "Page " counter(page);
            }
        }

        /* On-Screen Modal / Web View Footer */
        .screen-footer {
            border-top: 1px dashed #ccc;
            padding-top: 8px;
            font-size: 8.5pt;
            color: #666;
            margin-top: 30px;
        }

        .doc-table th,
        .doc-table td {
            font-size: 9.5pt;
            padding: 8px 10px;
        }

        .sig-cell {
            min-width: 180px;
            height: 38px;
        }
    </style>
</head>

<body class="bg-light p-3">
    <div class="transmittal-container bg-white p-4 rounded shadow-sm">

        <!-- Action Buttons (Screen Only) -->
        <div class="d-flex justify-content-end mb-3 no-print">
            <button onclick="window.print()" class="btn btn-primary me-2 shadow-sm">
                Print Transmittal
            </button>
            <button onclick="window.close()" class="btn btn-secondary">
                Close
            </button>
        </div>

        <!-- Header Block -->
        <div class="text-center mb-3 border-bottom pb-2">
            <h4 class="fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px;">Division Document Tracking System
                (DDTS)</h4>
            <h5 class="fw-bold text-primary mb-0">DOCUMENT TRANSMITTAL SLIP</h5>
            <span class="small text-muted">{{ auth()->user()->office->name ?? 'N/A' }}</span>
        </div>
        <!-- Document Table -->
        <table class="table table-bordered align-middle doc-table mb-4">
            <thead class="table-light">
                <tr class="text-uppercase text-secondary small">
                    <th style="width: 30px;" class="text-center">#</th>
                    <th style="width: 130px;">Tracking No.</th>
                    <th style="width: 130px;">Category</th>
                    <th>Subject Matter</th>
                    <th style="width: 220px;">Destination Office</th>
                    <th style="width: 220px;">Name,Date & Signature</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($documents as $index => $doc)
                    <tr>
                        <td class="text-center fw-bold">{{ $index + 1 }}</td>
                        <td class="fw-bold font-monospace text-primary">{{ $doc->tracking_number }}</td>
                        <td>{{ $doc->documentType->name ?? 'N/A' }}</td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $doc->title }}</div>
                            @if ($doc->description)
                                <div class="text-muted small text-truncate" style="max-width: 300px;"
                                    title="{{ $doc->description }}">
                                    {{ $doc->description }}
                                </div>
                            @endif
                        </td>
                        <td>{{ $doc->destinationOffice->name ?? 'N/A' }}</td>
                        <td class="sig-cell"></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No documents included in this transmittal
                            batch.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>



        <!-- On-Screen Preview Footer -->
        <div class="screen-footer d-flex justify-content-between align-items-center no-print">
            <div>
                <strong>System Generated Document</strong> |
                {{ now()->format('F d, Y h:i:s A') }} | By: {{ auth()->user()->name }}
            </div>
            <div>
                <span class="badge bg-light text-dark border">Page Preview</span>
            </div>
        </div>

    </div>
</body>

</html>
