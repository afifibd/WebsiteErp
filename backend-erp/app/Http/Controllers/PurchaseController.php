<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index()
    {
        return response()->json(Purchase::with(['supplier', 'items.product'])->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,supplier_id',
            'purchase_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,product_id',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.cost'       => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {
            $purchase = Purchase::create([
                'supplier_id'   => $request->supplier_id,
                'purchase_date' => $request->purchase_date,
                'total_amount'  => 0,
                'status'        => 'ordered',
            ]);

            $total = 0;
            foreach ($request->items as $item) {
                $subtotal = $item['quantity'] * $item['cost'];
                PurchaseItem::create([
                    'purchase_id' => $purchase->purchase_id,
                    'product_id'  => $item['product_id'],
                    'quantity'    => $item['quantity'],
                    'cost'        => $item['cost'],
                    'subtotal'    => $subtotal,
                ]);
                $total += $subtotal;
            }

            $purchase->update(['total_amount' => $total]);

            DB::commit();
            return response()->json($purchase->load('items.product'), 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        return response()->json(Purchase::with(['supplier', 'items.product'])->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $purchase = Purchase::findOrFail($id);
        $purchase->update($request->only(['status']));
        return response()->json($purchase);
    }

    public function destroy($id)
    {
        Purchase::destroy($id);
        return response()->json(['message' => 'Purchase deleted']);
    }
}
