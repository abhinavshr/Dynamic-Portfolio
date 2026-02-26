<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;

/**
 * Class CertificateController
 * @package App\Http\Controllers\api\admin
 *
 * Handles management of certificates for achievements and qualifications.
 */
class CertificateController extends Controller
{
    /**
     * CertificateController constructor.
     * Enforces admin authentication.
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display all certificates.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $certificates = Certificate::orderBy('issue_date', 'desc')->paginate(8);

            return response()->json([
                'success'    => true,
                'message'    => 'Certificates retrieved successfully.',
                'data'       => $certificates->items(),
                'pagination' => [
                    'total'         => $certificates->total(),
                    'per_page'      => $certificates->perPage(),
                    'current_page'  => $certificates->currentPage(),
                    'last_page'     => $certificates->lastPage(),
                    'next_page_url' => $certificates->nextPageUrl(),
                    'prev_page_url' => $certificates->previousPageUrl(),
                ]
            ]);
        } catch (Exception $e) {
            Log::error('Certificate Index Error: ' . $e->getMessage());
            return $this->errorResponse('Failed to fetch certificates.', 500);
        }
    }

    /**
     * Store a newly created certificate.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'title'            => 'required|string|max:255',
                'issuer'           => 'required|string|max:255',
                'issue_date'       => 'required|date_format:Y-m-d',
                'credential_id'    => 'nullable|string|max:255',
                'verification_url' => 'nullable|url|max:255',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors()->first(), 422);
            }

            $certificate = Certificate::create($request->all());

            return response()->json([
                'success'     => true,
                'message'     => 'Certificate created successfully',
                'certificate' => $certificate,
            ], 201);
        } catch (Exception $e) {
            Log::error('Certificate Store Error: ' . $e->getMessage());
            return $this->errorResponse('An error occurred while creating the certificate.', 500);
        }
    }

    /**
     * Display a single certificate.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $certificate = Certificate::find($id);

            if (!$certificate) {
                return $this->errorResponse('Certificate not found.', 404);
            }

            return response()->json([
                'success'     => true,
                'message'     => 'Certificate details fetched successfully',
                'certificate' => $certificate,
            ]);
        } catch (Exception $e) {
            Log::error('Certificate Show Error: ' . $e->getMessage());
            return $this->errorResponse('Failed to fetch certificate details.', 500);
        }
    }

    /**
     * Update a certificate.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $certificate = Certificate::find($id);

            if (!$certificate) {
                return $this->errorResponse('Certificate not found.', 404);
            }

            $validator = Validator::make($request->all(), [
                'title'            => 'required|string|max:255',
                'issuer'           => 'required|string|max:255',
                'issue_date'       => 'required|date_format:Y-m-d',
                'credential_id'    => 'nullable|string|max:255',
                'verification_url' => 'nullable|url|max:255',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors()->first(), 422);
            }

            $certificate->update($request->all());

            return response()->json([
                'success'     => true,
                'message'     => 'Certificate updated successfully',
                'certificate' => $certificate,
            ]);
        } catch (Exception $e) {
            Log::error('Certificate Update Error: ' . $e->getMessage());
            return $this->errorResponse('An error occurred while updating the certificate.', 500);
        }
    }

    /**
     * Delete a certificate.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $certificate = Certificate::find($id);

            if (!$certificate) {
                return $this->errorResponse('Certificate not found.', 404);
            }

            $certificate->delete();

            return response()->json([
                'success' => true,
                'message' => 'Certificate deleted successfully',
            ]);
        } catch (Exception $e) {
            Log::error('Certificate Destroy Error: ' . $e->getMessage());
            return $this->errorResponse('An error occurred while deleting the certificate.', 500);
        }
    }

    /**
     * Get the total number of certificates.
     *
     * @return JsonResponse
     */
    public function totalCertificates(): JsonResponse
    {
        try {
            $count = Certificate::count();

            return response()->json([
                'success' => true,
                'message' => 'Total certificates fetched successfully.',
                'data'    => [
                    'total_certificates' => $count
                ]
            ]);
        } catch (Exception $e) {
            Log::error('Total Certificates Count Error: ' . $e->getMessage());
            return $this->errorResponse('Failed to fetch total certificates count.', 500);
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

