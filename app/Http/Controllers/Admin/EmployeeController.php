<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Region;
use App\Models\Airport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EmployeesImport;
use App\Exports\EmployeesExport;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:employee');
        $this->middleware('permission:view_employees');
    }

    /**
     * Display a listing of employees with DataTables
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $employees = Employee::with(['region', 'airport', 'roles'])
                ->select('employees.*');

            return DataTables::of($employees)
                ->addColumn('profile_photo', function ($employee) {
                    if ($employee->profile_photo) {
                        return '<img src="' . asset('storage/' . $employee->profile_photo) . '"
                                alt="Profile" class="rounded-circle" width="40" height="40">';
                    }
                    return '<div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px; font-size: 16px;">
                            ' . strtoupper(substr($employee->name, 0, 2)) . '</div>';
                })
                ->addColumn('full_details', function ($employee) {
                    return '<strong>' . $employee->name . '</strong><br>
                            <small class="text-muted">' . $employee->employee_id . '</small><br>
                            <small>' . $employee->designation . '</small>';
                })
                ->addColumn('region_name', function ($employee) {
                    return $employee->region ? $employee->region->name : 'N/A';
                })
                ->addColumn('airport_name', function ($employee) {
                    return $employee->airport ? $employee->airport->name : 'N/A';
                })
                ->addColumn('role_badge', function ($employee) {
                    $roles = $employee->getRoleNames();
                    $badges = '';
                    foreach ($roles as $role) {
                        $color = $this->getRoleBadgeColor($role);
                        $badges .= '<span class="badge bg-' . $color . ' me-1">' . ucwords(str_replace('_', ' ', $role)) . '</span>';
                    }
                    return $badges ?: '<span class="badge bg-secondary">No Role</span>';
                })
                ->addColumn('status', function ($employee) {
                    $statusColors = [
                        'active' => 'success',
                        'inactive' => 'danger',
                        'retired' => 'warning',
                        'transferred' => 'info'
                    ];
                    $color = $statusColors[$employee->employment_status] ?? 'secondary';
                    return '<span class="badge bg-' . $color . '">' . ucfirst($employee->employment_status) . '</span>';
                })
                ->addColumn('action', function ($employee) {
                    $actions = '<div class="btn-group">';

                    if (auth()->user()->can('view_employee_details')) {
                        $actions .= '<a href="' . route('admin.employees.show', $employee->id) . '"
                                    class="btn btn-sm btn-info" title="View Details">
                                    <i class="bi bi-eye"></i></a>';
                    }

                    if (auth()->user()->can('edit_employees')) {
                        $actions .= '<a href="' . route('admin.employees.edit', $employee->id) . '"
                                    class="btn btn-sm btn-primary" title="Edit">
                                    <i class="bi bi-pencil"></i></a>';
                    }

                    if (auth()->user()->can('delete_employees')) {
                        $actions .= '<button class="btn btn-sm btn-danger delete-employee"
                                    data-id="' . $employee->id . '"
                                    data-name="' . $employee->name . '"
                                    title="Delete">
                                    <i class="bi bi-trash"></i></button>';
                    }

                    $actions .= '</div>';
                    return $actions;
                })
                ->filterColumn('region_name', function($query, $keyword) {
                    $query->whereHas('region', function($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('airport_name', function($query, $keyword) {
                    $query->whereHas('airport', function($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })
                ->rawColumns(['profile_photo', 'full_details', 'role_badge', 'status', 'action'])
                ->make(true);
        }

        $regions = Region::where('status', 'active')->get();
        $airports = Airport::where('status', 'active')->get();

        return view('admin.employees.index', compact('regions', 'airports'));
    }

    /**
     * Show the form for creating a new employee
     */
    public function create()
    {
        $this->authorize('create_employees');

        $regions = Region::where('status', 'active')->get();
        $airports = Airport::where('status', 'active')->get();

        return view('admin.employees.create', compact('regions', 'airports'));
    }

    /**
     * Store a newly created employee
     */
    public function store(Request $request)
    {
        $this->authorize('create_employees');

        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|string|unique:employees,employee_id',
            'name' => 'required|string|max:255',
            'pan_number' => 'required|string|size:10|unique:employees,pan_number|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
            'designation' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'airport_id' => 'required|exists:airports,id',
            'region_id' => 'required|exists:regions,id',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date|before:-18 years',
            'email' => 'required|email|unique:employees,email',
            'mobile' => 'nullable|string|max:15',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sports_category' => 'nullable|string|max:255',
            'blood_group' => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'medical_conditions' => 'nullable|string',
            'employment_status' => 'required|in:active,inactive,retired,transferred',
        ], [
            'pan_number.regex' => 'Invalid PAN number format. Should be like ABCDE1234F',
            'date_of_birth.before' => 'Employee must be at least 18 years old.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $employeeData = $request->except(['profile_photo', '_token']);

            // Set default password as PAN number
            $employeeData['password'] = Hash::make($request->pan_number);
            $employeeData['force_password_change'] = true;

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                $employeeData['profile_photo'] = $request->file('profile_photo')
                    ->store('employee-photos', 'public');
            }

            $employee = Employee::create($employeeData);

            // Assign default employee role
            $employee->assignRole('employee');

            // Log activity
            activity()
                ->performedOn($employee)
                ->causedBy(auth()->user())
                ->withProperties(['employee_id' => $employee->employee_id])
                ->log('created employee');

            DB::commit();

            return redirect()->route('admin.employees.index')
                ->with('success', 'Employee created successfully. Default password is PAN number.');

        } catch (\Exception $e) {
            DB::rollBack();

            // Delete uploaded file if exists
            if (isset($employeeData['profile_photo'])) {
                Storage::disk('public')->delete($employeeData['profile_photo']);
            }

            return redirect()->back()
                ->with('error', 'Failed to create employee: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified employee
     */
    public function show($id)
    {
        $this->authorize('view_employee_details');

        $employee = Employee::with([
            'region',
            'airport',
            'eventRegistrations.event',
            'documents',
            'certificates'
        ])->findOrFail($id);

        $statistics = [
            'total_registrations' => $employee->eventRegistrations->count(),
            'approved_registrations' => $employee->eventRegistrations->where('status', 'approved')->count(),
            'pending_registrations' => $employee->eventRegistrations->where('status', 'pending')->count(),
            'rejected_registrations' => $employee->eventRegistrations->where('status', 'rejected')->count(),
            'total_documents' => $employee->documents->count(),
            'verified_documents' => $employee->documents->where('verification_status', 'verified')->count(),
            'total_certificates' => $employee->certificates->count(),
        ];

        // Get login history
        $loginHistory = \Spatie\Activitylog\Models\Activity::where('subject_type', Employee::class)
            ->where('subject_id', $employee->id)
            ->where('description', 'logged in')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.employees.show', compact('employee', 'statistics', 'loginHistory'));
    }

    /**
     * Show the form for editing the specified employee
     */
    public function edit($id)
    {
        $this->authorize('edit_employees');

        $employee = Employee::findOrFail($id);
        $regions = Region::where('status', 'active')->get();
        $airports = Airport::where('status', 'active')->get();

        return view('admin.employees.edit', compact('employee', 'regions', 'airports'));
    }

    /**
     * Update the specified employee
     */
    public function update(Request $request, $id)
    {
        $this->authorize('edit_employees');

        $employee = Employee::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|string|unique:employees,employee_id,' . $employee->id,
            'name' => 'required|string|max:255',
            'pan_number' => 'required|string|size:10|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/|unique:employees,pan_number,' . $employee->id,
            'designation' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'airport_id' => 'required|exists:airports,id',
            'region_id' => 'required|exists:regions,id',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date|before:-18 years',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'mobile' => 'nullable|string|max:15',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sports_category' => 'nullable|string|max:255',
            'blood_group' => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'medical_conditions' => 'nullable|string',
            'employment_status' => 'required|in:active,inactive,retired,transferred',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $employeeData = $request->except(['profile_photo', '_token']);

            // Handle profile photo update
            if ($request->hasFile('profile_photo')) {
                // Delete old photo
                if ($employee->profile_photo) {
                    Storage::disk('public')->delete($employee->profile_photo);
                }
                $employeeData['profile_photo'] = $request->file('profile_photo')
                    ->store('employee-photos', 'public');
            }

            $employee->update($employeeData);

            // Log activity
            activity()
                ->performedOn($employee)
                ->causedBy(auth()->user())
                ->withProperties(['changes' => $employee->getChanges()])
                ->log('updated employee');

            DB::commit();

            return redirect()->route('admin.employees.index')
                ->with('success', 'Employee updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update employee: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified employee
     */
    public function destroy($id)
    {
        $this->authorize('delete_employees');

        $employee = Employee::findOrFail($id);

        // Check if employee has active registrations
        $activeRegistrations = $employee->eventRegistrations()
            ->whereIn('status', ['pending', 'approved'])
            ->count();

        if ($activeRegistrations > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete employee with active event registrations.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Soft delete the employee
            $employee->delete();

            // Log activity
            activity()
                ->performedOn($employee)
                ->causedBy(auth()->user())
                ->log('deleted employee');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Employee deleted successfully.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete employee: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle employee status
     */
    public function toggleStatus(Request $request, $id)
    {
        $this->authorize('edit_employees');

        $employee = Employee::findOrFail($id);
        $newStatus = $request->status;

        if (!in_array($newStatus, ['active', 'inactive', 'retired', 'transferred'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid status.'
            ], 422);
        }

        $employee->employment_status = $newStatus;
        $employee->save();

        activity()
            ->performedOn($employee)
            ->causedBy(auth()->user())
            ->withProperties(['status' => $newStatus])
            ->log('changed employee status');

        return response()->json([
            'success' => true,
            'message' => 'Employee status updated successfully.',
            'status' => ucfirst($newStatus)
        ]);
    }

    /**
     * Reset employee password
     */
    public function resetPassword(Request $request, $id)
    {
        $this->authorize('edit_employees');

        $employee = Employee::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'new_password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $employee->password = Hash::make($request->new_password);
        $employee->force_password_change = true;
        $employee->save();

        activity()
            ->performedOn($employee)
            ->causedBy(auth()->user())
            ->log('reset employee password');

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully.'
        ]);
    }

    /**
     * Show import form
     */
    public function showImportForm()
    {
        $this->authorize('import_employees');
        return view('admin.employees.import');
    }

    /**
     * Import employees from Excel
     */
    public function import(Request $request)
    {
        $this->authorize('import_employees');

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        DB::beginTransaction();
        try {
            $import = new EmployeesImport();
            Excel::import($import, $request->file('file'));

            $results = [
                'total' => $import->getRowCount(),
                'imported' => $import->getImportedCount(),
                'failed' => $import->getFailedCount(),
                'errors' => $import->getErrors()
            ];

            activity()
                ->causedBy(auth()->user())
                ->withProperties($results)
                ->log('imported employees');

            DB::commit();

            if ($results['failed'] > 0) {
                return redirect()->route('admin.employees.index')
                    ->with('warning', "{$results['imported']} employees imported successfully. {$results['failed']} records failed.")
                    ->with('import_errors', $results['errors']);
            }

            return redirect()->route('admin.employees.index')
                ->with('success', "{$results['imported']} employees imported successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Export employees to Excel
     */
    public function export(Request $request)
    {
        $this->authorize('export_employees');

        $filters = $request->only([
            'region_id',
            'airport_id',
            'employment_status',
            'gender',
            'department'
        ]);

        return Excel::download(
            new EmployeesExport($filters),
            'employees_' . date('Y-m-d_His') . '.xlsx'
        );
    }

    /**
     * Download sample Excel template
     */
    public function downloadTemplate()
    {
        $headers = [
            'Employee ID',
            'Name',
            'PAN Number',
            'Designation',
            'Department',
            'Airport Code',
            'Region Code',
            'Gender (male/female/other)',
            'Date of Birth (YYYY-MM-DD)',
            'Email',
            'Mobile',
            'Sports Category',
            'Blood Group',
            'Medical Conditions',
            'Employment Status (active/inactive)',
        ];

        return Excel::download(
            new class($headers) implements \Maatwebsite\Excel\Concerns\FromArray {
                private $headers;

                public function __construct($headers) {
                    $this->headers = $headers;
                }

                public function array(): array {
                    return [$this->headers];
                }
            },
            'employee_import_template.xlsx'
        );
    }

    /**
     * Get airports by region (AJAX)
     */
    public function getAirportsByRegion($regionId)
    {
        $airports = Airport::where('region_id', $regionId)
            ->where('status', 'active')
            ->get(['id', 'name', 'code']);

        return response()->json($airports);
    }

    /**
     * Check if employee ID exists (AJAX)
     */
    public function checkEmployeeId(Request $request)
    {
        $exists = Employee::where('employee_id', $request->employee_id)
            ->when($request->has('exclude_id'), function($q) use ($request) {
                $q->where('id', '!=', $request->exclude_id);
            })
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    /**
     * Get role badge color
     */
    private function getRoleBadgeColor($role)
    {
        $colors = [
            'super_admin' => 'danger',
            'regional_sports_secretary' => 'primary',
            'airport_sports_secretary' => 'info',
            'employee' => 'success',
        ];

        return $colors[$role] ?? 'secondary';
    }
}
