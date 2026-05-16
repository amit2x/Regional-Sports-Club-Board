<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Airport;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AirportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:employee');
        $this->middleware('permission:view_airports');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $airports = Airport::with('region')
                ->withCount('employees', 'events')
                ->latest();

            return DataTables::of($airports)
                ->addColumn('region_name', function($airport) {
                    return $airport->region ? $airport->region->name : 'N/A';
                })
                ->addColumn('status_badge', function($airport) {
                    $color = $airport->status === 'active' ? 'success' : 'danger';
                    return '<span class="badge bg-' . $color . '">' . ucfirst($airport->status) . '</span>';
                })
                ->addColumn('action', function($airport) {
                    $actions = '<div class="btn-group">';

                    if (auth()->user()->can('edit_airports')) {
                        $actions .= '<button class="btn btn-sm btn-primary edit-airport"
                                    data-id="' . $airport->id . '" title="Edit">
                                    <i class="bi bi-pencil"></i></button>';
                    }

                    if (auth()->user()->can('delete_airports')) {
                        $actions .= '<button class="btn btn-sm btn-danger delete-airport"
                                    data-id="' . $airport->id . '" title="Delete">
                                    <i class="bi bi-trash"></i></button>';
                    }

                    $actions .= '</div>';
                    return $actions;
                })
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }

        $regions = Region::where('status', 'active')->get();
        return view('admin.airports.index', compact('regions'));
    }

    public function store(Request $request)
    {
        $this->authorize('create_airports');

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:airports',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'region_id' => 'required|exists:regions,id',
            'address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:15',
            'email' => 'nullable|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $airport = Airport::create($request->all());

        activity()
            ->performedOn($airport)
            ->causedBy(auth()->guard('employee')->user())
            ->log('created airport');

        return response()->json([
            'success' => true,
            'message' => 'Airport created successfully.',
            'airport' => $airport
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('edit_airports');

        $airport = Airport::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:airports,code,' . $id,
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'region_id' => 'required|exists:regions,id',
            'address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:15',
            'email' => 'nullable|email',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $airport->update($request->all());

        activity()
            ->performedOn($airport)
            ->causedBy(auth()->guard('employee')->user())
            ->log('updated airport');

        return response()->json([
            'success' => true,
            'message' => 'Airport updated successfully.'
        ]);
    }

    public function destroy($id)
    {
        $this->authorize('delete_airports');

        $airport = Airport::withCount('employees')->findOrFail($id);

        if ($airport->employees_count > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete airport with associated employees.'
            ], 422);
        }

        $airport->delete();

        activity()
            ->performedOn($airport)
            ->causedBy(auth()->guard('employee')->user())
            ->log('deleted airport');

        return response()->json([
            'success' => true,
            'message' => 'Airport deleted successfully.'
        ]);
    }
}
