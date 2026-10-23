<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors([
                    'email' => 'Your account has been deactivated. Please contact the Health Services Office.',
                ]);
            }

            $request->session()->regenerate();

            ActivityLog::log(
                $user->id,
                'Authentication',
                'Login',
                "User {$user->name} ({$user->role}) logged in."
            );

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'Welcome back, Administrator ' . $user->name . '!');
            }

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Welcome back, ' . $user->name . '!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role' => ['required', 'string', 'in:student,faculty_staff'], // Strict prevention of admin self-assignment
            'id_number' => ['required', 'string', 'max:50'],
            'department_or_course' => ['required', 'string', 'max:150'],
            'contact_number' => ['required', 'string', 'max:20'],
            'emergency_contact_name' => ['required', 'string', 'max:150'],
            'emergency_contact_number' => ['required', 'string', 'max:20'],
            'gender' => ['nullable', 'string', 'in:Male,Female,Prefer not to say'],
            'birth_date' => ['nullable', 'date'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['accepted'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'id_number' => $validated['id_number'],
            'department_or_course' => $validated['department_or_course'],
            'contact_number' => $validated['contact_number'],
            'emergency_contact_name' => $validated['emergency_contact_name'],
            'emergency_contact_number' => $validated['emergency_contact_number'],
            'gender' => $validated['gender'] ?? null,
            'birth_date' => $validated['birth_date'] ?? null,
            'is_active' => true,
        ]);

        Auth::login($user);

        ActivityLog::log(
            $user->id,
            'Authentication',
            'Registration',
            "New {$user->role} registered: {$user->name} ({$user->id_number})"
        );

        return redirect()->route('dashboard')->with('success', 'Registration successful! Welcome to AdU eCare+.');
    }

    public function logout(Request $request)
    {
        $userId = Auth::id();
        $userName = Auth::user()?->name;

        if ($userId) {
            ActivityLog::log($userId, 'Authentication', 'Logout', "User {$userName} logged out.");
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been logged out successfully.');
    }

    public function showProfile()
    {
        return view('profile.show', [
            'user' => Auth::user(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'string', 'max:20'],
            'department_or_course' => ['required', 'string', 'max:150'],
            'emergency_contact_name' => ['required', 'string', 'max:150'],
            'emergency_contact_number' => ['required', 'string', 'max:20'],
            'gender' => ['nullable', 'string', 'in:Male,Female,Prefer not to say'],
            'birth_date' => ['nullable', 'date'],
        ]);

        $user->update($validated);

        ActivityLog::log($user->id, 'Profile', 'Update', "Updated profile details.");

        return back()->with('success', 'Profile information updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        ActivityLog::log($user->id, 'Profile', 'Password Change', "Changed account password.");

        return back()->with('success', 'Your password has been changed successfully.');
    }
}
