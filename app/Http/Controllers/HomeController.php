<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Costume;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class HomeController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index()
    {
        $categories = Category::all();
        $costumes = Costume::with('rentals')->paginate(12); // แบ่งหน้า 12 ชุดต่อหน้า
        $featured_costumes = Costume::where('stock', '>', 0)->take(4)->get(); // ชุดแนะนำ 4 ชุด
        return view('home', compact('categories', 'costumes', 'featured_costumes'));
    }
    public function search(Request $request)
    {
        $query = $request->input('query');
        $categories = Category::all();
        $costumes = Costume::with('rentals')
            ->where('name', 'like', "%$query%")
            ->orWhere('description', 'like', "%$query%")
            ->paginate(12);
        $featured_costumes = Costume::where('stock', '>', 0)->take(4)->get();
        return view('home', compact('categories', 'costumes', 'featured_costumes'));
    }
}