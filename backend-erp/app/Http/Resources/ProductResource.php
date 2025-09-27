<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'supplier_id' => $this->supplier_id,
            'sku' => $this->sku,
            'category' => $this->category,
            'stock' => $this->stock,
            
            // Format langsung di backend
            'cost' => 'Rp' . number_format($this->cost, 0, ',', '.'),
            'price' => 'Rp' . number_format($this->price, 0, ',', '.'),
            'margin' => number_format($this->margin ?? 0, 1) . '%',

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
