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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{

    public function index()
    {
        $user = Auth::user();

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

    public function requestVerification()
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            abort(403);
        }

        if (empty($user->phone) || empty($user->address) || empty($user->ktp_path) || empty($user->sim_path)) {

            $missing = [];
            if (empty($user->phone)) $missing[] = "Nomor WhatsApp";
            if (empty($user->address)) $missing[] = "Alamat";
            if (empty($user->ktp_path)) $missing[] = "Foto KTP";
            if (empty($user->sim_path)) $missing[] = "Foto SIM";

            $pesanError = "Gagal mengajukan verifikasi. Harap lengkapi data berikut terlebih dahulu: " . implode(', ', $missing) . ".";

            return redirect()->back()->with('error', $pesanError);
        }

        $tokenFonnte = 'vC36PX9CRHcWUgffxgtz';
        $nomorWAAdmin = '082146724109';

        try {
            $checkDevice = Http::withHeaders([
                'Authorization' => $tokenFonnte,
            ])->post('https://api.fonnte.com/device');

            $deviceResult = $checkDevice->json();

            if (!$checkDevice->successful() || !isset($deviceResult['device_status']) || $deviceResult['device_status'] !== 'connect') {

                Log::warning('Verifikasi Gagal: WhatsApp Gateway Fonnte Disconnect/Error.', [
                    'api_response' => $deviceResult ?? 'No Response'
                ]);

                return redirect()->back()->with('error', 'Permintaan verifikasi gagal! Sistem WhatsApp Gateway kami sedang dalam pemeliharaan (Disconnect). Mohon mencoba kembali dalam beberapa saat.');
            }
        } catch (\Exception $e) {
            Log::error('Fonnte Connection Timeout/Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal terhubung ke server verifikasi. Silakan coba lagi nanti.');
        }

        $user->catatan_verifikasi = null;
        $user->save();

        $pesan = "🚨 *KUDA BESI RENT - PERMINTAAN VERIFIKASI* 🚨\n\n";
        $pesan .= "Halo Admin, pelanggan berikut telah mengajukan verifikasi akun dan selesai melengkapi dokumen:\n\n";
        $pesan .= "👤 *Nama:* " . $user->name . "\n";
        $pesan .= "✉️ *Email:* " . $user->email . "\n";
        $pesan .= "📱 *No. HP:* " . $user->phone . "\n\n";
        $pesan .= "Silakan buka Dashboard Admin *Filament* untuk memeriksa foto KTP/SIM dan menentukan status verifikasinya. Terima kasih!";

        try {
            $response = Http::withHeaders([
                'Authorization' => $tokenFonnte,
            ])->asForm()->post('https://api.fonnte.com/send', [
                'target' => $nomorWAAdmin,
                'message' => $pesan,
            ]);

            if (!$response->successful()) {
                Log::error('Fonnte Send Message Failed: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Fonnte Error: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Permintaan verifikasi telah dikirim! Admin Kuda Besi Rent akan segera memeriksa dokumen Anda.');
    }
}
