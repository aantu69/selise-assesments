<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'author',
        'price',
        'photo'
    ];

    protected $appends = ['photo_url'];

    public function getPhotoUrlAttribute()
    {
        $img = asset('images/pp.png');
        if ($this->photo) {
            $img = '/storage/photos/' . $this->photo;
        }

        return asset($img);
    }

    public function scopeSearch($query, $search)
    {
        $search = "%$search%";
        $query->where(function ($query) use ($search) {
            $query->where('name', 'like', $search)
                ->orWhere('author', 'like', $search)
                ->orWhere('price', 'like', $search);
        });
    }
}
