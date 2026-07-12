<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportDetail extends Model
{
    use HasFactory;

    // Các cột được phép lưu vào DB
    protected $fillable = ['import_id', 'component_id', 'quantity', 'price'];

    // 1 Chi tiết phiếu nhập thuộc về 1 Linh kiện
    public function component()
    {
        return $this->belongsTo(Component::class, 'component_id', 'id');
    }

    // 1 Chi tiết phiếu nhập thuộc về 1 Phiếu nhập
    public function import()
    {
        return $this->belongsTo(Import::class, 'import_id');
    }

    
}