<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{

    public function _checkLogin(Request $request){
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function viewAllContacts(Request $request){

        $this->_checkLogin($request);

        $contacts = Contact::all();
        return response()->json($contacts);
    }

    public function viewOneContact(Request $request, $id){

        $this->_checkLogin($request);

        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json(['error' => 'Contact not found'], 404);
        }
        return response()->json($contact);
    }
}
