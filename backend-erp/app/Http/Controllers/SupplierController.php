<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    // Get all suppliers
    public function index()
    {
        return response()->json(Supplier::all());
    }

    // Create supplier
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:suppliers,code',
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'category' => 'required|string',
            'rating' => 'nullable|numeric',
            'orders_count' => 'nullable|integer',
            'total_purchased' => 'nullable|numeric',
            'terms' => 'nullable|string',
            'delivery_days' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
        ]);

        $supplier = Supplier::create($validated);

        return response()->json([
            'message' => 'Supplier created successfully!',
            'data' => $supplier
        ], 201);
    }
}
