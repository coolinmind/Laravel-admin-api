<?php

namespace Pl\LaravelAdminApi\Middleware;

use Pl\LaravelAdminApi\Models\Admin_permission;
use Pl\LaravelAdminApi\Repository\SignRepository;
use Closure;
use App\Http\Success;

class RolePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $url = $request->route()->uri;          // 当前url
        if($url == '') return Success::success([],Success::get_msg(Success::info),Success::info);

        // 获取用户信息
        $sign = new SignRepository();
        $user = $sign->get_admin_auth();
        $re = false;

        /*
         * 验证权限
         */
        // 查询权限
        $admin_permission_data = Admin_permission::where('route_name',$url)->first();
        if($admin_permission_data)
        {
            // 所有权限（直接的、继承的）
            $getAllPermissions = $user->getAllPermissions()->pluck('id')->toArray();
            if(in_array($admin_permission_data['id'],$getAllPermissions))
            {
                $re = true;
            }

        }
        if($re === false)
        {
            /**
             * 验证角色
             * 权限验证为false
             */
            $re = $user->hasRole(config('admin-api.root_role_name'));
        }

        if($re)
        {
            // 继续执行
            return $next($request);
        }
        else
        {
            // 返回错误信息
            return Success::success([],Success::get_msg(Success::info),Success::info);

        }
    }
}
