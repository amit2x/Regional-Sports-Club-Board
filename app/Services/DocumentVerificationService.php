<?php

namespace App\Services;

use App\Models\RegistrationDocument;
use App\Models\EmployeeDocument;
use Illuminate\Support\Facades\Storage;

class DocumentVerificationService
{
    /**
     * Verify a document
     */
    public function verifyDocument($document, $verifiedBy, $notes = null)
    {
        $document->verification_status = 'verified';
        $document->verified_by = $verifiedBy;
        $document->verified_at = now();
        $document->verification_notes = $notes;
        $document->save();

        // If this is a registration document, check if all documents are verified
        if ($document instanceof RegistrationDocument) {
            $registration = $document->eventRegistration;
            $allVerified = $registration->documents()
                ->where('verification_status', '!=', 'verified')
                ->count() === 0;

            if ($allVerified) {
                $registration->documents_verified = true;
                $registration->save();
            }
        }

        return $document;
    }

    /**
     * Reject a document
     */
    public function rejectDocument($document, $rejectedBy, $notes = null)
    {
        $document->verification_status = 'rejected';
        $document->verified_by = $rejectedBy;
        $document->verified_at = now();
        $document->verification_notes = $notes;
        $document->save();

        return $document;
    }

    /**
     * Check if document needs verification
     */
    public function needsVerification($document)
    {
        return $document->verification_status === 'pending';
    }

    /**
     * Get verification statistics
     */
    public function getVerificationStats()
    {
        $registrationDocs = RegistrationDocument::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN verification_status = "verified" THEN 1 ELSE 0 END) as verified,
            SUM(CASE WHEN verification_status = "rejected" THEN 1 ELSE 0 END) as rejected,
            SUM(CASE WHEN verification_status = "pending" THEN 1 ELSE 0 END) as pending
        ')->first();

        return [
            'total' => $registrationDocs->total,
            'verified' => $registrationDocs->verified,
            'rejected' => $registrationDocs->rejected,
            'pending' => $registrationDocs->pending,
            'verification_rate' => $registrationDocs->total > 0
                ? round(($registrationDocs->verified / $registrationDocs->total) * 100, 2)
                : 0,
        ];
    }
}
