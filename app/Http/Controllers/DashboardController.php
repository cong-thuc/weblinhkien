<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// KHAI BÁO CÁC MODEL CẦN LẤY DỮ LIỆU
use App\Models\Category; 
use App\Models\Component;
use App\Models\Import;
use App\Models\Export;

class DashboardController extends Controller
{
    public function index()
    {
        // Đếm tổng số dòng trong mỗi bảng
        $categoryCount = Category::count();
        $componentCount = Component::count();
        $importCount = Import::count(); 
        $exportCount = Export::count();

        // Truyền chính xác 4 biến này ra ngoài View (Giao diện)
        return view('dashboard', compact(
            'categoryCount', 
            'componentCount', 
            'importCount',
            'exportCount'
        )); 
    }
}