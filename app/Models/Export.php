<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Export extends Model
{
    use HasFactory;

    // Cấp quyền cho phép Laravel lưu dữ liệu vào các cột này
    protected $fillable = [
        'note', 
        'export_date',
        // 'user_id', // Bỏ comment nếu sau này bạn có lưu ID người xuất kho
    ];

    // Các hàm relationship (nếu có) bạn cứ giữ nguyên bên dưới nhé...

    // 1 phiếu xuất có nhiều chi tiết
    public function details()
    {
        return $this->hasMany(ExportDetail::class, 'export_id', 'id');
    }
}