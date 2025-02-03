<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Customer;

class InventoryController extends Controller
{
    public function getProducts(Request $request)
    {
        $user = auth('sanctum')->user(); // Get authenticated user if available
    
        $products = Product::with('category')->paginate(20);
    
        $wishlistProductIds = $user ? $user->wishlist()->pluck('product_id')->toArray() : [];
    
        $products->each(function ($product) use ($wishlistProductIds) {
            $product->subcategories = $product->subcategory();
            $product->wishlist = in_array($product->id, $wishlistProductIds);
        });
    
        return response()->json($products, 200);
    }
    
    
    

    public function getProductDetail(Request $request, $id)
    {
        $user = auth('sanctum')->user(); // Get authenticated user if available

        $product = Product::with('category')->where('id', $id)->first();

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->subcategories = $product->subcategory();
        $product->wishlist = $user ? $user->wishlist()->where('product_id', $product->id)->exists() : false;

        return response()->json($product, 200);
    }


    public function getCategory(Request $request){
        $category = Category::get();
        return response()->json($category, 200);
    }
}
