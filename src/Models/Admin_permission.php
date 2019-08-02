<?php

namespace Pl\LaravelAdminApi\Models;

use Spatie\Permission\Models\Permission;

class Admin_permission extends Permission
{
    protected $table = 'permissions';


    /**
     * 角色表-多对多关系
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/31
     * Time: 14:50
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function role_has_permissions()
    {
        return $this->belongsToMany(Admin_role::class,'role_has_permissions','permission_id','role_id');
    }
}
