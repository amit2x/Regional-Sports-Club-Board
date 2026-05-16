<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    /**
     * Login API
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|string',
            'password' => 'required|string',
            'device_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $employee = Employee::where('employee_id', $request->employee_id)
            ->where('employment_status', 'active')
            ->first();

        if (!$employee || !Hash::check($request->password, $employee->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Create token
        $token = $employee->createToken($request->device_name, ['employee-access']);

        // Check if force password change required
        if ($employee->force_password_change) {
            return response()->json([
                'success' => true,
                'message' => 'Login successful. Please change your password.',
                'force_password_change' => true,
                'token' => $token->plainTextToken,
                'employee' => $this->getEmployeeData($employee),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'token' => $token->plainTextToken,
            'employee' => $this->getEmployeeData($employee),
        ]);
    }

    /**
     * Logout API
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Get authenticated user profile
     */
    public function profile(Request $request)
    {
        $employee = $request->user();

        return response()->json([
            'success' => true,
            'data' => $this->getEmployeeData($employee)
        ]);
    }

    /**
     * Change password API
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
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

        $employee = $request->user();

        if (!Hash::check($request->current_password, $employee->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 422);
        }

        $employee->password = Hash::make($request->new_password);
        $employee->force_password_change = false;
        $employee->save();

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully'
        ]);
    }

    private function getEmployeeData($employee)
    {
        return [
            'id' => $employee->id,
            'employee_id' => $employee->employee_id,
            'name' => $employee->name,
            'email' => $employee->email,
            'mobile' => $employee->mobile,
            'designation' => $employee->designation,
            'department' => $employee->department,
            'airport' => $employee->airport ? $employee->airport->name : null,
            'region' => $employee->region ? $employee->region->name : null,
            'gender' => $employee->gender,
            'age' => $employee->age,
            'sports_category' => $employee->sports_category,
            'profile_photo_url' => $employee->profile_photo ? asset('storage/' . $employee->profile_photo) : null,
            'roles' => $employee->getRoleNames(),
        ];
    }
}
