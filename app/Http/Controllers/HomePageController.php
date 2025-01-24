<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HomePage;
use App\Models\HomePageCover;
use App\Models\HomePageSlider;
use App\Models\HomePageSupport;
use App\Models\Testimonial;
use App\Models\Product;
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
            'testimonials' => Testimonial::all(), // Fetches all records
            'featured_products' => Product::where('featured', true)->orderBy('ordernum')->get(), // Fetches featured products ordered by ordernum
            'new_arrival_products' => Product::where('newarrival', true)->orderBy('ordernum')->get(), // Fetches new arrival products ordered by ordernum
        ]);
    }
}
