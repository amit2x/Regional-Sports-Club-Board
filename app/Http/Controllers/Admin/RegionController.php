<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class RegionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:employee');
        $this->middleware('permission:view_regions');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $regions = Region::withCount(['airports', 'employees', 'events'])->latest();

            return DataTables::of($regions)
                ->addColumn('status_badge', function($region) {
                    $color = $region->status === 'active' ? 'success' : 'danger';
                    return '<span class="badge bg-' . $color . '">' . ucfirst($region->status) . '</span>';
                })
                ->addColumn('action', function($region) {
                    $actions = '<div class="btn-group">';

                    if (auth()->user()->can('edit_regions')) {
                        $actions .= '<button class="btn btn-sm btn-primary edit-region"
                                    data-id="' . $region->id . '" title="Edit">
                                    <i class="bi bi-pencil"></i></button>';
                    }

                    if (auth()->user()->can('delete_regions')) {
                        $actions .= '<button class="btn btn-sm btn-danger delete-region"
                                    data-id="' . $region->id . '" title="Delete">
                                    <i class="bi bi-trash"></i></button>';
                    }

                    $actions .= '</div>';
                    return $actions;
                })
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }

        return view('admin.regions.index');
    }

    public function store(Request $request)
    {
        $this->authorize('create_regions');

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:regions',
            'code' => 'required|string|max:10|unique:regions',
            'headquarters' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $region = Region::create($request->all());

        activity()
            ->performedOn($region)
            ->causedBy(auth()->guard('employee')->user())
            ->log('created region');

        return response()->json([
            'success' => true,
            'message' => 'Region created successfully.',
            'region' => $region
        ]);
    }

    public function show($id)
    {
        $region = Region::withCount(['airports', 'employees', 'events'])->findOrFail($id);

        $airports = $region->airports()->withCount('employees')->get();

        $recentEvents = $region->events()->latest()->take(5)->get();

        return view('admin.regions.show', compact('region', 'airports', 'recentEvents'));
    }

    public function update(Request $request, $id)
    {
        $this->authorize('edit_regions');

        $region = Region::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:regions,name,' . $id,
            'code' => 'required|string|max:10|unique:regions,code,' . $id,
            'headquarters' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $region->update($request->all());

        activity()
            ->performedOn($region)
            ->causedBy(auth()->guard('employee')->user())
            ->log('updated region');

        return response()->json([
            'success' => true,
            'message' => 'Region updated successfully.'
        ]);
    }

    public function destroy($id)
    {
        $this->authorize('delete_regions');

        $region = Region::withCount('airports')->findOrFail($id);

        if ($region->airports_count > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete region with associated airports.'
            ], 422);
        }

        $region->delete();

        activity()
            ->performedOn($region)
            ->causedBy(auth()->guard('employee')->user())
            ->log('deleted region');

        return response()->json([
            'success' => true,
            'message' => 'Region deleted successfully.'
        ]);
    }
}
