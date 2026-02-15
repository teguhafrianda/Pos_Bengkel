<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class InstallController extends Controller
{
    // ================== STEP 1 ==================
    public function step1()
    {
        return view('install.step1');
    }

    public function saveStep1(Request $request)
    {
        $request->validate([
            'app_name'  => 'required',
            'shop_name' => 'required',
        ]);

        session([
            'app_name'  => $request->app_name,
            'shop_name' => $request->shop_name,
        ]);

        return redirect('/install/database');
    }

    // ================== STEP 2 (DATABASE) ==================
    public function step2()
    {
        return view('install.step2');
    }

    public function saveStep2(Request $request)
    {
        $request->validate([
            'db_host' => 'required',
            'db_port' => 'required',
            'db_name' => 'required',
            'db_user' => 'required',
        ]);

        $host = $request->db_host;
        $port = $request->db_port;
        $dbname = $request->db_name;
        $username = $request->db_user;
        $password = $request->db_pass ?? '';

        try {
            $pdo = new \PDO("mysql:host=$host;port=$port", $username, $password);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
            
        } catch (\Exception $e) {
            return back()->withErrors([
                'db_error' => 'Koneksi ke server gagal: ' . $e->getMessage()
            ]);
        }

        $this->setEnv([
            'APP_NAME'      => '"' . session('app_name') . '"',
            'DB_CONNECTION' => 'mysql',
            'DB_HOST'        => $host,
            'DB_PORT'        => $port,
            'DB_DATABASE'   => $dbname,
            'DB_USERNAME'   => $username,
            'DB_PASSWORD'   => $password,
        ]);

        Artisan::call('config:clear');

        return redirect('/install/admin');
    }

    // ================== STEP 3 (ADMIN) ==================
    // FUNGSI INI WAJIB ADA UNTUK MENAMPILKAN FORM
    public function step3()
    {
        return view('install.step3');
    }

    public function saveStep3(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        try {
            // 1. Jalankan migrate
            Artisan::call('migrate', ['--force' => true]);

            // 2. Buat user admin dengan data dari DB & Session
            User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
                'shop_name' => session('shop_name'), 
                'role'      => 'Owner',
            ]);

            // 3. Buat file penanda installed
            if (!file_exists(storage_path('app/installed.lock'))) {
                file_put_contents(storage_path('app/installed.lock'), 'installed');
            }

            // 4. Bersihkan session instalasi
            session()->forget(['app_name', 'shop_name']);

            return redirect('/install/finish');

        } catch (\Exception $e) {
            return back()->withErrors([
                'install_error' => 'Gagal memasang database: ' . $e->getMessage()
            ]);
        }
    }

    // ================== FINISH ==================
    public function finish()
    {
        return view('install.finish');
    }

    // ================== HELPER SET ENV ==================
    private function setEnv(array $data)
    {
        $envPath = base_path('.env');
        $env = file_get_contents($envPath);

        foreach ($data as $key => $value) {
            if (preg_match("/^{$key}=.*/m", $env)) {
                $env = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $env);
            } else {
                $env .= "\n{$key}={$value}";
            }
        }

        file_put_contents($envPath, $env);
    }
}