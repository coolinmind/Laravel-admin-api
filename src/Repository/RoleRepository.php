<?php
/**
 * Created by PhpStorm.
 * User: EricPan
 * Date: 2019/7/31
 * Time: 11:45
 */

namespace Pl\LaravelAdminApi\Repository;


use Pl\LaravelAdminApi\Models\Admin_role;
use Pl\LaravelAdminApi\Models\Role_has_permission;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleRepository
{

    /**
     * 角色list
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/31
     * Time: 13:23
     * @param Request $request
     * @return array
     */
    public function role_list(Request $request)
    {
        $data = [];
        $paginate = $request->input('paginate',10);
        $name = $request->input('name','');

        $query = Admin_role::query();

        if($name)
        {
            $query->where('name','like','%'.$name.'%');
        }

        $query->orderBy('id','DESC');

        $data = $query->select('id','name','created_at')->paginate($paginate);

        return $data;
    }

    /**
     * 角色详情
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/31
     * Time: 13:35
     * @param Request $request
     * @return mixed
     */
    public function role_detail(Request $request)
    {
        $id = $request->input('id','');
        $data = Admin_role::where('id',$id)->select('id','name','created_at')
            ->with('role_has_permissions')->first();
        return $data;
    }

    /**
     * 管理员添加
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/31
     * Time: 11:46
     * @return bool
     */
    public function role_add(Request $request)
    {
        $re = false;
        $at = $request->all();
        $permissions = $request->input('permissions',[]);

        $model = new Role();

        $model->name = $at['name'];

        try {
            $model->save();
            // 同步权限
            $this->role_has_permissions($model['id'],$permissions);
            $re = true;
        } catch (\Exception $exception) {
            $re = '异常';
        }

        return $re;
    }

    /**
     * 同步权限
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/31
     * Time: 14:59
     * @param $id
     * @param $permissions
     */
    public function role_has_permissions($id,$permissions)
    {
        $role = Admin_role::where('id',$id)->first();
        $role->role_has_permissions()->sync($permissions);
    }

    /**
     * 角色修改
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/31
     * Time: 13:12
     * @param Request $request
     * @return bool
     */
    public function role_up(Request $request)
    {
        $re = false;
        $at = $request->all();
        $permissions = $request->input('permissions',[]);

        $model = new Role();


        try {
            $model->where('id',$at['id'])->update([
                'name' => $at['name']
            ]);
            // 同步权限
            $this->role_has_permissions($at['id'],$permissions);
            $re = true;
        } catch (\Exception $exception) {
            $re = '角色修改异常';
        }
        return $re;
    }

    /**
     * 角色删除
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/31
     * Time: 13:19
     * @param Request $request
     */
    public function role_de(Request $request)
    {
        $id = $request->input('id','');
        Role::where('id',$id)->delete();
        // 角色关联权限删除
        Role_has_permission::where('role_id',$id)->delete();
    }
}