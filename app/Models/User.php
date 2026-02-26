<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
    ];

    /**
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function guardian(): HasOne
    {
        return $this->hasOne(Guardian::class);
    }

    public function teacher(): HasOne
    {
        return $this->hasOne(Teacher::class);
    }

    public function counselingSessions(): HasMany
    {
        return $this->hasMany(IndividualCounseling::class, 'counselor_id');
    }

    public function groupCounselingSessions(): HasMany
    {
        return $this->hasMany(GroupCounseling::class, 'counselor_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isGuruBk(): bool
    {
        return $this->role === UserRole::GuruBk;
    }

    public function isGuru(): bool
    {
        return $this->role === UserRole::Guru;
    }

    public function isOrangTua(): bool
    {
        return $this->role === UserRole::OrangTua;
    }

    public function isSiswa(): bool
    {
        return $this->role === UserRole::Siswa;
    }
}
