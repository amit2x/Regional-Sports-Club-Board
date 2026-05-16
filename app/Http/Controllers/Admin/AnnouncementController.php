<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AnnouncementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:employee');
        $this->middleware('permission:manage_announcements');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $announcements = Announcement::with('creator')->latest();

            return DataTables::of($announcements)
                ->addColumn('title_preview', function($announcement) {
                    return '<strong>' . $announcement->title . '</strong><br>
                            <small class="text-muted">' . \Str::limit(strip_tags($announcement->content), 100) . '</small>';
                })
                ->addColumn('status_badge', function($announcement) {
                    $colors = [
                        'draft' => 'secondary',
                        'published' => 'success',
                        'archived' => 'warning'
                    ];
                    $color = $colors[$announcement->status] ?? 'secondary';
                    return '<span class="badge bg-' . $color . '">' . ucfirst($announcement->status) . '</span>';
                })
                ->addColumn('priority_badge', function($announcement) {
                    $colors = [
                        'low' => 'info',
                        'medium' => 'warning',
                        'high' => 'danger',
                        'urgent' => 'dark'
                    ];
                    $color = $colors[$announcement->priority] ?? 'info';
                    return '<span class="badge bg-' . $color . '">' . ucfirst($announcement->priority) . '</span>';
                })
                ->addColumn('action', function($announcement) {
                    $actions = '<div class="btn-group">';

                    if (auth()->user()->can('publish_announcements') && $announcement->status === 'draft') {
                        $actions .= '<button class="btn btn-sm btn-success publish-announcement"
                                    data-id="' . $announcement->id . '" title="Publish">
                                    <i class="bi bi-send"></i></button>';
                    }

                    $actions .= '<a href="' . route('admin.announcements.edit', $announcement->id) . '"
                                class="btn btn-sm btn-primary" title="Edit">
                                <i class="bi bi-pencil"></i></a>';

                    $actions .= '<button class="btn btn-sm btn-danger delete-announcement"
                                data-id="' . $announcement->id . '" title="Delete">
                                <i class="bi bi-trash"></i></button>';

                    $actions .= '</div>';
                    return $actions;
                })
                ->rawColumns(['title_preview', 'status_badge', 'priority_badge', 'action'])
                ->make(true);
        }

        return view('admin.announcements.index');
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'valid_from' => 'required|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'status' => 'required|in:draft,published',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $announcement = Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'priority' => $request->priority,
            'valid_from' => $request->valid_from,
            'valid_until' => $request->valid_until,
            'status' => $request->status,
            'created_by' => auth()->guard('employee')->id(),
        ]);

        // If published, send notification
        if ($request->status === 'published') {
            $this->notifyEmployees($announcement);
        }

        activity()
            ->performedOn($announcement)
            ->causedBy(auth()->guard('employee')->user())
            ->log('created announcement');

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    public function edit($id)
    {
        $announcement = Announcement::findOrFail($id);
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'valid_from' => 'required|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'status' => 'required|in:draft,published,archived',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $wasPublished = $announcement->status === 'published';
        $nowPublished = $request->status === 'published';

        $announcement->update($request->all());

        // Notify if newly published
        if (!$wasPublished && $nowPublished) {
            $this->notifyEmployees($announcement);
        }

        activity()
            ->performedOn($announcement)
            ->causedBy(auth()->guard('employee')->user())
            ->log('updated announcement');

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        return response()->json([
            'success' => true,
            'message' => 'Announcement deleted successfully.'
        ]);
    }

    public function publish($id)
    {
        $announcement = Announcement::findOrFail($id);

        if ($announcement->status !== 'draft') {
            return response()->json([
                'success' => false,
                'message' => 'Only draft announcements can be published.'
            ], 422);
        }

        $announcement->status = 'published';
        $announcement->published_at = now();
        $announcement->save();

        $this->notifyEmployees($announcement);

        return response()->json([
            'success' => true,
            'message' => 'Announcement published successfully.'
        ]);
    }

    private function notifyEmployees($announcement)
    {
        // Send to relevant employees based on scope
        \App\Models\Employee::where('employment_status', 'active')
            ->chunk(100, function($employees) use ($announcement) {
                foreach ($employees as $employee) {
                    $employee->notify(new \App\Notifications\NewAnnouncement($announcement));
                }
            });
    }
}
