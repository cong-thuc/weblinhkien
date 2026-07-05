<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('components', function (Blueprint $table) {
            $table->id();

            // Chỉ giữ lại liên kết với Danh mục
            $table->foreignId('category_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('name');
            $table->string('code')->unique();
            $table->decimal('price', 12, 2);
            $table->integer('quantity')->default(0);
            $table->string('image')->nullable();
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('components');
    }
};