<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>E-Ticket {{ $ticket->ticket_code }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }

        .ticket {
            background: white;
            border: 2px solid #000;
            border-radius: 15px;
            padding: 20px;
            position: relative;
            margin-bottom: 20px;
        }

        .ticket-header {
            border-bottom: 2px dashed #ccc;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }

        .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }

        .event-title {
            font-size: 24px;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 10px;
        }

        .ticket-info {
            margin-bottom: 20px;
        }

        .info-row {
            margin-bottom: 10px;
        }

        .label {
            font-weight: bold;
            color: #666;
            font-size: 12px;
            display: block;
        }

        .value {
            color: #333;
            font-size: 14px;
        }

        .qr-section {
            text-align: center;
            margin: 20px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .qr-code img {
            max-width: 200px;
        }

        .ticket-footer {
            border-top: 2px dashed #ccc;
            padding-top: 15px;
            margin-top: 15px;
            font-size: 12px;
            color: #666;
        }

        .ticket-number {
            font-family: monospace;
            font-size: 14px;
            color: #999;
            margin-bottom: 10px;
        }

        .price-tag {
            font-size: 18px;
            color: #2196F3;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="ticket">
        <div class="ticket-header">
            @if ($logo)
                <img src="data:image/png;base64,{{ $logo }}" alt="Logo" class="logo">
            @endif
            <h1 class="event-title">{{ $ticket->event->title }}</h1>
            <p class="ticket-number">No. Tiket: {{ $ticket->ticket_code }}</p>
        </div>

        <div class="ticket-info">
            <div class="info-row">
                <span class="label">Nama Pemegang Tiket:</span>
                <span class="value">{{ $ticket->user->name }}</span>
            </div>
            <div class="info-row">
                <span class="label">Tanggal & Waktu:</span>
                <span class="value">{{ $ticket->event->event_date->isoFormat('dddd, D MMMM Y, HH:mm') }} WIB</span>
            </div>
            <div class="info-row">
                <span class="label">Lokasi:</span>
                <span class="value">{{ $ticket->event->location }}</span>
            </div>
            <div class="price-tag">
                Rp {{ number_format($ticket->event->price, 0, ',', '.') }}
            </div>
        </div>

        <div class="qr-section">
            <div class="qr-code">
                <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code">
                <p>Scan QR Code ini untuk validasi tiket</p>
            </div>
        </div>

        <div class="ticket-footer">
            <p><strong>Syarat dan Ketentuan:</strong></p>
            <ol>
                <li>Tiket ini hanya berlaku untuk 1 orang pada tanggal dan waktu yang tertera</li>
                <li>Mohon tiba 30 menit sebelum acara dimulai</li>
                <li>Tiket yang sudah dibeli tidak dapat dikembalikan atau ditukar</li>
                <li>Dilarang memperbanyak atau memalsukan tiket ini</li>
            </ol>
            <p style="text-align: center; margin-top: 20px; font-size: 10px;">
                Tiket diterbitkan oleh JelajahEvent<br>
                Dicetak pada: {{ now()->format('d/m/Y H:i') }} WIB
            </p>
        </div>
    </div>
</body>

</html>
