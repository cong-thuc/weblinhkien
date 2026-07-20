<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location; // Khai báo Model Location

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mảng chứa các vị trí mẫu muốn tạo
        $locations = [
            ['name' => 'Kệ A1 - Tầng 1', 'max_capacity' => 500],
            ['name' => 'Kệ A2 - Tầng 2', 'max_capacity' => 500],
            ['name' => 'Tủ B1 - Tủ Kính', 'max_capacity' => 1000],
            ['name' => 'Kho Tổng C1', 'max_capacity' => 5000],
        ];

        // Lặp qua mảng và lưu vào Database
        foreach ($locations as $loc) {
            Location::create($loc);
        }
    }
}