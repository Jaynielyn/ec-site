<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'img_url', 'name', 'description', 'price', 'user_id', 'category', 'condition', 'color'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function sold()
    {
        return $this->hasMany('App\Models\Sold');
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
