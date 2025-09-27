<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Contoh data dummy
        $stats = [
            'products' => 120,
            'suppliers' => 35,
            'orders' => 245,
        ];

        $orders = [
            ['id' => 'ORD-001', 'customer' => 'John Doe', 'status' => 'Completed', 'total' => 250000],
            ['id' => 'ORD-002', 'customer' => 'Jane Smith', 'status' => 'Pending', 'total' => 150000],
            ['id' => 'ORD-003', 'customer' => 'PT Abadi Jaya', 'status' => 'Cancelled', 'total' => 500000],
        ];

        return response()->json([
            'stats' => $stats,
            'orders' => $orders,
        ]);
    }
}
