<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * * Kolom 'shop_name' dan 'role' ditambahkan agar bisa diisi secara massal
     * saat proses instalasi dan update profil.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'shop_name', // Untuk menyimpan nama bengkel dari proses instalasi
        'role',      // Untuk menentukan level akses (misal: Owner/Admin)
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Helper tambahan: Mendapatkan inisial nama untuk avatar
     * (Opsional, berguna untuk UI Profil yang kita buat tadi)
     */
    public function getAvatarUrl()
    {
        return "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&background=5156be&color=fff";
    }
}