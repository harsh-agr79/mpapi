<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function getProducts(Request $request){
        $product = Product::paginate(20);
        return response()->json($product, 200);
    }

    public function getCategory(Request $request){
        $category = Category::get();
        return response()->json($category, 200);
    }
}
