<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json(Order::with(['customer', 'items.product'])->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id'    => 'required|exists:customers,customer_id',
            'order_date'     => 'required|date',
            'items'          => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,product_id',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.price'      => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {
            $order = Order::create([
                'customer_id'    => $request->customer_id,
                'order_date'     => $request->order_date,
                'total_amount'   => 0,
                'payment_status' => 'pending',
                'order_status'   => 'processing',
            ]);

            $total = 0;
            foreach ($request->items as $item) {
                $subtotal = $item['quantity'] * $item['price'];
                OrderItem::create([
                    'order_id'   => $order->order_id,
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                    'subtotal'   => $subtotal,
                ]);
                $total += $subtotal;
            }

            $order->update(['total_amount' => $total]);

            DB::commit();
            return response()->json($order->load('items.product'), 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        return response()->json(Order::with(['customer', 'items.product'])->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update($request->only(['payment_status','order_status']));
        return response()->json($order);
    }

    public function destroy($id)
    {
        Order::destroy($id);
        return response()->json(['message' => 'Order deleted']);
    }
}
