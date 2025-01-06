<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;

class BlogController extends Controller
{
    public function getBlog(Request $request){
        $blog = Blog::orderBy('published_on', 'DESC')->get();
        return response()->json($blog, 200);
    }
}
