<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Kirschbaum\PowerJoins\PowerJoins;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Yadahan\AuthenticationLog\AuthenticationLogable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, AuthenticationLogable, PowerJoins;

    protected $guard = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'passwords',
        'approved',
        'active',
        'secret_key',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile()
    {
        return $this->hasOne(AdminProfile::class);
    }

    public function scopeSearch($query, $search)
    {
        $search = "%$search%";
        $query->where(function ($query) use ($search) {
            $query->where('name', 'like', $search)
                ->orWhere('email', 'like', $search);
        });
    }
}
