@php
    // use App\Services\QrCodeService;
    // // Generate full receive URL so scanning redirects directly to verification page
    // $receiveUrl = route('batches.receive.view', $batch->batch_number);
    // $qr = QrCodeService::generate($receiveUrl);
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Transmittal - {{ $batch->batch_number }}</title>
    <style>
        /* Define Exact A4 Bounds with Reserved Footer Space */
        @page {
            size: A4 portrait;
            margin-top: 12mm;
            margin-left: 15mm;
            margin-right: 15mm;
            margin-bottom: 8mm;
            /* Reserves bottom area specifically for fixed footer */
        }

        html,
        body {
            font-family: "Bookman Old Style", Times, serif;
            font-size: 11pt;
            line-height: 1.3;
            color: #000;

            position: relative;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            /* Prevents signature overlap with footer */
            box-sizing: border-box;
        }

        /* Updated Official DepEd Header with Logo Support */
        .header-container {
            position: relative;
            text-align: center;
            margin-bottom: 25px;
            min-height: 80px;
        }

        .header-logo {
            width: 75px;
            height: 75px;
            display: block;
            margin: 0 auto 8px auto;
            /* Centers logo directly above Republic of the Philippines */
            object-fit: contain;
        }

        .republic {
            font-size: 11pt;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .deped-main {
            font-size: 14pt;
            font-weight: bold;
            color: #000000;
            text-transform: uppercase;
            /* DepEd Blue */
            margin-bottom: 2px;
        }

        .region-division {
            font-size: 11pt;
            font-style: italic;
        }

        /* QR Code Positioning */
        .qr-container {
            position: absolute;
            top: 60px;
            right: 0;
            text-align: center;
        }

        .qr-container img {
            width: 90px;
            height: 90px;
        }

        .qr-text {
            font-size: 8pt;
            font-family: Arial, sans-serif;
            font-weight: bold;
            margin-top: 2px;
        }

        .title-block {
            text-align: center;
            margin: 25px 0;
            text-decoration: underline;
            font-weight: bold;
            font-size: 14pt;
        }

        /* Details Table */
        .info-section {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-section td {
            vertical-align: top;
            padding: 3px 0;
        }

        /* Document Table */
        .doc-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .doc-table th,
        .doc-table td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 11pt;
        }

        .doc-table th {
            background-color: #f2f2f2;
            text-transform: uppercase;
        }

        /* Signatures */
        .sig-container {
            margin-top: 50px;
            width: 100%;
        }

        .sig-box {
            width: 45%;
            display: inline-block;
            vertical-align: top;
        }

        .sig-line {
            margin-top: 40px;
            border-top: 1px solid #000;
            width: 90%;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            padding-top: 3px;
        }

        .sig-sub {
            text-align: center;
            width: 90%;
            font-size: 10pt;
        }

        /* Fixed Official Footer Styling */
        .transmittal-footer {
            position: fixed;
            bottom: -22mm;
            left: 0;
            right: 0;
            width: 100%;
            border-top: 1px dashed #777;
            padding-top: 10px;
            background-color: #fff;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-table td {
            vertical-align: middle;
            border: none;
            padding: 0;
        }

        .footer-logos {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .footer-logo-img {
            height: 52px;
            width: auto;
            object-fit: contain;
        }

        .footer-info {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8.5pt;
            color: #000;
            line-height: 1.35;
        }

        .footer-info strong {
            font-weight: bold;
        }

        @media print {
            .transmittal-footer {
                position: fixed;
                bottom: 0;
            }
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <!-- Floating Print Button for Screen Preview -->
    <div class="no-print" style="position: fixed; top: 15px; left: 15px;">
        <button onclick="window.print()"
            style="padding: 8px 16px; background: #0038A8; color: #fff; border: none; border-radius: 4px; cursor: pointer;">
            Print Transmittal
        </button>
    </div>

    <div class="qr-container">
        {!! QrCode::size(90)->generate($batch->batch_number) !!}
        <div class="qr-text">{{ $batch->batch_number }}</div>
    </div>

    <!-- DepEd Header Section with Logo -->
    <div class="header-container">
        <!-- Official Logo centered above 'Republic of the Philippines' -->
        <img src="{{ asset('images/logo.png') }}" class="header-logo" alt="DepEd Logo">

        <div class="republic">Republic of the Philippines</div>
        <div class="deped-main">Department of Education</div>
        <div class="region-division">
            REGION XI<br>
            SCHOOLS DIVISION OF DAVAO DEL SUR<br>
            <strong>{{ $batch->fromOffice->name ?? 'ORIGIN OFFICE' }}</strong>
        </div>
    </div>

    <hr style="border: 1px solid #000;">

    <div class="title-block">TRANSMITTAL</div>

    <table class="info-section">
        <tr>
            <td width="15%"><strong>TO:</strong></td>
            <td><strong>{{ strtoupper($batch->toOffice->name ?? 'DESTINATION OFFICE') }}</strong></td>
        </tr>
        <tr>
            <td><strong>FROM:</strong></td>
            <td><strong>{{ strtoupper($batch->fromOffice->name ?? 'ORIGIN OFFICE') }}</strong></td>
        </tr>
        <tr>
            <td><strong>DATE:</strong></td>
            <td>{{ now()->format('d F Y') }}</td>
        </tr>
        <tr>
            <td><strong>SUBJECT:</strong></td>
            <td><strong>SUBMISSION OF DOCUMENTS ({{ $batch->batch_number }})</strong></td>
        </tr>
    </table>

    <p style="text-indent: 50px;">
        Respectfully transmitted herewith are the following documents for your information, review, and appropriate
        action:
    </p>

    <table class="doc-table">
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th width="25%">Tracking No.</th>
                <th>Description / Document Title</th>
                <th width="15%">Remarks</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($batch->routes as $route)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td style="text-align: center;"><strong>{{ $route->document->tracking_number ?? 'N/A' }}</strong>
                    </td>
                    <td>{{ $route->document->title ?? 'N/A' }}</td>
                    <td>{{ $route->remarks ?? '' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">No documents attached to this batch.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p style="margin-top: 20px;">Total Number of Documents: <strong>{{ $batch->routes->count() }}</strong></p>

    <div class="sig-container">
        <div class="sig-box">
            <p>Prepared by:</p>
            <div class="sig-line">
                {{ $batch->dispatcher->name ?? (auth()->user()->name ?? 'Office Personnel') }}
            </div>
            <div class="sig-sub">Signature over Printed Name</div>
            <div class="sig-sub">Date: {{ $batch->created_at ? $batch->created_at->format('m/d/Y') : '___________' }}
            </div>
        </div>

        <div class="sig-box" style="float: right;">
            <p>Received by:</p>
            <div class="sig-line">&nbsp;</div>
            <div class="sig-sub">Signature over Printed Name</div>
            <div class="sig-sub">Date & Time: ______________</div>
        </div>
    </div>

    <!-- Official DepEd SDO Davao del Sur Footer -->
    <div class="transmittal-footer">
        <table class="footer-table">
            <tr>
                <!-- Left: Logos Group -->
                <td width="50%">
                    <div class="footer-logos">
                        <img src="{{ asset('images/deped-logo.png') }}" class="footer-logo-img" alt="DepEd Logo">
                        <img src="{{ asset('images/bagong-pilipinas.png') }}" class="footer-logo-img"
                            alt="Bagong Pilipinas Logo">
                        <img src="{{ asset('images/davao-del-sur-logo.png') }}" class="footer-logo-img"
                            alt="Division of Davao del Sur Logo">
                    </div>
                </td>

                <!-- Right: Contact Information -->
                <td width="50%" style="text-align: left;">
                    <div class="footer-info">
                        <strong>Address:</strong> Northern Paligue, Padada, Davao del Sur<br>
                        <strong>Email Address:</strong> davaodelsur.division@deped.gov.ph<br>
                        <strong>Website:</strong> www.deped-davaodelsur.com<br>
                        <strong>SDO-Direct Line:</strong> (082)272-4936
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
