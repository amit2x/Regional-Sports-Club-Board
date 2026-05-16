<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventRegistration;
use App\Models\ParticipationCertificate;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:employee');
        $this->middleware('permission:generate_certificates');
    }

    /**
     * Display certificates list
     */
    public function index(Request $request)
    {
        $certificates = ParticipationCertificate::with(['registration.employee', 'registration.event'])
            ->latest()
            ->paginate(20);

        return view('admin.certificates.index', compact('certificates'));
    }

    /**
     * Generate certificate for a registration
     */
    public function generate(Request $request, $registrationId)
    {
        $registration = EventRegistration::with(['employee', 'event'])->findOrFail($registrationId);

        // Check if registration is approved
        if ($registration->status !== 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Certificate can only be generated for approved registrations.'
            ], 422);
        }

        // Check if certificate already exists
        if ($registration->certificate) {
            return response()->json([
                'success' => false,
                'message' => 'Certificate already generated.',
                'certificate_id' => $registration->certificate->id
            ], 422);
        }

        // Generate unique certificate number
        $certificateNumber = $this->generateCertificateNumber($registration);

        // Generate QR code for verification
        $qrCodeData = route('certificates.verify', ['number' => $certificateNumber]);
        $qrCode = QrCode::format('png')
            ->size(200)
            ->margin(1)
            ->generate($qrCodeData);

        $qrCodePath = 'certificates/qr/' . $certificateNumber . '.png';
        Storage::disk('public')->put($qrCodePath, $qrCode);

        // Generate PDF certificate
        $pdf = PDF::loadView('admin.certificates.template', [
            'registration' => $registration,
            'certificateNumber' => $certificateNumber,
            'qrCodePath' => $qrCodePath,
            'issueDate' => now(),
        ]);

        $pdfPath = 'certificates/pdfs/' . $certificateNumber . '.pdf';
        Storage::disk('public')->put($pdfPath, $pdf->output());

        // Create certificate record
        $certificate = ParticipationCertificate::create([
            'event_registration_id' => $registration->id,
            'certificate_number' => $certificateNumber,
            'employee_id' => $registration->employee_id,
            'event_id' => $registration->event_id,
            'issue_date' => now(),
            'pdf_path' => $pdfPath,
            'qr_code_path' => $qrCodePath,
            'verification_url' => $qrCodeData,
            'generated_by' => auth()->guard('employee')->id(),
        ]);

        // Log activity
        activity()
            ->performedOn($certificate)
            ->causedBy(auth()->guard('employee')->user())
            ->log('generated certificate');

        return response()->json([
            'success' => true,
            'message' => 'Certificate generated successfully.',
            'certificate_id' => $certificate->id,
            'download_url' => route('certificates.download', $certificate->id)
        ]);
    }

    /**
     * Bulk generate certificates
     */
    public function bulkGenerate(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'registration_ids' => 'required|array',
            'registration_ids.*' => 'exists:event_registrations,id',
        ]);

        $generated = 0;
        $failed = 0;
        $errors = [];

        foreach ($request->registration_ids as $registrationId) {
            try {
                $registration = EventRegistration::with('employee')->findOrFail($registrationId);

                if ($registration->status !== 'approved') {
                    $failed++;
                    $errors[] = "Registration {$registration->registration_number} is not approved.";
                    continue;
                }

                if ($registration->certificate) {
                    $failed++;
                    $errors[] = "Certificate already exists for {$registration->registration_number}.";
                    continue;
                }

                // Generate certificate (same as single generate)
                $this->generateCertificateForRegistration($registration);
                $generated++;

            } catch (\Exception $e) {
                $failed++;
                $errors[] = "Failed for {$registration->registration_number}: " . $e->getMessage();
            }
        }

        return response()->json([
            'success' => $generated > 0,
            'message' => "Generated {$generated} certificates. Failed: {$failed}",
            'generated' => $generated,
            'failed' => $failed,
            'errors' => $errors
        ]);
    }

    /**
     * Download certificate
     */
    public function download($id)
    {
        $certificate = ParticipationCertificate::findOrFail($id);

        if (!Storage::disk('public')->exists($certificate->pdf_path)) {
            abort(404, 'Certificate file not found.');
        }

        return Storage::disk('public')->download(
            $certificate->pdf_path,
            'Certificate_' . $certificate->certificate_number . '.pdf'
        );
    }

    /**
     * Verify certificate via QR code
     */
    public function verify(Request $request)
    {
        $certificateNumber = $request->number;

        $certificate = ParticipationCertificate::with(['registration.employee', 'registration.event'])
            ->where('certificate_number', $certificateNumber)
            ->first();

        if (!$certificate) {
            return view('certificates.verify', ['valid' => false]);
        }

        return view('certificates.verify', [
            'valid' => true,
            'certificate' => $certificate
        ]);
    }

    /**
     * Generate unique certificate number
     */
    private function generateCertificateNumber($registration)
    {
        $year = date('Y');
        $eventCode = $registration->event->event_code;
        $random = strtoupper(substr(uniqid(), -6));
        return "CERT-{$year}-{$eventCode}-{$random}";
    }

    /**
     * Generate certificate for a registration (internal use)
     */
    private function generateCertificateForRegistration($registration)
    {
        $certificateNumber = $this->generateCertificateNumber($registration);

        $qrCodeData = route('certificates.verify', ['number' => $certificateNumber]);
        $qrCode = QrCode::format('png')->size(200)->generate($qrCodeData);

        $qrCodePath = 'certificates/qr/' . $certificateNumber . '.png';
        Storage::disk('public')->put($qrCodePath, $qrCode);

        $pdf = PDF::loadView('admin.certificates.template', [
            'registration' => $registration,
            'certificateNumber' => $certificateNumber,
            'qrCodePath' => $qrCodePath,
            'issueDate' => now(),
        ]);

        $pdfPath = 'certificates/pdfs/' . $certificateNumber . '.pdf';
        Storage::disk('public')->put($pdfPath, $pdf->output());

        return ParticipationCertificate::create([
            'event_registration_id' => $registration->id,
            'certificate_number' => $certificateNumber,
            'employee_id' => $registration->employee_id,
            'event_id' => $registration->event_id,
            'issue_date' => now(),
            'pdf_path' => $pdfPath,
            'qr_code_path' => $qrCodePath,
            'verification_url' => $qrCodeData,
            'generated_by' => auth()->guard('employee')->id(),
        ]);
    }

    /**
     * Preview certificate
     */
    public function preview($registrationId)
    {
        $registration = EventRegistration::with(['employee', 'event'])->findOrFail($registrationId);

        return view('admin.certificates.preview', compact('registration'));
    }

    /**
     * Certificate template customization
     */
    public function customizeTemplate(Request $request)
    {
        $request->validate([
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'primary_color' => 'nullable|string',
            'font_style' => 'nullable|string',
            'organization_name' => 'nullable|string',
            'signature_text' => 'nullable|string',
        ]);

        // Store template settings in settings table or config
        $settings = $request->except(['_token', 'background_image']);

        if ($request->hasFile('background_image')) {
            $settings['background_image'] = $request->file('background_image')
                ->store('certificates/templates', 'public');
        }

        // Save settings
        setting(['certificate_template' => $settings]);

        return response()->json([
            'success' => true,
            'message' => 'Certificate template updated successfully.'
        ]);
    }
}
