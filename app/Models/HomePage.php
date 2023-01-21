<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomePage extends Model
{
    use HasFactory;

    protected $fillable = [
        'left_part',
        'right_part',
    ];

    public function scopeSearch($query, $search)
    {
        $search = "%$search%";
        $query->where(function ($query) use ($search) {
            $query->where('left_part', 'like', $search)
                ->orWhere('right_part', 'like', $search);
        });
    }
}
