<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Laravel\Socialite\Facades\Socialite;

class EmployeeAuthController extends Controller
{
    /**
     * Where to redirect employees after login.
     *
     * @var string
     */
    protected $redirectTo = '/employee/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:employee')->except([
            'logout',
            'showChangePasswordForm',
            'changePassword'
        ]);
    }

    /**
     * Show the employee login form.
     */
    public function showLoginForm()
    {
        return view('auth.employee-login');
    }

    /**
     * Handle employee login.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|string',
            'password' => 'required|string',
        ], [
            'employee_id.required' => 'Please enter your Employee ID.',
            'password.required' => 'Please enter your password.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        // Find employee by employee_id
        $employee = Employee::where('employee_id', $request->employee_id)->first();

        if (!$employee) {
            return redirect()->back()
                ->withErrors(['employee_id' => 'No account found with this Employee ID.'])
                ->withInput($request->except('password'));
        }

        // Check if employee is active
        if ($employee->employment_status !== 'active') {
            return redirect()->back()
                ->withErrors(['employee_id' => 'Your account is ' . $employee->employment_status . '. Please contact the administrator.'])
                ->withInput($request->except('password'));
        }

        // Check if using default password (PAN Number)
        if ($request->password === $employee->pan_number) {
            Auth::guard('employee')->login($employee);

            // Log activity
            activity()
                ->performedOn($employee)
                ->causedBy($employee)
                ->withProperties(['login_method' => 'default_password'])
                ->log('first login with default password');

            return redirect()->route('employee.password.change')
                ->with('warning', 'You are using the default password. Please change your password for security reasons.');
        }

        // Attempt login with credentials
        $credentials = [
            'employee_id' => $request->employee_id,
            'password' => $request->password,
        ];

        if (Auth::guard('employee')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $employee = Auth::guard('employee')->user();

            // Log activity
            activity()
                ->performedOn($employee)
                ->causedBy($employee)
                ->withProperties(['ip' => $request->ip()])
                ->log('logged in');

            // Check if force password change is required
            if ($employee->force_password_change) {
                return redirect()->route('employee.password.change')
                    ->with('warning', 'Please change your password before continuing.');
            }

            // Redirect based on role
            return $this->authenticated($request, $employee);
        }

        return redirect()->back()
            ->withErrors(['password' => 'Invalid password. Please try again.'])
            ->withInput($request->except('password'));
    }

    /**
     * Handle post-authentication redirect based on role.
     */
    protected function authenticated(Request $request, $employee)
    {
        if ($employee->hasRole('super_admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($employee->hasRole('regional_sports_secretary')) {
            return redirect()->route('admin.regional.dashboard');
        } elseif ($employee->hasRole('airport_sports_secretary')) {
            return redirect()->route('admin.airport.dashboard');
        } else {
            return redirect()->route('employee.dashboard');
        }
    }

    /**
     * Show the password change form.
     */
    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    /**
     * Handle password change.
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
        ], [
            'new_password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number and one special character.',
            'new_password.confirmed' => 'Password confirmation does not match.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $employee = Auth::guard('employee')->user();

        // Verify current password
        if (!Hash::check($request->current_password, $employee->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Check if new password is same as PAN number
        if ($request->new_password === $employee->pan_number) {
            return redirect()->back()
                ->withErrors(['new_password' => 'Password cannot be the same as your PAN number.']);
        }

        // Update password
        $employee->password = Hash::make($request->new_password);
        $employee->force_password_change = false;
        $employee->save();

        // Log activity
        activity()
            ->performedOn($employee)
            ->causedBy($employee)
            ->log('changed password');

        return redirect()->route('employee.dashboard')
            ->with('success', 'Password changed successfully.');
    }

    /**
     * Show forgot password form.
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|string',
            'email' => 'required|email',
        ]);

        $employee = Employee::where('employee_id', $request->employee_id)
            ->where('email', $request->email)
            ->first();

        if (!$employee) {
            return redirect()->back()
                ->withErrors(['employee_id' => 'No matching employee found with the provided Employee ID and Email.'])
                ->withInput();
        }

        // Send password reset link
        $status = Password::broker('employees')->sendResetLink(
            ['email' => $employee->email]
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Password reset link has been sent to your email address.')
            : back()->withErrors(['email' => 'Unable to send reset link. Please try again.']);
    }

    /**
     * Redirect to Google for authentication.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google callback.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $employee = Employee::where('email', $googleUser->email)->first();

            if (!$employee) {
                return redirect()->route('employee.login')
                    ->withErrors(['google' => 'No account found with this Google email. Please use Employee ID to login.']);
            }

            if ($employee->employment_status !== 'active') {
                return redirect()->route('employee.login')
                    ->withErrors(['google' => 'Your account is ' . $employee->employment_status . '.']);
            }

            Auth::guard('employee')->login($employee);

            // Log activity
            activity()
                ->performedOn($employee)
                ->causedBy($employee)
                ->withProperties(['login_method' => 'google_oauth'])
                ->log('logged in via Google');

            return $this->authenticated(request(), $employee);

        } catch (\Exception $e) {
            return redirect()->route('employee.login')
                ->withErrors(['google' => 'Unable to login with Google. Please try again.']);
        }
    }

    /**
     * Logout employee.
     */
    public function logout(Request $request)
    {
        $employee = Auth::guard('employee')->user();

        if ($employee) {
            // Log activity
            activity()
                ->performedOn($employee)
                ->causedBy($employee)
                ->log('logged out');
        }

        Auth::guard('employee')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Get the guard to be used during authentication.
     */
    protected function guard()
    {
        return Auth::guard('employee');
    }
}
