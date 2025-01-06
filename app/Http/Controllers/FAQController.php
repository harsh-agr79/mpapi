<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FAQ;

class FAQController extends Controller
{
    public function getFaq(Request $request){
        $faq = FAQ::get();
        return response()->json($faq, 200);
    }
}
