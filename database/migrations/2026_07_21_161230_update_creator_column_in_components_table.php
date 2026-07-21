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
        Schema::table('components', function (Blueprint $table) {
            // 1. Thêm cột creator_name kiểu chuỗi (VARCHAR)
            if (!Schema::hasColumn('components', 'creator_name')) {
                $table->string('creator_name', 255)->nullable()->after('category_id');
            }

            // 2. Xóa cột user_id cũ đi (nếu trước đó bạn đã lỡ tạo) để cho sạch Database
            if (Schema::hasColumn('components', 'user_id')) {
                $table->dropColumn('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('components', function (Blueprint $table) {
            // Xóa cột creator_name nếu rollback
            if (Schema::hasColumn('components', 'creator_name')) {
                $table->dropColumn('creator_name');
            }
        });
    }
};