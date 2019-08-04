<?php
/**
 * Created by PhpStorm.
 * User: EricPan
 * Date: 2019/7/31
 * Time: 14:03
 */

namespace Pl\LaravelAdminApi\Repository;


use Pl\LaravelAdminApi\Models\Admin_permission;
use Pl\LaravelAdminApi\Models\Role_has_permission;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionRepository
{

    /**
     * 权限列表
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/31
     * Time: 14:30
     * @param Request $request
     * @return array
     */
    public function permission_list(Request $request)
    {
        $data = [];
        $paginate = $request->input('paginate',10);
        $name = $request->input('name','');
        $route_name = $request->input('route_name','');

        $query = Admin_permission::query();

        if($name)
        {
            $query->where('name','like','%'.$name.'%');
        }
        if($route_name)
        {
            $query->where('route_name','like','%'.$route_name.'%');
        }

        $query->orderBy('id','DESC');

        $query->select('id','name','created_at','route_name');

        $data = $query->paginate($paginate);

        return $data;
    }

    /**
     * 权限详情
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/31
     * Time: 14:29
     * @param Request $request
     * @return mixed
     */
    public function permission_deta(Request $request)
    {
        $id = $request->input('id','');
        $data = Admin_permission::where('id',$id)->select('id','name','created_at','route_name')
            ->with('role_has_permissions')->first();
        return $data;
    }

    /**
     * 权限添加
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/31
     * Time: 14:09
     * @param Request $request
     * @return bool
     */
    public function permission_add(Request $request)
    {
        $at = $request->all();
        $role = $request->input('role',[]);
        $re = false;

        $model = new Permission();

        $model->name = $at['name'];
        $model->route_name = $at['route_name'];

        try {
            // 添加角色
            $model->save();
            // 同步角色
            $this->role_has_permissions($model['id'],$role);
            $re = true;
        } catch (\Exception $exception) {
            $re = '权限名称重复';
        }

        return $re;
    }

    /**
     * 同步角色
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/31
     * Time: 14:59
     * @param $id
     * @param $permissions
     */
    public function role_has_permissions($id,$role)
    {
        $permission = Admin_permission::where('id',$id)->first();
        $permission->role_has_permissions()->sync($role);
    }

    /**
     * 权限修改
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/31
     * Time: 14:19
     * @param Request $request
     * @return bool
     */
    public function permission_up(Request $request)
    {
        $at = $request->all();
        $role = $request->input('role',[]);
        $re = false;

        try {
            Permission::where('id',$at['id'])->update([
                'name' => $at['name'],
                'route_name' => $at['route_name']
            ]);
            // 同步角色
            $this->role_has_permissions($at['id'],$role);
            $re = true;
        } catch (\Exception $exception) {
            $re = '权限名称重复';
        }

        return $re;
    }

    /**
     * 权限删除
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/31
     * Time: 14:24
     * @param Request $request
     */
    public function permission_de(Request $request)
    {
        $id = $request->input('id','');
        Permission::where('id',$id)->delete();
        // 删除权限关联角色
        Role_has_permission::where('permission_id',$id)->delete();
    }

}