<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostumeImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'costume_id',
        'image_path',
        'is_primary'
    ];

    public function costume()
    {
        return $this->belongsTo(Costume::class);
    }
}