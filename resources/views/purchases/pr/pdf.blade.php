<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Purchase Requisitions Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            background: #fff;
        }

        .report-header {
            text-align: center;
            padding: 20px 0 10px;
        }

        .report-header h1 {
            font-size: 20px;
            margin-bottom: 5px;
        }

        .report-meta {
            display: flex;
            justify-content: space-between;
            padding: 0 10px;
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 10px;
        }

        .total-count {
            background: #dbeafe;
            color: #1e40af;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 12px;
            margin: 10px;
            display: inline-block;
        }

        .table-container { padding: 0 10px; }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        th {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            color: #374151;
            font-weight: 600;
            padding: 10px 6px;
            text-align: left;
            border: 1px solid #e5e7eb;
            text-transform: uppercase;
            font-size: 9px;
        }

        td {
            padding: 8px 6px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
            font-size: 10px;
        }

        tr:nth-child(even) { background-color: #f9fafb; }
        tr:hover { background-color: #f3f4f6; }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #6b7280;
            font-style: italic;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 40px;
            background: #f8fafc;
            border-top: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            font-size: 10px;
            color: #6b7280;
        }

        @media print {
            thead { display: table-header-group; }
            tfoot { display: table-footer-group; }
            table, tr, td, th { page-break-inside: avoid; text-align: center }
        }
    </style>
</head>

<body>

    <div class="report-header">
        <h1>Purchase Requisitions Report</h1>
        <div class="report-meta">
            <div><strong>Generated:</strong> {{ now()->format('Y-m-d H:i:s') }}</div>
            <div><strong>Total Requests:</strong> {{ count($PR) }}</div>
        </div>
    </div>

    <div class="table-container">
        <div class="total-count">
            Total: {{ count($PR) }} requests
        </div>

        @if (count($PR) > 0)
            <table>
                <thead>
                    <tr>
                        <th>الكود</th>
                        <th>مقدم الطلب</th>
                        <th>الأولوية</th>
                        <th>مطلوب بحلول</th>
                        <th>الحالة</th>
                        <th>تاريخ الإنشاء</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($PR as $req)
                        <tr>
                            <td>{{ $req->code ?? '-' }}</td>
                            <td>{{ $req->requester?->name ?? '-' }}</td>
                            <td>{{ $req->priority ?? '-' }}</td>
                            <td>{{ $req->needed_by_date?->format('Y-m-d') ?? '-' }}</td>
                            <td>{{ $req->status ?? '-' }}</td>
                            <td>{{ $req->created_at?->format('Y-m-d') ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">
                No purchase requisitions found.
            </div>
        @endif
    </div>

    <div class="footer">
        <div>© {{ date('Y') }} Al_Fath. All rights reserved.</div>
        <div>Page <span class="page-number"></span></div>
    </div>

</body>
</html>
