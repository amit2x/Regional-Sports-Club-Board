<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class GalleryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:employee');
        $this->middleware('permission:manage_gallery');
    }

    public function index()
    {
        $images = Gallery::with('uploader')
            ->latest()
            ->paginate(24);

        return view('admin.gallery.index', compact('images'));
    }

    public function create()
    {
        $events = \App\Models\Event::whereIn('status', ['published', 'completed'])->get();
        return view('admin.gallery.create', compact('events'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'category' => 'nullable|string',
            'description' => 'nullable|string',
            'is_featured' => 'boolean',
            'event_id' => 'nullable|exists:events,id',
        ]);

        // Store original image
        $imagePath = $request->file('image')->store('gallery', 'public');

        // Create thumbnail
        $thumbnailPath = 'gallery/thumbnails/' . basename($imagePath);
        $thumbnail = Image::make(storage_path('app/public/' . $imagePath))
            ->fit(400, 300)
            ->save(storage_path('app/public/' . $thumbnailPath));

        Gallery::create([
            'title' => $request->title,
            'image_path' => $imagePath,
            'thumbnail_path' => $thumbnailPath,
            'category' => $request->category,
            'description' => $request->description,
            'is_featured' => $request->is_featured ?? false,
            'event_id' => $request->event_id,
            'uploaded_by' => auth()->guard('employee')->id(),
        ]);

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Image uploaded successfully.');
    }

    public function uploadMultiple(Request $request)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'category' => 'nullable|string',
            'event_id' => 'nullable|exists:events,id',
        ]);

        $uploaded = 0;
        foreach ($request->file('images') as $image) {
            $imagePath = $image->store('gallery', 'public');

            $thumbnailPath = 'gallery/thumbnails/' . basename($imagePath);
            Image::make(storage_path('app/public/' . $imagePath))
                ->fit(400, 300)
                ->save(storage_path('app/public/' . $thumbnailPath));

            Gallery::create([
                'title' => $image->getClientOriginalName(),
                'image_path' => $imagePath,
                'thumbnail_path' => $thumbnailPath,
                'category' => $request->category,
                'event_id' => $request->event_id,
                'uploaded_by' => auth()->guard('employee')->id(),
            ]);

            $uploaded++;
        }

        return response()->json([
            'success' => true,
            'message' => "{$uploaded} images uploaded successfully."
        ]);
    }

    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);

        Storage::disk('public')->delete([
            $gallery->image_path,
            $gallery->thumbnail_path
        ]);

        $gallery->delete();

        return response()->json([
            'success' => true,
            'message' => 'Image deleted successfully.'
        ]);
    }
}
