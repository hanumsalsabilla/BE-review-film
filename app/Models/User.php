<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Carbon\Carbon;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    public static function boot(){
        parent::boot();

        static::created(function($model){
            $model->generateOtpCode();
        });
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
        {
        return [];
    }

    public function generateOtpCode(){
        do {
            $randomNumber = mt_rand(100000, 999999);
            $checkOtpCode = OtpCode::where('otp', $randomNumber)->first();
        } while ($checkOtpCode);

    $now = Carbon::now();

    $otp_code = OtpCode::updateOrCreate(
        ['user_id' => $this->id],
        [
            'otp' => $randomNumber,
            'valid_until' => $now->addMinutes(5)
        ]
        );
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id'
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

    public function Profile(){
        return $this->hasOne(Profile::class, 'user_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function historyReviews()
    {
        return $this->belongsToMany(Movie::class, 'reviews', 'user_id', 'movie_id');
    }

    

    public function otpcode(){
        return $this->hasOne(OtpCode::class, 'user_id');
    }
}
