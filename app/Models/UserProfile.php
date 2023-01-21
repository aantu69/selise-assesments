<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Traits\UpdatableAndCreatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserProfile extends Model
{
    use HasFactory;
    use UpdatableAndCreatable;

    protected $fillable = [
        'first_name', 'last_name', 'user_id'
    ];

    protected $appends = ['photo_url', 'thumb_url'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPhotoUrlAttribute()
    {
        $subdomain = Str::of(app('currentTenant')->domain)->beforeLast('.')->beforeLast('.');
        $img = asset('images/pp.png');
        if ($this->photo) {
            $img = '/storage/photos/' . $this->photo;
        }

        return asset($img);
    }


    public function getThumbUrlAttribute()
    {

        $img = asset('images/profile.png');
        if ($this->photo) {

            $imageName = (string) Str::of($this->photo)
                ->afterLast('/');
            $imgLink = (string) Str::of($this->photo)
                ->beforeLast('/')
                ->append('/thumbs/')
                ->append($imageName);
            $img = '/storage/' . $imgLink;
        }

        return asset($img);
    }
}
