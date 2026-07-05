<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    protected $fillable = [
        'category_id', 
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
}