<?php

namespace StallionExpress\AuthUtility\Models;

use Illuminate\Database\Eloquent\Model;

class UserScope extends Model
{
    protected $table = 'user_scopes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, array>
     */
    protected $fillable = [
        'user_id',
        'scopes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'scopes' => 'array',
    ];
}
