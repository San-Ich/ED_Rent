<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{

    public function index()
    {
        // Mengambil data user yang sedang login saat ini
        $user = Auth::user();

        // 🌟 Arahkan ke nama file view kamu (misal: detail-profile.blade.php)
        return view('profile', compact('user'));
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if (! $user instanceof User) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'ktp' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'sim' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->address = $request->address;

        if (!$user->is_verified && $request->hasFile('ktp')) {
            if ($user->ktp_path) {
                Storage::disk('public')->delete($user->ktp_path);
            }
            $user->ktp_path = $request->file('ktp')->store('documents/ktp', 'public');
        }

        if (!$user->is_verified && $request->hasFile('sim')) {
            if ($user->sim_path) {
                Storage::disk('public')->delete($user->sim_path);
            }
            $user->sim_path = $request->file('sim')->store('documents/sim', 'public');
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Informasi profil dan berkas identitas Anda telah diperbarui.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
