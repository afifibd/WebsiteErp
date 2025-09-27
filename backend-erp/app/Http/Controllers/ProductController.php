<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('supplier')->get();

        return $products;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'supplier_id' => 'required|exists:suppliers,id',
            'sku' => 'required|string|unique:products,sku',
            'category' => 'required|string',
            'stock' => 'required|integer',
            'cost' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        $margin = (($validated['price'] - $validated['cost']) / $validated['cost']) * 100;

        $product = Product::create([
            ...$validated,
            'margin' => $margin,
        ]);

        return response()->json($product, 201);
    }




    public function show(Product $product)
    {
        return $product;
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'supplier_id' => 'required|exists:suppliers,id',
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'category' => 'required|string',
            'stock' => 'required|integer',
            'cost' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        $validated['margin'] = (($validated['price'] - $validated['cost']) / $validated['price']) * 100;

        $product->update($validated);

        return response()->json($product);
    }


    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Product deleted']);
    }
}
