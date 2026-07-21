<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    // Bỏ user_id ra khỏi mảng này
    protected $fillable = [
        'category_id', 
        'creator_name', // Chỉ dùng cái này
        'name', 
        'code', 
        'price', 
        'quantity', 
        'image', 
        'description'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Khai báo liên kết: 1 Linh kiện do 1 Người dùng tạo
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}