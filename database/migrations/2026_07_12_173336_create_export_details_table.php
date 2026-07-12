<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
    {
        Schema::create('export_details', function (Blueprint $table) {
            $table->id();
            
            // Các cột cần thiết để lưu chi tiết phiếu xuất
            $table->integer('export_id');
            $table->integer('component_id');
            $table->integer('quantity');
            $table->integer('price')->default(0); // Giá xuất (VNĐ)
            
            $table->timestamps(); // Tự động tạo created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('export_details');
    }
};
