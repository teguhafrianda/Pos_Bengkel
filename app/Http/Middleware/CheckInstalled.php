<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckInstalled
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $lockFile = storage_path('app/installed.lock');
        $isInstallPath = $request->is('install*');

        // 1. KONDISI SUDAH TERINSTAL
        if (file_exists($lockFile)) {
            // Jika user mencoba mengakses halaman install padahal sudah beres, 
            // lempar paksa ke halaman login.
            if ($isInstallPath) {
                return redirect()->route('login')->with('info', 'Aplikasi sudah terinstal. Silakan masuk.');
            }
        } 
        
        // 2. KONDISI BELUM TERINSTAL
        else {
            // Jika belum ada file lock dan user mencoba akses selain halaman install,
            // paksa mereka untuk melakukan instalasi terlebih dahulu.
            if (!$isInstallPath) {
                return redirect('/install');
            }
        }

        return $next($request);
    }
}