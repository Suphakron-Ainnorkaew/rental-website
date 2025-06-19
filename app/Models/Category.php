<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name'];

    public function costumes()
    {
        return $this->hasMany(Costume::class, 'category_id');
    }
}