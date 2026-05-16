<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Participation Certificate</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #f5f5f5;
        }

        .certificate {
            width: 297mm;
            height: 210mm;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
            page-break-after: always;
        }

        .certificate-border {
            position: absolute;
            top: 15px;
            left: 15px;
            right: 15px;
            bottom: 15px;
            border: 3px solid #fff;
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.03;
            font-size: 200px;
            color: #000;
            pointer-events: none;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .organization-name {
            font-size: 28px;
            font-weight: bold;
            color: #1a237e;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }

        .certificate-title {
            font-size: 48px;
            font-weight: bold;
            color: #667eea;
            text-transform: uppercase;
            letter-spacing: 5px;
            margin: 10px 0;
        }

        .certificate-body {
            text-align: center;
            margin: 30px 0;
        }

        .recipient-name {
            font-size: 36px;
            font-weight: bold;
            color: #d4af37;
            margin: 20px 0;
            text-transform: uppercase;
        }

        .description {
            font-size: 18px;
            line-height: 1.6;
            color: #333;
            margin: 20px 0;
        }

        .event-details {
            font-size: 16px;
            color: #666;
            margin: 15px 0;
        }

        .signature-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 60px;
            width: 100%;
        }

        .signature {
            text-align: center;
        }

        .signature-line {
            width: 200px;
            border-top: 2px solid #333;
            margin-bottom: 5px;
        }

        .qr-code {
            position: absolute;
            bottom: 20px;
            right: 20px;
            width: 100px;
            height: 100px;
        }

        .certificate-number {
            position: absolute;
            bottom: 20px;
            left: 20px;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="certificate-border">
            <div class="watermark">RSCB</div>

            <div class="header">
                <div class="organization-name">
                    Regional Sports Control Board
                </div>
                <div style="font-size: 16px; color: #666;">
                    Ministry of Civil Aviation, Government of India
                </div>
            </div>

            <div class="certificate-title">
                Certificate of Participation
            </div>

            <div class="certificate-body">
                <div class="description">
                    This is to certify that
                </div>

                <div class="recipient-name">
                    {{ $registration->employee->name }}
                </div>

                <div class="description">
                    Employee ID: {{ $registration->employee->employee_id }}<br>
                    {{ $registration->employee->designation }}<br>
                    {{ $registration->employee->airport->name ?? '' }}
                </div>

                <div class="description">
                    has participated in the
                </div>

                <div class="event-details">
                    <strong>{{ $registration->event->event_name }}</strong><br>
                    held at {{ $registration->event->venue }}<br>
                    from {{ $registration->event->start_date->format('d F Y') }}
                    to {{ $registration->event->end_date->format('d F Y') }}
                </div>

                <div class="description" style="font-size: 14px; color: #888; margin-top: 30px;">
                    We appreciate your enthusiasm and sportsmanship spirit.
                </div>
            </div>

            <div class="signature-section">
                <div class="signature">
                    <div class="signature-line"></div>
                    <div style="font-size: 12px;">Sports Secretary</div>
                </div>

                <div style="text-align: center;">
                    <div style="font-size: 14px; color: #666;">
                        Date of Issue: {{ $issueDate->format('d F Y') }}
                    </div>
                </div>

                <div class="signature">
                    <div class="signature-line"></div>
                    <div style="font-size: 12px;">Regional Director</div>
                </div>
            </div>

            <div class="certificate-number">
                Certificate No: {{ $certificateNumber }}
            </div>

            @if($qrCodePath)
                <div class="qr-code">
                    <img src="{{ public_path('storage/' . $qrCodePath) }}"
                         alt="QR Code"
                         style="width: 100%; height: 100%;">
                </div>
            @endif
        </div>
    </div>
</body>
</html>
