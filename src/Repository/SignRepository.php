<?php
/**
 * Created by PhpStorm.
 * User: EricPan
 * Date: 2019/7/30
 * Time: 14:41
 */

namespace Pl\LaravelAdminApi\Repository;


use Pl\LaravelAdminApi\Models\Admin_user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Success;

class SignRepository
{

    /**
     * 登录
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/30
     * Time: 14:44
     * @param Request $request
     * @return int
     */
    public function sign(Request $request)
    {
        $username = $request->input('username','');
        $password = $request->input('password','');

        $admin_user = new AdminRepository();

        // 查询管理员信息
        $data = $admin_user->username_get($username);

        if(Auth::guard('admin_api')->attempt(['username'=>$username,'password'=>$password],true))
        {
            return ['data'=>$data,'code'=>Success::code_success,'msg'=>'成功'];
        }
        return ['data'=>'','code'=>Success::info,'msg' => '登录失败'];
    }

    /**
     * 退出登录
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/30
     * Time: 15:28
     * @return mixed
     */
    public function out()
    {
        if($this->get_admin_auth())
        {
            Auth::guard('admin_api')->logout();
        }
    }

    /**
     * 获取登录用户信息
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/30
     * Time: 15:35
     * @return mixed
     */
    public function get_admin_auth()
    {
        $u_id = config('admin-api.simulation.user');
        $user = '';
        if($u_id === '')
        {
            $user = Auth::guard('admin_api')->user();
        }
        if($u_id && $user == '')
        {
            $user = Admin_user::where('id',$u_id)->first();
        }
        return $user;
    }
}