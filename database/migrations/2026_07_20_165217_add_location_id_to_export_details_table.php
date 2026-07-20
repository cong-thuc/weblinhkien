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
        Schema::table('export_details', function (Blueprint $table) {
            // Thêm cột location_id, cho phép null phòng trường hợp dữ liệu cũ không có
            $table->unsignedBigInteger('location_id')->nullable()->after('component_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('export_details', function (Blueprint $table) {
            // Xóa cột nếu lùi (rollback) database
            $table->dropColumn('location_id');
        });
    }
};
