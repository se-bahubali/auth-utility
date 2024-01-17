<?php

namespace StallionExpress\AuthUtility\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Role\App\Models\Role;
use Modules\ThreePlStaff\App\Models\ThreePlStaff;
use StallionExpress\AuthUtility\Enums\UserTypeEnum;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ulid',
        'email',
        'password',
        'user_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'updated_at',
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'user_type' => UserTypeEnum::class,
    ];

    /*
    public function resolveRouteBinding($value, $field = null)
    {
    return $this->where('ulid', $value)->firstOrFail();
    }
     */

    public static function getUserDetails(int $userType, int $userId)
    {
        $module = 'Threepl';
        $modalName = 'ThreePL';
        switch ($userType) {
            case UserTypeEnum::THREE_PL_CUSTOMER->value:
                $module = 'ThreePlCustomer';
                $modalName = 'ThreePlCustomer';
                break;
        }

        return app('Modules\\' . $module . "\App\Models\\" . $modalName)->where('user_id', $userId)->first();
    }

    public function threePlStaff()
    {
        return $this->hasOne(ThreePlStaff::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    public function scopes()
    {
        return $this->hasMany(UserScope::class);
    }
}
