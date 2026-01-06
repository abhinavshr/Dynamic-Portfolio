<?php

namespace App\Http\Controllers\api\user;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Store a new contact message.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function storeContact(Request $request)
    {
        $validatedData = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $contact = Contact::create([
            'name'    => $validatedData['name'],
            'email'   => $validatedData['email'],
            'subject' => $validatedData['subject'],
            'message' => $validatedData['message'],
            'is_read' => false,
        ]);

        return response()->json([
            'message' => 'Thank you for contacting us!',
            'data'    => $contact
        ], 201);
    }
}
