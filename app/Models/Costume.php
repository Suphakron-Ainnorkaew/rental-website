<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Costume extends Model
{
    protected $fillable = ['name', 'category_id', 'price', 'description', 'stock'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    // ตรวจสอบ relationships
    public function images()
    {
        return $this->hasMany(CostumeImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(CostumeImage::class)->where('is_primary', true);
    }

    public function colors()
    {
        return $this->hasMany(CostumeVariant::class)->where('type', 'color');
    }

    public function sizes()
    {
        return $this->hasMany(CostumeVariant::class)->where('type', 'size');
    }
}