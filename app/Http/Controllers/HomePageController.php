<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HomePage;
use App\Models\HomePageCover;
use App\Models\HomePageSlider;
use App\Models\HomePageSupport;
use App\Models\HomePageImageBlock;
use App\Models\Testimonial;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class HomePageController extends Controller
{
    public function getHomePageData(): JsonResponse
    {
        return response()->json([
            'home_page' => HomePage::first(), // Fetches the first record only
            'covers' => HomePageCover::all(), // Fetches all records
            'sliders' => HomePageSlider::all(), // Fetches all records
            'supports' => HomePageSupport::all(), // Fetches all records
            'supports' => HomePageSupport::all(), // Fetches all records
            'image_blocks' => HomePageImageBlock::all(),
            'testimonials' => Testimonial::all(), // Fetches all records
            'featured_products' => Product::where('featured', true)->with('category')->orderBy('ordernum')->get()
            ->map(function ($product) {
                $discountPercent = ($product->price > 0) 
                    ? (($product->price - $product->discounted_price) / $product->price) * 100 
                    : 0;

                $product->discount_percent = round($discountPercent); // Add discount percent
                return $product;
            }), // Fetches featured products ordered by ordernum
            'discount_products' => Product::where('sale', true)
                ->with('category')
                ->orderBy('ordernum')
                ->get()
                ->map(function ($product) {
                    $discountPercent = ($product->price > 0) 
                        ? (($product->price - $product->discounted_price) / $product->price) * 100 
                        : 0;

                    $product->discount_percent = round($discountPercent); // Add discount percent
                    return $product;
                }),
            'new_arrival_products' => Product::where('newarrival', true)->with('category')->orderBy('ordernum')->get()
            ->map(function ($product) {
                $discountPercent = ($product->price > 0) 
                    ? (($product->price - $product->discounted_price) / $product->price) * 100 
                    : 0;

                $product->discount_percent = round($discountPercent); // Add discount percent
                return $product;
            }), // Fetches new arrival products ordered by ordernum
            'categories' => Category::where('show_in_homepage', true)->get(), // Fetches categories where show_in_homepage is true
        ]);
    }
}
