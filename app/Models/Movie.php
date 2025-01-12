<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Movie extends Model
{
    use HasFactory, HasUuids;

    protected $table="movie";
    protected $fillable=
    [
        'title',
        'summary',
        'poster',
        'genre_id',
        'year'
    ];

    public function genre()
    {
        return $this->belongsTo(Genre::class, 'genre_id');
    }

    public function listCasts()
    {
        return $this->belongsToMany(Cast::class, 'cast_movie', 'movie_id', 'cast_id')
                    ->withPivot('movie_id', 'cast_id')
                    ->withTimestamps();
    }

    public function listReviews()
    {
        return $this->hasMany(Review::class, 'movie_id');
    }
    
    protected $hidden = [
        'genre_id'
    ];


}
