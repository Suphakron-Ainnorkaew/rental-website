<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CostumeColor extends Model
{
    protected $fillable = ['costume_id', 'color', 'stock'];

    public function costume()
    {
        return $this->belongsTo(Costume::class);
    }
}