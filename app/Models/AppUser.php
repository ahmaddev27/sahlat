<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class AppUser extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'app_users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'phone',
        'profile_status',
        'number_id',
        'gender',
        'location',
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
        'email_verified_at' => 'date',
    ];


    public function getAvatar()
    {
        if ($this->avatar) {
            return url('storage/' . $this->avatar);
        } else {
            return url('blank.png');
        }
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'user_id');
    }

    public function violation()
    {
        return $this->hasMany(Violation::class, 'user_id');
    }

    public function houseKeeperOrder()
    {
        return $this->hasMany(HouseKeeperOrder::class, 'user_id');
    }

    public function houseKeeperHourlyOrder()
    {
        return $this->hasMany(HouseKeeperHourlyOrder::class, 'user_id');
    }
    public function assuranceOrder()
    {
        return $this->hasMany(AssuranceOrder::class, 'user_id');
    }

    public function fcm_tokens()
    {
        return $this->hasMany(FcmToken::class, 'user_id');
    }


    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }


    public function routeNotificationForFcm($notification = null,)
    {
        return $this->fcm_tokens()->pluck('token')->toArray();
    }




}
