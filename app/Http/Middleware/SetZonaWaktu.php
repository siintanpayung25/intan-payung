<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SetZonaWaktu
{
    public function handle(Request $request, Closure $next)
    {
        // Set default timezone untuk aplikasi menggunakan config
        config(['app.timezone' => 'Asia/Jakarta']);

        // Jika perlu, kamu bisa set waktu sekarang dengan timezone yang diubah
        Carbon::setLocale(config('app.locale'));

        // Mengatur zona waktu aplikasi
        date_default_timezone_set('Asia/Jakarta');

        return $next($request);  // Lanjutkan ke request berikutnya
    }
}
