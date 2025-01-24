<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\AboutUs;
use App\Models\AboutUsCard;
use App\Models\AboutUsImageBlock;
use App\Models\TeamMember;
use Illuminate\Http\JsonResponse;


class AboutUsController extends Controller
{
    public function getAboutUs(): JsonResponse
    {
        return response()->json([
            'about_us' => AboutUs::first(), // Fetches the first record only
            'about_us_cards' => AboutUsCard::all(), // Fetches all records
            'about_us_image_blocks' => AboutUsImageBlock::all(), // Fetches all records
            'team_members' => TeamMember::all(), // Fetches all records
        ]);
    }
}
