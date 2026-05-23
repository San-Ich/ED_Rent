<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        if (! $user instanceof User) {
            abort(403);
        }

        $request->validate([
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'ktp_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sim_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user->phone = $request->phone;
        $user->address = $request->address;

        if ($request->hasFile('ktp_path')) {
            if ($user->ktp_path) {
                Storage::disk('local')->delete($user->ktp_path);
            }
            $user->ktp_path = $request->file('ktp_path')->store('identitas-ktp', 'local');
            $user->is_verified = false; // Reset verifikasi karena dokumen berubah
        }

        // Proses Upload SIM
        if ($request->hasFile('sim_path')) {
            if ($user->sim_path) {
                Storage::disk('local')->delete($user->sim_path);
            }
            $user->sim_path = $request->file('sim_path')->store('identitas-sim', 'local');
            $user->is_verified = false;
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil dan dokumen berhasil diperbarui. Tunggu verifikasi admin.');
    }
}
