<?php

namespace Pl\LaravelAdminApi;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Arr;

class LaravelAdminApiProvider extends ServiceProvider
{

    /**
     *
     *  应用程序的路由中间件
     *
     * @var array
     */
    protected $routeMiddleware = [
        'admin.api.auth'              => Middleware\AdminAuth::class,
        'admin.api.operation.log'     => Middleware\OperationLog::class,
        'admin.api.role.permission'   => Middleware\RolePermission::class,
        'admin.api.verify.xss'   => Middleware\VerifyXss::class,
    ];

    /**
     * 应用程序的路由中间件组
     *
     * @var array
     */
    protected $middlewareGroups = [
        'admin.api' => [
            'admin.api.auth',
            'admin.api.operation.log',
            'admin.api.verify.xss',
        ],
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // 配置文件迁移
        $this->publishes([
            __DIR__.'/config/admin-api.php' => config_path('admin-api.php'),
            __DIR__.'/database/migrations' => database_path('migrations'),
        ]);

        // 路由加载
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        // 迁移文件
//        $this->loadMigrationsFrom(__DIR__.'/migrations');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        // 注册路由中间件
        $this->registerRouteMiddleware();
    }

    /**
     * 注册路由中间件
     *
     * @return void
     */
    protected function registerRouteMiddleware()
    {
        // register route middleware.
        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->aliasMiddleware($key, $middleware);
        }

        // register middleware group.
        foreach ($this->middlewareGroups as $key => $middleware) {
            app('router')->middlewareGroup($key, $middleware);
        }
    }
}
