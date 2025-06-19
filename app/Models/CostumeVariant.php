<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CostumeVariant extends Model
{
    protected $fillable = ['costume_id', 'type', 'value', 'stock'];

    public function costume()
    {
        return $this->belongsTo(Costume::class);
    }
}