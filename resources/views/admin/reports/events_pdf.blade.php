<!DOCTYPE html>
<html>

<head>
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
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
                <th>Nama Event</th>
                <th>Kategori</th>
                <th>Tanggal Event</th>
                <th>Lokasi</th>
                <th>Harga</th>
                <th>Kuota</th>
                <th>Total Pesanan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($events as $index => $event)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $event->title }}</td>
                    <td>{{ $event->category->name }}</td>
                    <td>{{ $event->event_date->format('d/m/Y H:i') }}</td>
                    <td>{{ $event->location }}</td>
                    <td>Rp {{ number_format($event->price, 0, ',', '.') }}</td>
                    <td>{{ $event->quota }}</td>
                    <td>{{ $event->orders_count }}</td>
                    <td>{{ ucfirst($event->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
