<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);

        $loginInput = trim($request->input('email'));
        $passwordInput = $request->input('password');

        // Cari user berdasarkan email, nomor telepon, nama, atau kata kunci "Admin"
        $user = User::where(function ($q) use ($loginInput) {
            $q->where('email', $loginInput)
              ->orWhere('email', strtolower($loginInput))
              ->orWhere('phone', $loginInput)
              ->orWhere('name', $loginInput);
            
            if (strtolower($loginInput) === 'admin') {
                $q->orWhere('role', 'admin');
            }
        })->first();

        // Cek kecocokan password: aman untuk plain text maupun bcrypt hash (mencegah crash RuntimeException pada Laravel 12)
        $passwordMatches = false;
        if ($user) {
            if ($user->password === $passwordInput) {
                $passwordMatches = true;
            } else {
                try {
                    $passwordMatches = Hash::check($passwordInput, $user->password);
                } catch (\Throwable $e) {
                    $passwordMatches = false;
                }
            }
        }

        // Autentikasi berhasil
        if ($user && $passwordMatches) {
            if ($user->is_active === false) {
                return back()->withErrors([
                    'email' => 'Akun Anda telah dinonaktifkan oleh administrator. Silakan hubungi admin.',
                ])->with('error_modal', [
                    'title' => 'Akun Dinonaktifkan',
                    'message' => 'Akun Anda telah dinonaktifkan oleh administrator. Silakan hubungi admin pengelola sistem.',
                    'type' => 'inactive'
                ])->onlyInput('email');
            }

            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();
            return redirect()->intended($this->redirectUrlBasedOnRole($user));
        }

        // Jika user ditemukan tapi password tidak cocok
        if ($user) {
            return back()->withErrors([
                'password' => 'Kata sandi yang Anda masukkan salah.',
            ])->with('error_modal', [
                'title' => 'Kata Sandi Salah',
                'message' => 'Kata sandi yang Anda masukkan tidak cocok dengan akun ini. Silakan periksa kembali kata sandi akun Anda dan coba lagi.',
                'type' => 'wrong_password'
            ])->onlyInput('email');
        }

        // Jika email/username tidak ditemukan
        return back()->withErrors([
            'email' => 'Email atau username tidak terdaftar pada sistem.',
        ])->with('error_modal', [
            'title' => 'Akun Tidak Ditemukan',
            'message' => 'Alamat email atau username yang Anda masukkan tidak terdaftar pada sistem kami. Silakan periksa kembali atau buat akun baru.',
            'type' => 'user_not_found'
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', 'min:3', 'confirmed'],
            'phone' => ['required', 'string', 'min:8', 'max:20', 'unique:users,phone'],
            'address' => ['nullable', 'string'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email ini sudah terdaftar. Gunakan email lain.',
            'phone.required' => 'Nomor telepon/WhatsApp wajib diisi.',
            'phone.min' => 'Nomor telepon harus memiliki minimal 8 karakter.',
            'phone.unique' => 'Nomor telepon ini sudah terdaftar. Gunakan nomor lain.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 3 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'], // Plain text sesuai instruksi
            'role' => 'customer',
            'phone' => $validated['phone'],
            'address' => $validated['address'] ?? null,
        ]);

        Auth::login($user);
        return redirect()->route('home')->with('success', 'Selamat datang! Pendaftaran akun berhasil.');
    }

    public function showVendorRegisterForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.register-vendor');
    }

    public function registerVendor(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', 'min:3', 'confirmed'],
            'phone' => ['required', 'string', 'min:8', 'max:20', 'unique:users,phone'],
            'vendor_name' => ['required', 'string', 'max:100'],
            'location_address' => ['required', 'string'],
            'google_maps_url' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
        ], [
            'name.required' => 'Nama pemilik wajib diisi.',
            'email.required' => 'Alamat email login wajib diisi.',
            'email.unique' => 'Alamat email ini sudah terdaftar. Gunakan email lain.',
            'phone.required' => 'Nomor telepon/WhatsApp wajib diisi.',
            'phone.min' => 'Nomor telepon harus memiliki minimal 8 karakter.',
            'phone.unique' => 'Nomor telepon ini sudah terdaftar. Gunakan nomor lain.',
            'vendor_name.required' => 'Nama brand vendor wajib diisi.',
            'location_address.required' => 'Alamat lokasi vendor wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 3 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'], // Plain text sesuai instruksi
            'role' => 'vendor',
            'phone' => $validated['phone'],
            'address' => $validated['location_address'],
        ]);

        Vendor::create([
            'user_id' => $user->id,
            'vendor_name' => $validated['vendor_name'],
            'owner_name' => $validated['name'],
            'location_address' => $validated['location_address'],
            'google_maps_url' => $validated['google_maps_url'] ?? null,
            'phone' => $validated['phone'],
            'description' => $validated['description'] ?? null,
        ]);

        Auth::login($user);
        return redirect()->route('vendor.dashboard')->with('success', 'Akun vendor berhasil didaftarkan!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Anda telah berhasil keluar.');
    }

    protected function redirectUrlBasedOnRole(User $user): string
    {
        return match ($user->role) {
            'admin' => route('admin.dashboard'),
            'vendor' => route('vendor.dashboard'),
            default => route('home'),
        };
    }

    protected function redirectBasedOnRole(User $user)
    {
        return redirect()->to($this->redirectUrlBasedOnRole($user));
    }
}
