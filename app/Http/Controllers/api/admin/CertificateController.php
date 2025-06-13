<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificateController extends Controller
{

    public function _checkLogin(Request $request){
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function storeCertificate(Request $request)
    {
        $this->_checkLogin($request);

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'issuer' => 'required|string|max:255',
            'issue_date' => 'required|date_format:Y-m-d',
            'certificate_photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        if ($request->hasFile('certificate_photo')) {
            $file = $request->file('certificate_photo');
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::slug($validatedData['title'] . '-' . time()) . '.' . $extension;
            $file->storeAs('public/certificates', $fileName);
            $filePath = 'certificates/' . $fileName;
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

    public function viewAllCertificates(Request $request)
    {
        $this->_checkLogin($request);

        $certificates = Certificate::all();
        return response()->json([
            'message' => 'All Certificates',
            'certificates' => $certificates,
        ]);
    }

    public function viewOneByOneCertificate(Request $request, $id)
    {
        $this->_checkLogin($request);

        $certificate = Certificate::findOrFail($id);
        return response()->json([
            'message' => 'Certificate',
            'certificate' => $certificate,
        ]);
    }

    public function updateCertificate(Request $request, $id)
    {
        $this->_checkLogin($request);

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'issuer' => 'required|string|max:255',
            'issue_date' => 'required|date_format:Y-m-d',
            'certificate_photo' => 'nullable|mimes:jpeg,png,jpg,gif,svg,pdf',
        ]);

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
            $filePath = $file->storeAs('certificate_photos', $fileName, 'public');

            $validatedData['certificate_photo'] = $filePath;
        }

        $certificate->update($validatedData);

        return response()->json([
            'message' => 'Certificate updated successfully',
            'certificate' => $certificate,
            'image_url' => $certificate->certificate_photo ? asset('storage/' . $certificate->certificate_photo) : null,
        ]);
    }

    public function deleteCertificate(Request $request, $id)
    {
        $this->_checkLogin($request);

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
}
