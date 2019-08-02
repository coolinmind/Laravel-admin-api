<?php

namespace Pl\LaravelAdminApi\Middleware;

use Closure;

class VerifyXss
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
        $data = $request->all();

        if(count($data))
        {
            foreach ($data as $k=>$v)
            {
                // 重新设置值
                $request->request->set($k,clean($v));
            }
        }

        return $next($request);
    }
}
