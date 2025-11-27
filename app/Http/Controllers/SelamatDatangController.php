<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SelamatDatangController extends Controller
{
    public function index()
    {
        return view('selamatdatang');
    }

    public function loginProses(Request $request)
    {
        // Mendefinisikan pesan validasi secara custom
        $messages = [
            'username.required' => 'Username harus diisi.',
            'password.required' => 'Password harus diisi.',
        ];

        // Validasi input dengan aturan tambahan
        $credentials = $request->validate([
            'username' => 'required',  // Validasi required untuk username
            'password' => 'required',  // Validasi required untuk password
        ], $messages);  // Menggunakan pesan validasi yang sudah kita tentukan

        // Proses autentikasi jika validasi berhasil
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            // Regenerasi session agar lebih aman
            $request->session()->regenerate();

            // Menyimpan user_id ke session secara manual
            session(['user_id' => Auth::id()]);

            // Redirect ke halaman home setelah login berhasil
            return redirect()->route('home');
        }

        // Jika login gagal, kirimkan error login_error
        return back()->withErrors([
            'login_error' => 'Username atau password salah.',
        ])->withInput($request->except('password'));  // Pastikan password tidak ikut terinput kembali
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
