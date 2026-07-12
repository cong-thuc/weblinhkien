<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Component; // Thêm dòng này cho chắc ăn

class ExportDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'export_id',
        'component_id',
        'quantity',
        'price',
    ];

    // BẮT BUỘC PHẢI CÓ HÀM NÀY: Báo cho Laravel biết chi tiết này thuộc về linh kiện nào
    public function component()
    {
        return $this->belongsTo(Component::class, 'component_id', 'id');
    }
}