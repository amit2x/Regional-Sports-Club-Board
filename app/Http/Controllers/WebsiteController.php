<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Announcement;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WebsiteController extends Controller
{
    /**
     * Display the landing page
     */
    public function home()
    {
        // Cache frequently accessed data
        $upcomingEvents = Cache::remember('upcoming_events', 3600, function () {
            return Event::where('status', 'published')
                ->where('is_published', true)
                ->where('start_date', '>=', now())
                ->with(['region', 'airport'])
                ->orderBy('start_date')
                ->take(6)
                ->get();
        });

        $latestAnnouncements = Cache::remember('latest_announcements', 1800, function () {
            return Announcement::where('status', 'published')
                ->latest()
                ->take(5)
                ->get();
        });

        $gallery = Cache::remember('homepage_gallery', 7200, function () {
            return Gallery::where('is_featured', true)
                ->latest()
                ->take(8)
                ->get();
        });

        $statistics = Cache::remember('public_statistics', 3600, function () {
            return [
                'total_events' => Event::where('status', 'published')->count(),
                'total_participants' => \App\Models\EventRegistration::where('status', 'approved')->count(),
                'total_airports' => \App\Models\Airport::count(),
                'total_regions' => \App\Models\Region::count(),
                'ongoing_events' => Event::where('status', 'published')
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->count(),
            ];
        });

        $recentWinners = Cache::remember('recent_winners', 7200, function () {
            return \App\Models\EventRegistration::where('status', 'approved')
                ->whereHas('event', function($q) {
                    $q->where('status', 'completed');
                })
                ->with(['employee', 'event'])
                ->latest()
                ->take(4)
                ->get();
        });

        return view('website.home', compact(
            'upcomingEvents',
            'latestAnnouncements',
            'gallery',
            'statistics',
            'recentWinners'
        ));
    }

    /**
     * Display upcoming events page
     */
    public function events(Request $request)
    {
        $query = Event::where('status', 'published')
            ->where('is_published', true)
            ->with(['region', 'airport']);

        // Apply filters
        if ($request->event_type) {
            $query->where('event_type', $request->event_type);
        }

        if ($request->region_id) {
            $query->where('region_id', $request->region_id);
        }

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('event_name', 'like', '%' . $request->search . '%')
                  ->orWhere('venue', 'like', '%' . $request->search . '%')
                  ->orWhere('event_code', 'like', '%' . $request->search . '%');
            });
        }

        $events = $query->orderBy('start_date')->paginate(12);

        $regions = \App\Models\Region::where('status', 'active')->get();
        $eventTypes = [
            'regional' => 'Regional',
            'airport' => 'Airport',
            'inter_airport' => 'Inter-Airport',
            'annual_meet' => 'Annual Meet'
        ];

        return view('website.events', compact('events', 'regions', 'eventTypes'));
    }

    /**
     * Display event details
     */
    public function eventDetails($slug)
    {
        $event = Event::where('event_code', $slug)
            ->orWhere('id', $slug)
            ->with(['region', 'airport', 'formTemplate'])
            ->firstOrFail();

        // Check if publicly viewable
        if (!$event->is_published && !auth()->guard('employee')->check()) {
            abort(404);
        }

        $registrationCount = $event->registrations()->count();
        $approvedCount = $event->registrations()->where('status', 'approved')->count();

        $relatedEvents = Event::where('id', '!=', $event->id)
            ->where('status', 'published')
            ->where(function($q) use ($event) {
                $q->where('region_id', $event->region_id)
                  ->orWhere('event_type', $event->event_type);
            })
            ->take(3)
            ->get();

        return view('website.event-details', compact(
            'event',
            'registrationCount',
            'approvedCount',
            'relatedEvents'
        ));
    }

    /**
     * Display announcements page
     */
    public function announcements(Request $request)
    {
        $announcements = Announcement::where('status', 'published')
            ->latest()
            ->paginate(10);

        return view('website.announcements', compact('announcements'));
    }

    /**
     * Display announcement details
     */
    public function announcementDetails($id)
    {
        $announcement = Announcement::findOrFail($id);

        // Increment view count
        $announcement->increment('views_count');

        $recentAnnouncements = Announcement::where('id', '!=', $id)
            ->where('status', 'published')
            ->latest()
            ->take(5)
            ->get();

        return view('website.announcement-details', compact('announcement', 'recentAnnouncements'));
    }

    /**
     * Display gallery page
     */
    public function gallery(Request $request)
    {
        $query = Gallery::latest();

        if ($request->category) {
            $query->where('category', $request->category);
        }

        $images = $query->paginate(20);
        $categories = Gallery::select('category')
            ->distinct()
            ->pluck('category');

        return view('website.gallery', compact('images', 'categories'));
    }

    /**
     * Display contact page
     */
    public function contact()
    {
        $regions = \App\Models\Region::with(['airports' => function($q) {
            $q->select('id', 'name', 'region_id', 'contact_person', 'contact_number', 'email');
        }])->where('status', 'active')->get();

        return view('website.contact', compact('regions'));
    }

    /**
     * Handle contact form submission
     */
    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
            'captcha' => 'required|captcha',
        ]);

        // Store contact query
        \App\Models\ContactQuery::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'ip_address' => $request->ip(),
            'status' => 'pending',
        ]);

        // Send notification to admin
        // Notification logic here

        return redirect()->back()
            ->with('success', 'Your message has been sent successfully. We will get back to you soon.');
    }

    /**
     * Display winners/hall of fame
     */
    public function winners(Request $request)
    {
        $winners = \App\Models\Winner::with(['employee', 'event'])
            ->latest()
            ->paginate(12);

        return view('website.winners', compact('winners'));
    }

    /**
     * Download forms and resources
     */
    public function downloads()
    {
        $forms = \App\Models\DownloadableForm::where('is_active', true)
            ->latest()
            ->get();

        return view('website.downloads', compact('forms'));
    }

    /**
     * Search functionality
     */
    public function search(Request $request)
    {
        $query = $request->q;

        $events = Event::where('status', 'published')
            ->where('is_published', true)
            ->where(function($q) use ($query) {
                $q->where('event_name', 'like', "%{$query}%")
                  ->orWhere('venue', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->take(10)
            ->get();

        $announcements = Announcement::where('status', 'published')
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            })
            ->take(5)
            ->get();

        return view('website.search-results', compact('events', 'announcements', 'query'));
    }

    /**
     * PWA manifest
     */
    public function manifest()
    {
        return response()->json([
            'name' => 'Regional Sports Control Board',
            'short_name' => 'RSCB',
            'description' => 'Centralized sports management portal for Regional Sports Control Board',
            'start_url' => '/',
            'display' => 'standalone',
            'background_color' => '#667eea',
            'theme_color' => '#667eea',
            'icons' => [
                [
                    'src' => asset('images/icons/icon-192x192.png'),
                    'sizes' => '192x192',
                    'type' => 'image/png'
                ],
                [
                    'src' => asset('images/icons/icon-512x512.png'),
                    'sizes' => '512x512',
                    'type' => 'image/png'
                ]
            ],
            'orientation' => 'portrait-primary',
            'scope' => '/',
            'lang' => 'en-IN',
            'categories' => ['sports', 'government', 'utilities'],
        ]);
    }

    /**
     * Service worker for PWA
     */
    public function serviceWorker()
    {
        return response()->view('website.service-worker')
            ->header('Content-Type', 'application/javascript');
    }
}
