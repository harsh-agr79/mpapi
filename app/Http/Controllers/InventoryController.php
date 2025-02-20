<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Customer;

class InventoryController extends Controller
{
    public function getProducts2(Request $request)
    {
        $user = auth('sanctum')->user(); // Get authenticated user if available
    
        $products = Product::with('category')->get();
    
        $wishlistProductIds = $user ? $user->wishlist()->pluck('product_id')->toArray() : [];
    
        $products->each(function ($product) use ($wishlistProductIds) {
            $product->subcategories = $product->subcategory();
            $product->wishlist = in_array($product->id, $wishlistProductIds);
        });

        $prices = Product::selectRaw('
            MIN(CASE WHEN discounted_price IS NOT NULL THEN discounted_price ELSE price END) as min_price,
            MAX(CASE WHEN discounted_price IS NOT NULL THEN discounted_price ELSE price END) as max_price
        ')->first();


        $colorsData = Product::pluck('colors');
    
        // Process the data: decode if necessary, extract the "color" field, trim values, and filter out null/empty entries.
        $uniqueColors = $colorsData->flatMap(function ($colors) {
            // If the data is a JSON string, decode it; otherwise assume it's already an array.
            if (is_string($colors)) {
                $colors = json_decode($colors, true);
            }
            return is_array($colors) ? $colors : [];
        })
        ->map(function ($colorItem) {
            // Extract and trim the "color" value if present
            return isset($colorItem['color']) ? trim($colorItem['color']) : null;
        })
        ->filter(function ($color) {
            // Filter out null or empty strings
            return !empty($color);
        })
        // Use a callback with unique() to ignore case sensitivity
        ->unique(function ($color) {
            return strtolower($color);
        })
        ->values();  // Reset the keys
    
        // return response()->json($uniqueColors, 200);
    
        return response()->json([
        'min_price' => $prices->min_price ?? 0,
        'max_price' => $prices->max_price ?? 0,
        'categories' => $categories = Category::select('id', 'name')->get(),
        'colors'=>$uniqueColors,
        'products'=>$products
    ], 200);
    }

    public function getProducts(Request $request)
    {
        $user = auth('sanctum')->user(); // Get authenticated user if available
    
        $query = Product::with('category');
    
        // Apply category filter for multiple categories (supports JSON string or array)
        if ($request->has('category_id')) {
            $categoryIds = $request->input('category_id');
            if (is_string($categoryIds)) {
                $decoded = json_decode($categoryIds, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $categoryIds = $decoded;
                }
            }
            if (is_array($categoryIds)) {
                $query->whereIn('category_id', $categoryIds);
            } else {
                $query->where('category_id', $categoryIds);
            }
        }
    
        // Apply price range filter with discounted_price logic
        if ($request->has('price_min') || $request->has('price_max')) {
            $query->where(function ($q) use ($request) {
                if ($request->has('price_min')) {
                    $q->whereRaw('(CASE WHEN discounted_price IS NOT NULL THEN discounted_price ELSE price END) >= ?', [$request->price_min]);
                }
                if ($request->has('price_max')) {
                    $q->whereRaw('(CASE WHEN discounted_price IS NOT NULL THEN discounted_price ELSE price END) <= ?', [$request->price_max]);
                }
            });
        }
    
        // Apply featured filter
        if ($request->has('featured') && $request->featured) {
            $query->where('featured', true);
        }
    
        // Apply new arrival filter
        if ($request->has('newarrival') && $request->newarrival) {
            $query->where('newarrival', true);
        }
    
        // Apply colors filter for multiple colors using JSON_SEARCH
        if ($request->has('colors')) {
            $colors = $request->input('colors');
            // Decode if provided as a JSON string
            if (is_string($colors)) {
                $decoded = json_decode($colors, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $colors = $decoded;
                }
            }
            if (is_array($colors)) {
                $query->where(function ($q) use ($colors) {
                    foreach ($colors as $color) {
                        // This will check if the provided $color exists in any of the "color" keys in the JSON array.
                        $q->orWhereRaw("JSON_SEARCH(colors, 'one', ?, NULL, '$[*].color') IS NOT NULL", [$color]);
                    }
                });
            } else {
                $query->whereRaw("JSON_SEARCH(colors, 'one', ?, NULL, '$[*].color') IS NOT NULL", [$colors]);
            }
        }
    
        // Paginate results
        $products = $query->paginate(20);
    
        // Get wishlist product IDs if user is authenticated
        $wishlistProductIds = $user ? $user->wishlist()->pluck('product_id')->toArray() : [];
    
        // Modify products collection to add subcategories and wishlist status
        $products->each(function ($product) use ($wishlistProductIds) {
            $product->subcategories = $product->subcategory();
            $product->wishlist = in_array($product->id, $wishlistProductIds);
        });
    
        return response()->json($products, 200);
    }
    

    public function getAvailableColors()
    {
        // Get the raw colors data from the products
        $colorsData = Product::pluck('colors');
    
        // Process the data: decode if necessary, extract the "color" field, trim values, and filter out null/empty entries.
        $uniqueColors = $colorsData->flatMap(function ($colors) {
            // If the data is a JSON string, decode it; otherwise assume it's already an array.
            if (is_string($colors)) {
                $colors = json_decode($colors, true);
            }
            return is_array($colors) ? $colors : [];
        })
        ->map(function ($colorItem) {
            // Extract and trim the "color" value if present
            return isset($colorItem['color']) ? trim($colorItem['color']) : null;
        })
        ->filter(function ($color) {
            // Filter out null or empty strings
            return !empty($color);
        })
        // Use a callback with unique() to ignore case sensitivity
        ->unique(function ($color) {
            return strtolower($color);
        })
        ->values();  // Reset the keys
    
        return response()->json($uniqueColors, 200);
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
