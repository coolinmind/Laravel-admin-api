<?php

namespace Pl\LaravelAdminApi\Models;


use Spatie\Permission\Models\Role;

class Admin_role extends Role
{
    protected $table = 'roles';

    /**
     * 权限表-多对多关系
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/31
     * Time: 14:50
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function role_has_permissions()
    {
        return $this->belongsToMany(Admin_permission::class,'role_has_permissions','role_id','permission_id');
    }
}
