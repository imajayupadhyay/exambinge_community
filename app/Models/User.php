<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Follower;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Mass assignable fields.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo_path',
        'phone',
        'age',
        'city',
        'preferences',
        'role',
        'google_id',
        'avatar',
        'onboarded',
    ];

    /**
     * Hidden attributes for arrays.
     *
     * @var array<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast attributes.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'onboarded' => 'boolean',
        'age' => 'integer',
    ];

    /**
     * Relationship: User has many Tweets.
     */
    public function tweets()
    {
        return $this->hasMany(\App\Models\Tweet::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    public function replies() {
    return $this->hasMany(Reply::class);
}

public function retweets() {
    return $this->hasMany(Retweet::class);
}

// Users who follow this user
public function followers()
{
    return $this->hasMany(Follower::class, 'followed_id');
}

public function followings()
{
    return $this->hasMany(Follower::class, 'follower_id');
}
public function isFollowing($userId)
{
    return $this->followings()->where('followed_id', $userId)->exists();
}

public function isFollowedBy($userId)
{
    return $this->followers()->where('follower_id', $userId)->exists();
}


}
