<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class CertificateController
 * @package App\Http\Controllers\api\admin
 */
class CertificateController extends Controller
{
    /**
     * @var string
     */
    private $imageStoragePath = 'public/certificates';

    /**
     * Constructor to set auth middleware
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeCertificate(Request $request)
    {
        $validatedData = $this->validateCertificate($request, true);

        if ($request->hasFile('certificate_photo')) {
            $file = $request->file('certificate_photo');
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::slug($validatedData['title'] . '-' . time()) . '.' . $extension;
            $file->storeAs($this->imageStoragePath, $fileName);
            $filePath = $this->imageStoragePath . '/' . $fileName;
        } else {
            return response()->json(['error' => 'No image file uploaded'], 400);
        }

        $certificate = Certificate::create([
            'title' => $validatedData['title'],
            'issuer' => $validatedData['issuer'],
            'issue_date' => $validatedData['issue_date'],
            'certificate_photo' => $filePath,
        ]);

        return response()->json([
            'message' => 'Image uploaded successfully',
            'data' => $certificate,
            'image_url' => asset('storage/' . $filePath),
        ], 201);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function viewAllCertificates(Request $request)
    {
        $certificates = Certificate::all();
        return response()->json([
            'message' => 'All Certificates',
            'certificates' => $certificates,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewOneByOneCertificate(Request $request, $id)
    {
        $certificate = Certificate::findOrFail($id);
        return response()->json([
            'message' => 'Certificate',
            'certificate' => $certificate,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCertificate(Request $request, $id)
    {
        $validatedData = $this->validateCertificate($request, false);

        $certificate = Certificate::find($id);
        if (!$certificate) {
            return response()->json([
                'error' => 'Certificate not found',
            ], 404);
        }

        if ($request->hasFile('certificate_photo')) {
            if ($certificate->certificate_photo && Storage::disk('public')->exists($certificate->certificate_photo)) {
                Storage::disk('public')->delete($certificate->certificate_photo);
            }

            $file = $request->file('certificate_photo');
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::slug($validatedData['title'] . '-' . time()) . '.' . $extension;
            $filePath = $file->storeAs($this->imageStoragePath, $fileName, 'public');

            $validatedData['certificate_photo'] = $filePath;
        }

        $certificate->update($validatedData);

        return response()->json([
            'message' => 'Certificate updated successfully',
            'certificate' => $certificate,
            'image_url' => $certificate->certificate_photo ? asset('storage/' . $certificate->certificate_photo) : null,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteCertificate(Request $request, $id)
    {
        $certificate = Certificate::find($id);
        if (!$certificate) {
            return response()->json([
                'error' => 'Certificate not found',
            ], 404);
        }

        if ($certificate->certificate_photo && Storage::disk('public')->exists($certificate->certificate_photo)) {
            Storage::disk('public')->delete($certificate->certificate_photo);
        }

        $certificate->delete();

        return response()->json([
            'message' => 'Certificate deleted successfully',
        ]);
    }

    /**
     * Validate the request data
     *
     * @param Request $request
     * @param bool $isCreate
     * @return array
     */
    private function validateCertificate(Request $request, $isCreate = true)
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'issuer' => 'required|string|max:255',
            'issue_date' => 'required|date_format:Y-m-d',
            'certificate_photo' => $isCreate
                ? 'required|image|mimes:jpeg,png,jpg,gif,svg,pdf|max:2048'
                : 'nullable|image|mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
        ]);
    }
}

