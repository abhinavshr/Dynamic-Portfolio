<?php

namespace App\Http\Controllers\api\user;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{

    public function storeContact(Request $request)
    {
        $validateddata = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        $contact = Contact::create($validateddata);

        return response()->json([
            'message' => 'Thank you for contacting us!',
            'data' => $contact
        ], 201);
    }
}
