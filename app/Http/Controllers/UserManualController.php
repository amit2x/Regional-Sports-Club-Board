<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserManualController extends Controller
{
    /**
     * Display the user manual based on user role
     */
    public function index()
    {
        $user = auth()->guard('employee')->user();

        if ($user) {
            if ($user->hasRole('super_admin')) {
                return view('manual.super-admin');
            } elseif ($user->hasRole('regional_sports_secretary')) {
                return view('manual.regional-secretary');
            } elseif ($user->hasRole('airport_sports_secretary')) {
                return view('manual.airport-secretary');
            } else {
                return view('manual.employee');
            }
        }

        // Guest / Public user
        return view('manual.public');
    }

    /**
     * Display specific manual section
     */
    public function section($section)
    {
        $user = auth()->guard('employee')->user();
        $role = $user ? $user->getRoleNames()->first() : 'public';

        $validSections = [
            'getting-started',
            'login-guide',
            'event-registration',
            'document-upload',
            'dashboard-guide',
            'profile-management',
            'admin-guide',
            'faq',
            'troubleshooting',
        ];

        if (!in_array($section, $validSections)) {
            abort(404);
        }

        return view("manual.sections.{$section}", compact('role'));
    }

    /**
     * Download manual as PDF
     */
    public function downloadPdf()
    {
        $user = auth()->guard('employee')->user();
        $role = $user ? $user->getRoleNames()->first() : 'public';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('manual.pdf.user-manual', compact('role'));
        return $pdf->download('RSCB_User_Manual.pdf');
    }
}
