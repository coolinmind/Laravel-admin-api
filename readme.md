<p align="center">
<a href="http://laravel-admin-api.plasr.cn/">
<img src="http://test.plasr.cn/img/laravel-admin-api-logo.jpg" width="200" alt="laravel-admin-api">
</a>
<p align="center"><code>laravel-admin-api</code>是后台基础框架api集成,(管理员、角色、权限、操作日志)。都是api接口，没有前端页面，前端页面自定义</p>

<p align="center">
<a href="https://www.eolinker.com/#/share/index?shareCode=KUgsLJ">API文档</a> | 
<a href="http://laravel-admin-api.plasr.cn/">使用文档</a>
</p>


# 安装配置管理

## 安装

### 安装
```
composer require pl/laravel-admin-api
```

### 服务添加

> `config/app.php`

```
'providers' => [
    ......
    Pl\LaravelAdminApi\LaravelAdminApiProvider::class,
]
```

### 发布文件
```
php artisan vendor:publish --provider="Pl\LaravelAdminApi\LaravelAdminApiProvider"
```

### 迁移数据库
```
php artisan migrate
```

### 数据填充
```
php artisan db:seed --class=LaravelAdminApiSeeder
```

## 配置

### 登录验证
> `config/auth.php`
```
'guards' => [
    'admin.api' => [
        'driver' => 'session',
        'provider' => 'admin_api_users',
    ],
    ....
],

'providers' => [
    'admin_api_users' => [
        'driver' => 'eloquent',
        'model' => \Pl\LaravelAdminApi\Models\Admin_user::class,
    ],
    ....
],
```

### `POST`提交验证取消
> `VerifyCsrfToken.php`
```
protected $except = [
    'gjgase/*',
];
```

### 参数验证拦截
>`Handler.php`
```
use Illuminate\Validation\ValidationException;
use Pl\LaravelAdminApi\success;

public function render($request, Exception $exception)
{
    // 拦截参数验证错误
    if($exception instanceof ValidationException)
    {
        $error = array_collapse($exception->errors());
        success::success($error,$error[0],success::info);

    }
    ......
    return parent::render($request, $exception);
}
```