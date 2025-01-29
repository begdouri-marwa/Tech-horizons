<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'published_at', 'status'];
    protected $casts = [
	    'published_at' => 'datetime',
	];

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
