<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function salesReport(Request $request)
    {
        $from = $request->query('from');
        $to   = $request->query('to');

        $sales = Order::whereBetween('order_date', [$from, $to])
            ->select(
                DB::raw('DATE(order_date) as date'),
                DB::raw('SUM(total_amount) as total_sales')
            )
            ->groupBy('date')
            ->get();

        return response()->json($sales);
    }

    public function purchaseReport(Request $request)
    {
        $from = $request->query('from');
        $to   = $request->query('to');

        $purchases = Purchase::whereBetween('purchase_date', [$from, $to])
            ->select(
                DB::raw('DATE(purchase_date) as date'),
                DB::raw('SUM(total_amount) as total_purchases')
            )
            ->groupBy('date')
            ->get();

        return response()->json($purchases);
    }
}
