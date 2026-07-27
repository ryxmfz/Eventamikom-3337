<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// ✨ Tambahkan kolom Multi-Tenant (organization_name, role, organizer_status) ke atribut Fillable
#[Fillable(['name', 'email', 'password', 'google_id', 'is_admin', 'organization_name', 'role', 'organizer_status'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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

    // ✨ Relasi Tenant ke Event (Satu Organizer bisa bikin banyak Event)
    public function events()
    {
        return $this->hasMany(Event::class, 'user_id');
    }
}
