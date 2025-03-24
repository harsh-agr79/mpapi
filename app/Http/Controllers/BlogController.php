<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;

class BlogController extends Controller
{
    public function getBlog(Request $request)
    {
        $pinnedBlog = Blog::where('pinned', true)->first();
        $otherBlogs = Blog::orderBy('published_on', 'DESC')->get();
    
        return response()->json([
            'pinned_blog' => $pinnedBlog,
            'blogs' => $otherBlogs
        ], 200);
    }
    public function getBlogContent(Request $request, $id)
    {
        $Blog = Blog::where('id', $id)->first();
    
        return response()->json([
            'blog' => $Blog
        ], 200);
    }
    
}
