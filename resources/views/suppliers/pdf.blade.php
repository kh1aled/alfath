<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: #fff;
        }

        .meta-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding: 10px 0;
            font-size: 11px;
            color: #6b7280;
        }

        .table-container {
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
        }

        th {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            color: #374151;
            font-weight: 600;
            padding: 12px 8px;
            text-align: left;
            border: 1px solid #e5e7eb;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 10px 8px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
            font-size: 10px;
        }

        tr:nth-child(even) {
            background-color: #f9fafb;
        }

        tr:hover {
            background-color: #f3f4f6;
        }

        .id-column {
            width: 50px;
            text-align: center;
            font-weight: 600;
            color: #4b5563;
        }

        .name-column {
            width: 120px;
            font-weight: 600;
            color: #111827;
        }

        .email-column {
            width: 140px;
            color: #3b82f6;
        }

        .phone-column {
            width: 100px;
        }

        .address-column {
            width: 150px;
        }

        .location-column {
            width: 80px;
        }

        .date-column {
            width: 80px;
            font-size: 9px;
            color: #6b7280;
        }

        .page-number::after {
            content: counter(page);
        }

        .total-count {
            background: #dbeafe;
            color: #1e40af;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 12px;
            margin-bottom: 15px;
            display: inline-block;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #6b7280;
            font-style: italic;
        }

        @media print {
            .header {
                page-break-inside: avoid;
            }

            table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
            }
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50px;
            background: #f8fafc;
            border-top: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            font-size: 10px;
            color: #6b7280;
        }
    </style>
</head>

<body>
    <x-report-header title="Supplier Report" subtitle="Complete list of suppliers and their information" />

    <div class="meta-info">
        <div>
            <strong>Generated:</strong> {{ date('Y-m-d H:i:s') }}
        </div>
        <div>
            <strong>Total Suppliers:</strong> {{ count($suppliers) }}
        </div>
    </div>

    <div class="table-container">
        <div class="total-count">
            Total: {{ count($suppliers) }} suppliers
        </div>

        @if (count($suppliers) > 0)
            <table>
                <thead>
                    <tr>
                        <th class="id-column">ID</th>
                        <th class="name-column">Name</th>
                        <th class="email-column">Email</th>
                        <th class="phone-column">Phone</th>
                        <th class="address-column">Address</th>
                        <th class="location-column">Country</th>
                        <th class="location-column">City</th>
                        <th class="location-column">Zip Code</th>
                        <th class="date-column">Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($suppliers as $supplier)
                        <tr>
                            <td class="id-column">{{ $supplier->id }}</td>
                            <td class="name-column">{{ $supplier->name }}</td>
                            <td class="email-column">{{ $supplier->email }}</td>
                            <td class="phone-column">{{ $supplier->phone->phone_number ?? 'N/A' }}</td>
                            <td class="address-column">{{ $supplier->address ?? 'N/A' }}</td>
                            <td class="location-column">{{ $supplier->country ?? 'N/A' }}</td>
                            <td class="location-column">{{ $supplier->city ?? 'N/A' }}</td>
                            <td class="location-column">{{ $supplier->zip_code ?? 'N/A' }}</td>
                            <td class="date-column">{{ $supplier->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">
                No suppliers found.
            </div>
        @endif
    </div>


    <div class="footer">
        <div>© {{ date('Y') }} Al_Fath. All rights reserved.</div>
        <div>Page <span class="page-number"></span></div>
    </div>
</body>

</html>
