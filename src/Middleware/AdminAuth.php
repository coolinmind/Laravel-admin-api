<?php

namespace Pl\LaravelAdminApi\Middleware;

use Pl\LaravelAdminApi\Repository\SignRepository;
use Closure;
use App\Http\Success;

class AdminAuth
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
        $sign = new SignRepository();
        $user = $sign->get_admin_auth();
        // 验证用户是否登录
        if(!$user)
        {
            return success::success([],'请登录',success::code_sign_error);
        }

        return $next($request);
    }
}
