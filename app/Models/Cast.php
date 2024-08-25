<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Cast extends Model
{
    use HasFactory, HasUuids;

    protected $table="casts";
    protected $fillable=
    [
        'name',
        'age',
        'bio'
    ];

    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'cast_movie', 'cast_id', 'movie_id')
                    ->withPivot('movie_id', 'cast_id')
                    ->withTimestamps();
    }

}
