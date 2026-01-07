<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Get all contacts (with filter)
     * ?status=all|read|unread
     */
    public function viewAllContacts(Request $request)
    {
        $status = $request->query('status');

        $query = Contact::query();

        if ($status === 'unread') {
            $query->where('is_read', false);
        }

        if ($status === 'read') {
            $query->where('is_read', true);
        }

        return response()->json([
            'message' => 'Contacts retrieved successfully',
            'data' => $query->orderBy('created_at', 'desc')->get()
        ]);
    }

    /**
     * View one contact & mark as read
     */
    public function viewOneContact($id)
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json([
                'error' => 'Contact not found'
            ], 404);
        }

        // 👁️ Mark as seen when admin views it
        if (!$contact->is_read) {
            $contact->update(['is_read' => true]);
        }

        return response()->json([
            'message' => 'Contact retrieved successfully',
            'data' => $contact
        ]);
    }

    /**
     * Mark contact as read manually
     */
    public function markAsRead($id)
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json([
                'message' => 'Contact not found'
            ], 404);
        }

        $contact->update(['is_read' => true]);

        return response()->json([
            'message' => 'Message marked as read'
        ]);
    }
}
