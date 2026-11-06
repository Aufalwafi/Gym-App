<?php

namespace App\Http\Controllers;

use App\Models\BodyMeasurement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'))->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan tidak sesuai.',
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
            'password' => ['required', 'confirmed', Password::defaults()],
            'gender' => ['nullable', 'in:male,female'],
            'age' => ['nullable', 'integer', 'min:12', 'max:100'],
            'height_cm' => ['nullable', 'numeric', 'min:100', 'max:250'],
            'initial_weight_kg' => ['nullable', 'numeric', 'min:30', 'max:300'],
            'target_weight_kg' => ['nullable', 'numeric', 'min:30', 'max:300'],
            'fitness_goal' => ['nullable', 'in:bulking,cutting,maintenance'],
            'activity_level' => ['nullable', 'in:sedentary,light,moderate,active,very_active'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'gender' => $validated['gender'] ?? 'male',
            'age' => $validated['age'] ?? 20,
            'height_cm' => $validated['height_cm'] ?? 170,
            'target_weight_kg' => $validated['target_weight_kg'] ?? null,
            'fitness_goal' => $validated['fitness_goal'] ?? 'maintenance',
            'activity_level' => $validated['activity_level'] ?? 'moderate',
            'theme_preference' => 'dark',
        ]);

        if (!empty($validated['initial_weight_kg'])) {
            BodyMeasurement::create([
                'user_id' => $user->id,
                'recorded_date' => Carbon::today(),
                'weight_kg' => $validated['initial_weight_kg'],
                'notes' => 'Berat awal saat registrasi akun FitTrack',
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Akun berhasil dibuat! Selamat datang di FitTrack!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }
}
