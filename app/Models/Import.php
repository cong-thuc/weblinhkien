<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    use HasFactory;

    // 1. Khai báo các cột được phép thêm dữ liệu (Mass Assignment)
    // Tùy thuộc vào bảng imports của bạn có những cột nào, ở đây mình để các cột cơ bản
    protected $fillable = [
        'import_date',
        'supplier_id', 
        'user_id', 
        'note',
        'total_amount' // Nếu bạn có cột tổng tiền
    ];

    // 2. Mối quan hệ 1-N: 1 Phiếu nhập có nhiều Chi tiết phiếu nhập
    public function details()
    {
        return $this->hasMany(ImportDetail::class, 'import_id');
    }

    // 3. Mối quan hệ N-1: 1 Phiếu nhập thuộc về 1 Nhà cung cấp
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    // 4. Mối quan hệ N-1: 1 Phiếu nhập do 1 User (Admin) tạo
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}