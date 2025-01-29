<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'password', 'role'];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function history()
    {
        return $this->hasMany(History::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }
}
