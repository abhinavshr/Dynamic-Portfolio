<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Class ContactController
 * @package App\Http\Controllers\api\admin
 *
 * Handles management of contact messages from the portfolio.
 */
class ContactController extends Controller
{
    /**
     * ContactController constructor.
     * Enforces admin authentication.
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Get all contacts with optional status filter.
     * Filter: ?status=all|read|unread
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function viewAllContacts(Request $request): JsonResponse
    {
        try {
            $status = $request->query('status');
            $perPage = $request->query('per_page', 6);

            $contacts = Contact::query()
                ->when($status === 'unread', fn($q) => $q->unread())
                ->when($status === 'read', fn($q) => $q->read())
                ->latest('created_at')
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Contacts retrieved successfully',
                'data' => $contacts->items(),
                'pagination' => [
                    'total'         => $contacts->total(),
                    'per_page'      => $contacts->perPage(),
                    'current_page'  => $contacts->currentPage(),
                    'last_page'     => $contacts->lastPage(),
                    'next_page_url' => $contacts->nextPageUrl(),
                    'prev_page_url' => $contacts->previousPageUrl(),
                ]
            ]);
        } catch (Exception $e) {
            Log::error('Contact Index Error: ' . $e->getMessage());
            return $this->errorResponse('Failed to fetch contacts.', 500);
        }
    }

    /**
     * View one contact and mark as read if not already.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function viewOneContact($id): JsonResponse
    {
        try {
            $contact = Contact::find($id);

            if (!$contact) {
                return $this->errorResponse('Contact not found.', 404);
            }

            // Mark as read if it hasn't been read yet
            if (!$contact->is_read) {
                $contact->update(['is_read' => true]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Contact retrieved successfully',
                'data' => $contact
            ]);
        } catch (Exception $e) {
            Log::error('Contact Show Error: ' . $e->getMessage());
            return $this->errorResponse('Failed to fetch contact details.', 500);
        }
    }

    /**
     * Mark contact as read manually.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function markAsRead($id): JsonResponse
    {
        try {
            $contact = Contact::find($id);

            if (!$contact) {
                return $this->errorResponse('Contact not found.', 404);
            }

            $contact->update(['is_read' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Message marked as read'
            ]);
        } catch (Exception $e) {
            Log::error('Contact MarkRead Error: ' . $e->getMessage());
            return $this->errorResponse('Failed to mark message as read.', 500);
        }
    }

    /**
     * Delete a contact by ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function deleteContact($id): JsonResponse
    {
        try {
            $contact = Contact::find($id);

            if (!$contact) {
                return $this->errorResponse('Contact not found.', 404);
            }

            $contact->delete();

            return response()->json([
                'success' => true,
                'message' => 'Contact deleted successfully'
            ]);
        } catch (Exception $e) {
            Log::error('Contact Delete Error: ' . $e->getMessage());
            return $this->errorResponse('Failed to delete contact.', 500);
        }
    }

    /**
     * Returns the total number of contacts.
     *
     * @return JsonResponse
     */
    public function totalContacts(): JsonResponse
    {
        try {
            $count = Contact::count();

            return response()->json([
                'success' => true,
                'message' => 'Total contacts fetched successfully.',
                'data' => [
                    'total_contacts' => $count
                ]
            ]);
        } catch (Exception $e) {
            Log::error('Total Contacts Count Error: ' . $e->getMessage());
            return $this->errorResponse('Failed to fetch total contacts count.', 500);
        }
    }

    /**
     * Fetch the 3 most recent contacts.
     *
     * @return JsonResponse
     */
    public function recentContacts(): JsonResponse
    {
        try {
            $contacts = Contact::latest('created_at')
                ->take(3)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Recent contacts fetched successfully',
                'data' => $contacts
            ]);
        } catch (Exception $e) {
            Log::error('Recent Contacts Error: ' . $e->getMessage());
            return $this->errorResponse('Failed to fetch recent contacts.', 500);
        }
    }

    /**
     * Standard error response structure.
     *
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    protected function errorResponse(string $message, int $code = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $code);
    }
}
