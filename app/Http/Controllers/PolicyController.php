<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Policy;

class PolicyController extends Controller
{
    public function getPrivacyPolicy(Request $request){
        $policy = Policy::where('id', 1)->first();
        return response()->json($policy, 200);
    }
    public function getWarrantyPolicy(Request $request){
        $policy = Policy::where('id', 2)->first();
        return response()->json($policy, 200);
    }
    public function getTerms(Request $request){
        $policy = Policy::where('id', 3)->first();
        return response()->json($policy, 200);
    }
    public function getShippings(Request $request){
        $policy = Policy::where('id', 6)->first();
        return response()->json($policy, 200);
    }
}
