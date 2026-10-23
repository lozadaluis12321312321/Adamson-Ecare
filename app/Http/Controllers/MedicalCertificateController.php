<?php

namespace App\Http\Controllers;

use App\Models\MedicalCertificateRequest;
use App\Models\AppNotification;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MedicalCertificateController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = MedicalCertificateRequest::where('user_id', $user->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('certificates.index', compact('requests'));
    }

    public function create()
    {
        $types = [
            'Fit to Return to Class / Work',
            'Excuse Slip Medical Validation',
            'Sports & PE Clearance',
            'OJT / Internship Medical Clearance',
            'Pre-Enrollment Health Clearance',
            'General Health Clearance',
        ];

        return view('certificates.create', compact('types'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'certificate_type' => ['required', 'string'],
            'consultation_date' => ['nullable', 'date'],
            'purpose_of_request' => ['required', 'string', 'max:255'],
            'additional_information' => ['nullable', 'string', 'max:1500'],
            'supporting_document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'], // max 5MB
        ]);

        $docPath = null;
        $docOriginalName = null;

        if ($request->hasFile('supporting_document')) {
            $file = $request->file('supporting_document');
            $docOriginalName = $file->getClientOriginalName();
            $docPath = $file->store('medical_certificates', 'local');
        }

        $certRequest = MedicalCertificateRequest::create([
            'user_id' => $user->id,
            'certificate_type' => $validated['certificate_type'],
            'consultation_date' => $validated['consultation_date'] ?? null,
            'purpose_of_request' => $validated['purpose_of_request'],
            'additional_information' => $validated['additional_information'] ?? null,
            'supporting_document_path' => $docPath,
            'supporting_document_name' => $docOriginalName,
            'status' => 'submitted',
        ]);

        AppNotification::send(
            $user->id,
            'Medical Certificate Request Submitted',
            "Your request for {$certRequest->certificate_type} has been received for review.",
            'certificate',
            route('certificates.show', $certRequest->id)
        );

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            AppNotification::send(
                $admin->id,
                'New Medical Certificate Request',
                "{$user->name} requested a {$certRequest->certificate_type}.",
                'certificate',
                route('admin.certificates.index')
            );
        }

        ActivityLog::log(
            $user->id,
            'Medical Certificate',
            'Request Submitted',
            "Submitted medical certificate request for {$certRequest->certificate_type}."
        );

        return redirect()->route('certificates.index')->with('success', 'Medical certificate request submitted for verification.');
    }

    public function show(MedicalCertificateRequest $medicalCertificate)
    {
        if ($medicalCertificate->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        return view('certificates.show', compact('medicalCertificate'));
    }

    public function downloadDocument(MedicalCertificateRequest $medicalCertificate)
    {
        // Must be owner or admin
        if ($medicalCertificate->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized file download.');
        }

        if (!$medicalCertificate->supporting_document_path || !Storage::disk('local')->exists($medicalCertificate->supporting_document_path)) {
            abort(404, 'Document file not found.');
        }

        return Storage::disk('local')->download(
            $medicalCertificate->supporting_document_path,
            $medicalCertificate->supporting_document_name ?? 'supporting_document'
        );
    }
}
