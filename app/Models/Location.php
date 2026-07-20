<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'max_capacity',
    ];



    public function importDetails()
    {
        return $this->hasMany(ImportDetail::class, 'location_id');
    }


    public function exportDetails()
    {
        return $this->hasMany(ExportDetail::class, 'location_id');
    }
}