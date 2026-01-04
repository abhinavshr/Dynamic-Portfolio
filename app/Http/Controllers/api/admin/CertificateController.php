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
    public function viewAllCertificates()
    {
        return response()->json([
            'message' => 'All certificates',
            'certificates' => Certificate::all(),
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
