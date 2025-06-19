<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    protected $fillable = [
        'user_id',
        'costume_id',
        'color_id',       // เพิ่ม
        'size_id',        // เพิ่ม
        'quantity',
        'start_date',
        'end_date',
        'payment_proof',
        'status',
        'cancel_reason',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'start_date',
        'end_date'
    ];
    
    // หรือใช้ $casts สำหรับ Laravel เวอร์ชันใหม่กว่า
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function costume()
    {
        return $this->belongsTo(Costume::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'rental_id');
    }

    public function color()
    {
        return $this->belongsTo(CostumeVariant::class, 'color_id');
    }

    public function size()
    {
        return $this->belongsTo(CostumeVariant::class, 'size_id');
    }
}