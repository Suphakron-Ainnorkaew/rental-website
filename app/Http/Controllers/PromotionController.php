<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        return view('promotions'); // ส่งไปยัง view promotions.blade.php
    }
}