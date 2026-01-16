<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;

/**
 * Class CertificateController
 * @package App\Http\Controllers\api\admin
 */
class CertificateController extends Controller
{
    /**
     * Constructor to apply auth middleware
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Store a newly created certificate.
     */
    public function storeCertificate(Request $request)
    {
        $validatedData = $this->validateCertificate($request);

        $certificate = Certificate::create($validatedData);

        return response()->json([
            'message' => 'Certificate created successfully',
            'certificate' => $certificate,
        ], 201);
    }

    /**
     * Display all certificates.
     */
    public function viewAllCertificates(Request $request)
    {
        $certificates = Certificate::paginate(8);

        return response()->json([
            'success'     => true,
            'message'     => 'Certificates retrieved successfully.',
            'data'        => $certificates->items(),
            'pagination'  => [
                'total'        => $certificates->total(),
                'per_page'     => $certificates->perPage(),
                'current_page' => $certificates->currentPage(),
                'last_page'    => $certificates->lastPage(),
                'next_page_url' => $certificates->nextPageUrl(),
                'prev_page_url' => $certificates->previousPageUrl(),
            ]
        ]);
    }

    /**
     * Display a single certificate by ID.
     */
    public function viewOneByOneCertificate($id)
    {
        $certificate = Certificate::findOrFail($id);

        return response()->json([
            'message' => 'Certificate details',
            'certificate' => $certificate,
        ]);
    }

    /**
     * Update a certificate.
     */
    public function updateCertificate(Request $request, $id)
    {
        $certificate = Certificate::find($id);

        if (!$certificate) {
            return response()->json([
                'error' => 'Certificate not found',
            ], 404);
        }

        $validatedData = $this->validateCertificate($request, false);

        $certificate->update($validatedData);

        return response()->json([
            'message' => 'Certificate updated successfully',
            'certificate' => $certificate,
        ]);
    }

    /**
     * Delete a certificate.
     */
    public function deleteCertificate($id)
    {
        $certificate = Certificate::find($id);

        if (!$certificate) {
            return response()->json([
                'error' => 'Certificate not found',
            ], 404);
        }

        $certificate->delete();

        return response()->json([
            'message' => 'Certificate deleted successfully',
        ]);
    }

    /**
     * Get the total number of certificates.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function totalCertificates()
    {
        $totalCertificates = Certificate::count();

        return response()->json([
            'success' => true,
            'message' => 'Total certificates fetched successfully.',
            'data' => [
                'total_certificates' => $totalCertificates
            ]
        ]);
    }


    /**
     * Validate certificate data.
     */
    private function validateCertificate(Request $request, $isCreate = true)
    {
        return $request->validate([
            'title'            => 'required|string|max:255',
            'issuer'           => 'required|string|max:255',
            'issue_date'       => 'required|date_format:Y-m-d',
            'credential_id'    => 'nullable|string|max:255',
            'verification_url' => 'nullable|url|max:255',
        ]);
    }
}
