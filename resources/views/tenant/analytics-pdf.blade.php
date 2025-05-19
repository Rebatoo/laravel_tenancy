<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Analytics Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .date {
            text-align: right;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Analytics Report</h1>
        <div class="date">Generated on: {{ now()->format('F d, Y') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Worker</th>
                <th>Laundry Type</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laundryLogs as $log)
            <tr>
                <td>{{ $log->id }}</td>
                <td>{{ $log->customer->name }}</td>
                <td>{{ $log->worker->name }}</td>
                <td>{{ $log->laundry_type }}</td>
                <td>₱{{ number_format($log->total_price, 2) }}</td>
                <td>{{ $log->status }}</td>
                <td>{{ $log->created_at->format('M d, Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 