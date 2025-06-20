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
                <th>Kode Order</th>
                <th>Event</th>
                <th>Customer</th>
                <th>Jumlah Tiket</th>
                <th>Total Harga</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $index => $order)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $order->order_code }}</td>
                    <td>{{ $order->event->title }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>{{ $order->quantity }}</td>
                    <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        <h3>Total Penjualan: Rp {{ number_format($total, 0, ',', '.') }}</h3>
    </div>
</body>

</html>
