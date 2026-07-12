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
        Schema::table('exports', function (Blueprint $table) {
            // Thêm cột note (kiểu chuỗi, cho phép để trống)
            $table->string('note')->nullable()->after('id'); 
            // Bạn có thể bỏ chữ ->after('id') đi cũng được, nó chỉ dùng để xếp vị trí cột thôi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exports', function (Blueprint $table) {
            // Xóa cột note nếu muốn hoàn tác (Rollback)
            $table->dropColumn('note');
        });
    }
};
