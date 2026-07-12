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
        Schema::create('import_details', function (Blueprint $table) {
            $table->id();
            
            // Khóa ngoại liên kết với bảng imports (Phiếu nhập)
            // onDelete('cascade') giúp tự động xóa chi tiết nếu phiếu nhập bị xóa
            $table->foreignId('import_id')->constrained('imports')->onDelete('cascade');
            
            // Khóa ngoại liên kết với bảng components (Linh kiện)
            $table->foreignId('component_id')->constrained('components')->onDelete('cascade');
            
            // Cột lưu số lượng nhập của linh kiện này
            $table->integer('quantity');
            
            // Cột lưu giá nhập (tùy chọn, kiểu số thập phân, có thể để trống)
            $table->decimal('price', 15, 2)->nullable(); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_details');
    }
};