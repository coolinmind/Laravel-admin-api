<?php

namespace Pl\LaravelAdminApi\Middleware;

use Pl\LaravelAdminApi\Repository\OperationLogRepository;
use Pl\LaravelAdminApi\Repository\SignRepository;
use Closure;

class OperationLog
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
        $data = $request->all();                // 参数
        $methods = $request->route()->methods;  // 请求类型
        $url = $request->route()->uri;          // 当前url
        // 获取用户信息
        $sign = new SignRepository();
        $user = $sign->get_admin_auth();

        // 保存
        $operationLogRepository = new OperationLogRepository();
        $operationLogRepository->add($user['id'],$url,$methods[0],$data);

        return $next($request);
    }
}
