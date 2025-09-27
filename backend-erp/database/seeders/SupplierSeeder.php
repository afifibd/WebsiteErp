<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        Supplier::create([
            'code' => 'SUP-001',
            'name' => 'CleanCorp Industries',
            'email' => 'orders@cleancorp.com',
            'phone' => '+1 (555) 987-6543',
            'address' => '500 Industrial Blvd',
            'category' => 'Cleaning Supplies',
            'rating' => 4.8,
            'orders_count' => 156,
            'total_purchased' => 245000.00,
            'terms' => 'Net 30',
            'delivery_days' => '3-5 days',
            'status' => 'Active'
        ]);

        Supplier::create([
            'code' => 'SUP-002',
            'name' => 'SoapWorks Ltd',
            'email' => 'sales@soapworks.com',
            'phone' => '+1 (555) 876-5432',
            'address' => '200 Chemical Ave',
            'category' => 'Personal Care',
            'rating' => 4.6,
            'orders_count' => 98,
            'total_purchased' => 156000.50,
            'terms' => 'Net 15',
            'delivery_days' => '2-4 days',
            'status' => 'Active'
        ]);
    }
}
