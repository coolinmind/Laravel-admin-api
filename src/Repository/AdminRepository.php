<?php
/**
 * Created by PhpStorm.
 * User: EricPan
 * Date: 2019/7/30
 * Time: 10:34
 */

namespace Pl\LaravelAdminApi\Repository;

use Pl\LaravelAdminApi\Models\Admin_user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminRepository
{

    /**
     * 管理员list
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/30
     * Time: 13:42
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function admin_list(Request $request)
    {
        $paginate = $request->input('paginate',10);
        $username = $request->input('username','');
        $name = $request->input('name','');
        $query = Admin_user::query();

        if($username)
        {
            $query->where('username','like','%'.$username.'%');
        }
        if($name)
        {
            $query->where('name','like','%'.$name.'%');
        }

        $data = $query->select('id','username','name','avatar','created_at','updated_at')->paginate($paginate);

        return $data;
    }

    /**
     * 管理员详情
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/31
     * Time: 13:41
     * @param Request $request
     * @return mixed
     */
    public function admin_detail(Request $request)
    {
        $id = $request->input('id','');
        $model = new Admin_user();
        $data = $model->where('id',$id)->first();
        $data->getAllPermissions();
        return $data;
    }

    /**
     * 管理员添加
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/30
     * Time: 10:35
     * @param Request $request
     * @return array
     */
    public function admin_add(Request $request)
    {
        $re = false;
        $at = $request->all();
        $avatar = $at['avatar'];
        $role = $request->input('role',[]);
        $permission = $request->input('permission',[]);
        $avatar = str_replace(config('app.url'), '', $avatar);
        // 保存用户
        $model = new Admin_user();
        $model->username = $at['username'];
        $model->password = bcrypt($at['password']);
        $model->name = $at['name'];
        $model->avatar = $avatar;

        try {
            // 手动开始事务
            DB::beginTransaction();
            $model->save();
            $user_data = Admin_user::where('id',$model['id'])->first();
            // 同步角色
            $this->admin_has_role_permission($user_data,$role,$permission);
            // 提交事务
            DB::commit();
            $re = true;
        } catch (\Exception $exception) {
            // 回滚事务
            DB::rollBack();
            $re = '账号重复';
        }
        return $re;
    }

    /**
     * 同步管理员-角色/权限
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/8/1
     * Time: 10:01
     * @param $user
     * @param $role
     * @param $permission
     */
    public function admin_has_role_permission($user,$role,$permission)
    {
        // 同步角色
        $user->syncRoles($role);
        // 同步权限
        $user->syncPermissions($permission);

    }

    /**
     * 管理员修改
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/30
     * Time: 13:30
     * @param Request $request
     * @return bool
     */
    public function admin_up(Request $request)
    {
        $re = false;
        $at = $request->all();
        $avatar = $at['avatar'];
        $avatar = str_replace(config('app.url'), '', $avatar);
        $id = $at['id'];
        $password = $at['password'];
        $role = $request->input('role',[]);
        $permission = $request->input('permission',[]);

        $model = new Admin_user();

        $update = [
            'username' => $at['username'],
            'name' => $at['name'],
            'avatar' => $avatar
        ];

        // 判断密码是否修改
        $user_data = $model->where('id',$id)->first();
        if($user_data && $user_data['password'] != $password)
        {
            $update['password'] = bcrypt($password);
        }
        try {
            // 手动开始事务
            DB::beginTransaction();
            $model->where('id',$id)->update($update);
            // 同步角色
            $this->admin_has_role_permission($user_data,$role,$permission);
            // 提交事务
            DB::commit();
            $re = true;
        } catch (\Exception $exception) {
            DB::rollBack();
            $re = '账号重复';
        }
        return $re;
    }

    /**
     * 管理员删除
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/30
     * Time: 13:38
     * @param Request $request
     */
    public function admin_de(Request $request)
    {
        $id = $request->input('id','');
        $model = new Admin_user();
        // 查询用户信息
        $user_data = $model->where('id',$id)->first();
        if($user_data)
        {
            // 删除用户
            $model->where('id',$id)->delete();
            // 同步角色,清空
            $this->admin_has_role_permission($user_data,[],[]);
        }
    }

    /**
     * 根据账号查询信息
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/30
     * Time: 14:40
     * @param $username
     * @return mixed
     */
    public function username_get($username)
    {
        $data = Admin_user::where('username',$username)->first();
        return $data;
    }
}