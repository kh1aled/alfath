<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SalesInvoice;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SalesDashboardController extends Controller
{
    /**
     * Get today's sales summary including:
     * - Total sales amount
     * - Average sale value
     * - Completed sales count
     * - Unpaid invoices (count & details)
     * - Pending orders (count & details)
     */
    public function summaryToday(Request $request)
    {
        $today = Carbon::today();

        $totalSales = Sale::whereDate('sale_date', $today)
            ->where('status', 'completed')
            ->sum('total_amount');

        $averageSale = Sale::whereDate('sale_date', $today)
            ->where('status', 'completed')
            ->avg('total_amount');

        $completedSalesCount = Sale::whereDate('sale_date', $today)
            ->where('status', 'completed')
            ->count();

        $unpaidInvoices = SalesInvoice::whereDate('invoice_date', $today)
            ->where('status', 'unpaid')
            ->orderBy('invoice_date', 'desc')
            ->get();

        $pendingOrders = SalesOrder::whereDate('order_date', $today)
            ->where('status', 'pending')
            ->get();

        return response()->json([
            'date' => $today->toDateString(),
            'total_sales' => round($totalSales, 2),
            'average_sale' => round($averageSale ?? 0, 2),
            'completed_sales_count' => $completedSalesCount,
            'pending_orders_count' => $pendingOrders->count(),
            'unpaid_invoices_count' => $unpaidInvoices->count(),
            'unpaid_invoices' => $unpaidInvoices,
            'pending_orders' => $pendingOrders,
        ]);
    }
}

/**
 * 
 * 
 * Result of summary today
 * {
  "date": "2025-10-21",
  "total_sales": 3269.29,
  "average_sale": 1634.65,
  "completed_sales_count": 2,
  "pending_orders_count": 1,
  "unpaid_invoices_count": 1,
  "unpaid_invoices": [
    {
      "id": 6,
      "invoice_number": "INV-20251021-01",
      "order_id": null,
      "customer_id": 5,
      "created_by": 33,
      "updated_by": 33,
      "invoice_date": "2025-10-21",
      "due_date": "2025-10-27",
      "status": "unpaid",
      "total_amount": "0.00",
      "paid_amount": "0.00",
      "notes": "لسه مدفعش",
      "created_at": "2025-10-21T13:32:14.000000Z",
      "updated_at": null
    }
  ],
  "pending_orders": [
    {
      "id": 1,
      "order_number": "12",
      "customer_id": 5,
      "quote_id": null,
      "created_by": null,
      "updated_by": null,
      "order_date": "2025-10-21",
      "status": "pending",
      "total_amount": "0.00",
      "notes": null,
      "created_at": null,
      "updated_at": null
    }
  ]
}

 * 
 * 
 */
