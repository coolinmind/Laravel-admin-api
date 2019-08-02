### 安装
```

```

### 发布文件
```$xslt
php artisan vendor:publish --provider="Pl\LaravelAdminApi\LaravelAdminApiProvider"
```

### 迁移数据库
```$xslt
php artisan migrate
```

### 配置
#### 1.登录验证
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

#### 2.`POST`提交验证取消
> `VerifyCsrfToken.php`
```
protected $except = [
    'gjgase/*',
];
```

#### 3.参数验证拦截
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