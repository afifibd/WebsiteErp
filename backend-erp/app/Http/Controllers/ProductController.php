<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(Product::with('supplier')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'sku'         => 'required|string|unique:products',
            'category'    => 'nullable|string',
            'supplier_id' => 'nullable|exists:suppliers,supplier_id',
            'stock'       => 'required|integer',
            'cost'        => 'required|numeric',
            'price'       => 'required|numeric',
        ]);

        $margin = $request->cost > 0 ? (($request->price - $request->cost) / $request->cost) * 100 : 0;

        $product = Product::create($request->all() + ['margin' => $margin]);

        return response()->json($product, 201);
    }

    public function show($id)
    {
        return response()->json(Product::with('supplier')->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $margin = $request->cost > 0 ? (($request->price - $request->cost) / $request->cost) * 100 : 0;
        $product->update($request->all() + ['margin' => $margin]);

        return response()->json($product);
    }

    public function destroy($id)
    {
        Product::destroy($id);
        return response()->json(['message' => 'Product deleted']);
    }
}
