<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // This middleware will check if the request is authenticated with the admin guard
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    // This method will return all contacts
    public function viewAllContacts(Request $request)
    {
        // Retrieve all contacts from the database
        $contacts = Contact::all();

        // Return the contacts in JSON format
        return response()->json([
            'message' => 'All contacts retrieved successfully',
            'data' => $contacts,
        ]);
    }

    // This method will return a single contact
    public function viewOneContact(Request $request, $id)
    {
        // Retrieve the contact from the database
        $contact = Contact::find($id);

        // If the contact is not found, return a 404 error
        if (!$contact) {
            return response()->json([
                'error' => 'Contact not found',
            ], 404);
        }

        // Return the contact in JSON format
        return response()->json([
            'message' => 'Contact retrieved successfully',
            'data' => $contact,
        ]);
    }
}

