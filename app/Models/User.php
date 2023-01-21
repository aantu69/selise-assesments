<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Kirschbaum\PowerJoins\PowerJoins;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Yadahan\AuthenticationLog\AuthenticationLogable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, AuthenticationLogable, PowerJoins;

    protected $guard = 'web';

    protected $fillable = [
        'name',
        'email',
        'password',
        'approved',
        'active',
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
        return $this->hasOne(UserProfile::class);
    }

    public function scopeSearch($query, $search)
    {
        $search = "%$search%";
        $query->where(function ($query) use ($search) {
            $query->where('name', 'like', $search)
                ->orWhere('email', 'like', $search)
                ->orWhereHas('profile', function ($query) use ($search) {
                    $query->where('first_name', 'like', $search)
                        ->orWhere('last_name', 'like', $search);
                });
        });
    }

    public function scopeSearchSuperAdmin($query, $search)
    {
        $search = "%$search%";
        $query->where(function ($query) use ($search) {
            $query->where('name', 'like', $search)
                ->orWhere('email', 'like', $search);
        });
    }
}
