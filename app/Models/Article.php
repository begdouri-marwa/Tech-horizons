<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'theme_id', 'issue_id', 'title', 'content', 'image', 'status', 'target'];

    public function issue()
    {
        return $this->belongsTo(Issue::class);
    }

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'article_id');
    }

    public function chats()
    {
        return $this->hasMany(Chat::class, 'article_id');
    }
}
