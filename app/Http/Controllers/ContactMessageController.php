<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;
use App\Models\ContactUsData;
use App\Models\ContactUsIcon;
use Illuminate\Http\JsonResponse;

class ContactMessageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:contact_messages,email',
            'phone_number' => 'nullable|string|max:15',
            'company' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        ContactMessage::create($request->all());

        return response()->json(['message' => 'Your message has been sent successfully.'], 201);
    }

    public function getContactInfo(): JsonResponse
    {
        $contactData = ContactUsData::all();
        $contactIcons = ContactUsIcon::all();

        return response()->json([
            'contact_data' => $contactData,
            'contact_icons' => $contactIcons,
        ]);
    }
}
