<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function getProducts(Request $request){
        $products = Product::with(['category', 'subcategory'])->paginate(20);
        return response()->json($products, 200);
    }

    public function getCategory(Request $request){
        $category = Category::get();
        return response()->json($category, 200);
    }
}
