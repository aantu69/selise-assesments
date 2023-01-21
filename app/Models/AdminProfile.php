<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Traits\UpdatableAndCreatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdminProfile extends Model
{
    use HasFactory;
    use UpdatableAndCreatable;

    protected $fillable = [
        'name', 'phone', 'designation', 'photo', 'admin_id'
    ];

    protected $appends = ['photo_url', 'thumb_url'];

    public function user()
    {
        return $this->belongsTo(Admin::class);
    }

    public function getPhotoUrlAttribute()
    {

        $img = asset('images/pp.png');
        if ($this->photo) {
            $img = '/storage/' . $this->photo;
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
