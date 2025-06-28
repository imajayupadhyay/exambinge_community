<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tweet extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'content', 'exam_tag','image'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
   {
         return $this->hasMany(Like::class);
   }

public function isLikedBy($user)
   {
    return $this->likes->contains('user_id', $user->id);
   }

   public function replies() {
    return $this->hasMany(Reply::class);
}

public function retweets() {
    return $this->hasMany(Retweet::class);
}

public function isRetweetedBy($user) {
    return $this->retweets->contains('user_id', $user->id);
}
}

