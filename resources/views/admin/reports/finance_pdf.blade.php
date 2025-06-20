<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }

        .date {
            text-align: right;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
        }

        .total {
            margin-top: 20px;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>{{ $title }}</h2>
    </div>

    <div class="date">
        Tanggal: {{ $date }}
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Transaksi</th>
                <th>Event</th>
                <th>Customer</th>
                <th>Tanggal Pembayaran</th>
                <th>Jumlah Tiket</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $index => $transaction)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $transaction->id }}</td>
                    <td>{{ $transaction->event->title }}</td>
                    <td>{{ $transaction->user->name }}</td>
                    <td>{{ $transaction->paid_at ? $transaction->paid_at->format('d/m/Y H:i') : '-' }}</td>
                    <td>{{ $transaction->quantity }}</td>
                    <td>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($transaction->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        <h3>Total Pendapatan: Rp {{ number_format($total, 0, ',', '.') }}</h3>
    </div>
</body>

</html>
