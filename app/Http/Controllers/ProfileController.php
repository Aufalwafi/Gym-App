<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'gender' => 'required|in:male,female',
            'age' => 'required|integer|min:10|max:100',
            'height_cm' => 'required|numeric|min:100|max:250',
            'target_weight_kg' => 'nullable|numeric|min:30|max:300',
            'fitness_goal' => 'required|in:bulking,cutting,maintenance',
            'activity_level' => 'required|in:sedentary,light,moderate,active,very_active',
            'theme_preference' => 'required|in:dark,light',
        ]);

        $user->update($validated);

        return redirect()->route('profile.edit')->with('success', 'Profil kebugaran Anda berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.edit')->with('success', 'Password Anda berhasil diperbarui!');
    }
}
