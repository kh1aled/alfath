<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Report</title>
    <style>
        body {
            font-family: 'cairo';
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .report-title {
            text-align: start;
            margin: 20px 0;
            font-size: 18px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .footer {
            position: fixed;
            bottom: -30px;
            left: 0;
            right: 0;
            height: 30px;
            text-align: center;
            font-size: 11px;
            color: #555;
        }
    </style>
</head>
<body>

    <div class="report-title">Customers Report</div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Country</th>
                <th>City</th>
                <th>Address</th>
                <th>Zip Code</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customers as $customer)
            <tr>
                <td>{{ $customer->id }}</td>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->email }}</td>
                <td>{{ $customer->phone?->phone_number }}</td>
                <td>{{ $customer->country }}</td>
                <td>{{ $customer->city }}</td>
                <td>{{ $customer->address }}</td>
                <td>{{ $customer->zip_code }}</td>
                <td>{{ $customer->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>


</body>
</html>
