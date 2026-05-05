<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Member extends Model
{
    protected $fillable = [
        'user_id',
        'member_code',
        'name',
        'email',
        'address',
        'phone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
