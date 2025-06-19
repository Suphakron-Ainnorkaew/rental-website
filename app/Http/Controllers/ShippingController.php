<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function index()
    {
        return view('shipping');
    }

    public function track()
    {
        return view('track-shipping');
    }

    public function sizeGuide()
    {
        return view('size-guide');
    }
}