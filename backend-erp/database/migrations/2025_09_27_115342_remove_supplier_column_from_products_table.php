<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan perubahan pada database.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('supplier'); // Hapus kolom supplier
        });
    }

    /**
     * Rollback perubahan.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('supplier')->nullable(); // Jika rollback, tambahkan kembali
        });
    }
};
