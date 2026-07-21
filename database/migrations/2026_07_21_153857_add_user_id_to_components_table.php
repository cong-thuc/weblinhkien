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
            // Thêm cột user_id để liên kết với bảng users
            $table->unsignedBigInteger('user_id')->nullable()->after('category_id');
        });
    }

    public function down(): void
    {
        Schema::table('components', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
};
