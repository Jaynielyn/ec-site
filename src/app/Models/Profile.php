<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'user_name', 'postcode', 'address', 'building', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
