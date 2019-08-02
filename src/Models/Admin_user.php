<?php

namespace Pl\LaravelAdminApi\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin_user extends Authenticatable
{
    use HasRoles;

    protected $guard_name = 'web'; // or whatever guard you want to use

    protected $table = 'admin_api_users';

    /**
     * 头像字段处理
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/30
     * Time: 13:46
     * @param $v
     * @return string
     */
//    public function getAvatarAttribute($v)
//    {
//        return config('app.url').'storage/'.$v;
//    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
//    protected $hidden = [
//        'password', 'remember_token',
//    ];
}
