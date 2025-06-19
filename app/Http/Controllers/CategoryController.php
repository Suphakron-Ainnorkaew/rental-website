<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Costume;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class CategoryController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function show(Category $category)
    {
        $costumes = $category->costumes()->with('rentals')->get();
        return view('category.show', [
            'category' => $category,
            'costumes' => $costumes ?? collect(),
        ]);
    }
}