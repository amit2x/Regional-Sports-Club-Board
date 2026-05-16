<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    /**
     * Display FAQ page
     */
    public function faq()
    {
        $faqs = [
            [
                'question' => 'What is Regional Sports Control Board (RSCB)?',
                'answer' => 'RSCB is a centralized sports management portal for Airports/Regional Offices to announce sports events, allow employees to participate, manage registrations, and maintain employee sports database.'
            ],
            [
                'question' => 'Who can participate in RSCB events?',
                'answer' => 'All active employees of participating airports and regional offices can participate in RSCB sports events. Eligibility may vary based on event-specific criteria like gender, age, department, and region.'
            ],
            [
                'question' => 'How do I register for an event?',
                'answer' => 'Login to the employee portal using your Employee ID and password, browse available events, and click on "Register" for the event you wish to participate in. Fill in the required details and submit your registration.'
            ],
            [
                'question' => 'What documents are required for registration?',
                'answer' => 'Required documents vary by event but typically include Medical Fitness Certificate, Undertaking Form, ID Card copy, and Consent Form. Check the specific event details for exact requirements.'
            ],
            [
                'question' => 'How can I check my registration status?',
                'answer' => 'Login to your employee dashboard and navigate to "My Registrations" section. You can view the status of all your registrations - Draft, Pending, Approved, or Rejected.'
            ],
            [
                'question' => 'What should I do if I forget my password?',
                'answer' => 'Click on "Forgot Password" on the login page. Enter your Employee ID and registered email address. You will receive a password reset link on your email.'
            ],
            [
                'question' => 'How do I change my password?',
                'answer' => 'After logging in, go to your profile and click on "Change Password". Enter your current password and set a new password that meets the security requirements.'
            ],
            [
                'question' => 'Can I withdraw my registration after submitting?',
                'answer' => 'Yes, you can withdraw your registration if it is in Draft or Pending status. Once approved, you cannot withdraw through the portal. Contact your sports secretary for assistance.'
            ],
            [
                'question' => 'How are event winners determined?',
                'answer' => 'Winners are determined based on event rules and judged by designated officials. Results are published on the portal and winners receive certificates and recognition.'
            ],
            [
                'question' => 'How do I get my participation certificate?',
                'answer' => 'Certificates are generated for approved participants after event completion. You can download them from your dashboard under "My Certificates" section. Each certificate has a unique QR code for verification.'
            ],
            [
                'question' => 'What types of sports events are organized?',
                'answer' => 'RSCB organizes various sports including Cricket, Football, Badminton, Table Tennis, Chess, Athletics, Volleyball, Basketball, and many more. Events can be individual or team-based.'
            ],
            [
                'question' => 'How can I contact my regional sports secretary?',
                'answer' => 'Visit the Contact Us page to find contact details of all regional offices and airports. You can also submit a query through the contact form on the website.'
            ],
            [
                'question' => 'Is there a mobile app for RSCB?',
                'answer' => 'The RSCB portal is a Progressive Web App (PWA). You can install it on your mobile device by clicking "Install RSCB App" when prompted or using the browser menu option "Add to Home Screen".'
            ],
            [
                'question' => 'What should I do if I face technical issues?',
                'answer' => 'Contact the IT support team through the Contact Us page or email support@rscb.gov.in. Describe your issue in detail including your Employee ID and any error messages you received.'
            ],
            [
                'question' => 'Can I update my profile information?',
                'answer' => 'Basic profile information can be updated through your profile settings. For changes to Employee ID, Department, or other official details, contact your HR department or sports secretary.'
            ],
        ];

        return view('website.faq', compact('faqs'));
    }

    /**
     * Display Terms & Conditions page
     */
    public function terms()
    {
        return view('website.terms');
    }

    /**
     * Display Privacy Policy page
     */
    public function privacy()
    {
        return view('website.privacy');
    }

    /**
     * Display About page
     */
    public function about()
    {
        return view('website.about');
    }

    /**
     * Display Help page
     */
    public function help()
    {
        return view('website.help');
    }

    /**
     * Display Site Map page
     */
    public function sitemap()
    {
        return view('website.sitemap');
    }

    /**
     * Display Accessibility page
     */
    public function accessibility()
    {
        return view('website.accessibility');
    }

    /**
     * Display Disclaimer page
     */
    public function disclaimer()
    {
        return view('website.disclaimer');
    }

    /**
     * Display Copyright page
     */
    public function copyright()
    {
        return view('website.copyright');
    }

    /**
     * Display Feedback page
     */
    public function feedback()
    {
        return view('website.feedback');
    }

    /**
     * Submit feedback
     */
    public function submitFeedback(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'required|string|min:10',
            'category' => 'required|string',
        ]);

        // Store feedback in database
        \App\Models\Feedback::create([
            'name' => $request->name,
            'email' => $request->email,
            'rating' => $request->rating,
            'feedback' => $request->feedback,
            'category' => $request->category,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->back()->with('success', 'Thank you for your feedback!');
    }
}
