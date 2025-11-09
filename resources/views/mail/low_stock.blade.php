<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Low Stock Alert - SariTrack</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 640px;
            margin: 30px auto;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #2563eb;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .content {
            padding: 24px;
        }

        .content h2 {
            margin-bottom: 12px;
            color: #111827;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }

        .table th,
        .table td {
            border: 1px solid #e5e7eb;
            padding: 10px;
            text-align: left;
        }

        .table th {
            background-color: #f3f4f6;
        }

        .footer {
            background-color: #f9fafb;
            padding: 16px;
            text-align: center;
            font-size: 14px;
            color: #6b7280;
        }

        .highlight {
            color: #dc2626;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>SariTrack Alert</h1>
        </div>

        <div class="content">
            <h2>Low Stock Items Notification</h2>
            <p>Hi there {{ $user }},</p>
            <p>The following items are running low on stock. Please review and restock as needed:</p>

            <table class="table">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Current Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td>{{ $item->item_name }}</td>
                            <td class="highlight">{{ $item->batch_quantity }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align:center; color:#6b7280;">
                                All items are sufficiently stocked.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <p style="margin-top: 20px;">Keep your shelves stocked to avoid shortages!</p>
            <p>— The <strong>SariTrack</strong> Team</p>
        </div>

        <div class="footer">
            <p>This is an automated email. Please do not reply.</p>
        </div>
    </div>
</body>

</html>