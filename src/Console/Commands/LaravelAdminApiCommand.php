<?php
/**
 * Created by PhpStorm.
 * User: EricPan
 * Date: 2019/8/5
 * Time: 13:40
 */

namespace Pl\LaravelAdminApi\Console\Commands;

use Illuminate\Console\Command;

class LaravelAdminApiCommand extends Command
{
    /**
     * The console command name.
     * path 文件路径及名称 例如"Admin/Base/Test","Admin/Base/"表示路径和命名空间,"Test"表示文件名
     * type 默认1,1创建 2删除对应文件，根据path删除
     * @var string
     */
    protected $signature = 'make:admin-api {path=x} {type=1} {model=x}';

    protected $directory = '';  // 外层目录

    protected $path = '';   // 目录

    protected $fileName = ''; // 文件名

    protected $namespace = '';  // 命名空间

    protected $model_str = ''; // 用户输入的model信息

    private $app_path;  // app绝对路径

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $path = $this->argument('path');
        $type = $this->argument('type');

        $param_init = $this->param_init();
//        php artisan make:admin-api Admin/Base/Gift 1 model=App/Models/Admin/Gift
        $this->app_path = app_path();
        // 初始化目录
//        $this->init_dir();

        if ($param_init['state'] == 1) {
            // path初始化
            $this->path_init($path);

            if ($type == 1) // 创建
            {
                // 创建Controller
                $this->create_controller();
//                // 创建Request
                $this->create_requests();
//                // 创建Repository
                $this->create_repository();
                // 输出路由模板
                $this->echoRoute();
            } else if ($type == 2) {
                // 删除创建文件
                $this->delete_file();
            }
        } else {
            echo $param_init['msg'];
        }
    }


    /**
     * 参数验证
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/8/12
     * Time: 16:55
     * @return array
     */
    private function param_init()
    {
        $re = [
            'state' => 1, // 1成功 2失败
            'msg' => ''
        ];
        $msg = '';

        $path = $this->argument('path');
        $model = $this->argument('model');

        if($path == 'x') $msg = '请输入文件路径及名称';
        if($model == 'x') $msg = '请输入model路径';

        $re['msg'] = $msg;
        $re['state'] = $msg == '' ? 1 : 2;

        return $re;
    }

    /**
     * 删除创建文件
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/8/5
     * Time: 16:42
     */
    private function delete_file()
    {
        $fileNmae = $this->fileName;

        $this->directory = '/Http/Controllers/';
        $controller = $this->get_file_path().$fileNmae.'Controller.php';
        $this->file_unlink($controller,'Controller.php');

        $this->directory = '/Http/Requests/';
        $controller = $this->get_file_path().$fileNmae.'Request.php';
        $this->file_unlink($controller,'Request.php');

        $this->directory = '/Repository/';
        $controller = $this->get_file_path().$fileNmae.'Repository.php';
        $this->file_unlink($controller,'Repository.php');
    }

    /**
     * 文件删除
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/8/6
     * Time: 10:16
     * @param $path
     * @param $name
     */
    private function file_unlink($path,$name)
    {
        $fileNmae = $this->directory.$this->path.'/'.$this->fileName.$name;
        if(is_file($path)){
            unlink($path);
            $this->line('<info>文件删除成功:</info> '.$fileNmae);
        }
        else
        {
            $this->line('<info>文件删除失败:</info> '.$fileNmae);
        }
    }

    /**
     * path初始化
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/8/5
     * Time: 16:38
     * @param $path
     */
    private function path_init($path)
    {
        $model_str = $this->argument('model');
        $model_str = str_replace('model=','',$model_str);
        $model_str = str_replace('/','\\',$model_str);
        $this->model_str = $model_str;

        $this->fileName = substr($path,strrpos($path,'/')+1,strlen($path)); // 文件名称
        $this->path = substr($path,0,strrpos($path,'/'));   // 目录
        // 命名空间
        $arr = explode('/',$this->path);
        if(count($arr))
        {
            foreach ($arr as $v)
            {
                if($v) $this->namespace .= '\\'.$v;

            }
        }
    }

    /**
     * 创建Controller
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/8/5
     * Time: 16:25
     */
    private function create_controller()
    {
        $path = $this->path;
        $fileNmae = $this->fileName;
        $this->directory = '/Http/Controllers/';
        // 创建目录
        $this->dir_create($this->directory.$path);
        // 生成Controller路径
        $controller = $this->get_file_path().$fileNmae.'Controller.php';
        // 文件内容
        $contents = $this->getStub('AdminApiController');
        // 替换
        $contents = $this->contents_str_replace($contents,$fileNmae);
        // 生成Controller
        $this->create_file($controller,$contents,'Controller.php');

    }

    /**
     * 检查目录是否存在，不存在创建
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/4/24
     * Time: 11:15
     * @param $path
     */
    private function dir_create($path)
    {
        $arr = explode('/',$path);
        $str_pa = '';
        $root_apth = $this->app_path;
        foreach ($arr as $v)
        {
            clearstatcache();
            if($v)
            {
                $str_pa .= '/'.$v;
                if(!is_dir($root_apth.$str_pa))
                {
                    mkdir($root_apth.$str_pa);
                }
            }
        }
    }

    /**
     * 创建Request
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/8/5
     * Time: 16:28
     */
    private function create_requests()
    {
        $path = $this->path;
        $fileNmae = $this->fileName;
        $this->directory = '/Http/Requests/';
        // 创建目录
        $this->dir_create($this->directory.$path);
        // 生成Controller路径
        $controller = $this->get_file_path().$fileNmae.'Request.php';
        // 文件内容
        $contents = $this->getStub('AdminApiRequest');
        // 替换
        $contents = $this->contents_str_replace($contents,$fileNmae);
        // 生成Request
        $this->create_file($controller,$contents,'Request.php');

    }

    /**
     * 创建Repository
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/8/5
     * Time: 16:28
     */
    private function create_repository()
    {
        $path = $this->path;
        $fileNmae = $this->fileName;
        $this->directory = '/Repository/';
        // 创建目录
        $this->dir_create($this->directory.$path);
        // 生成Controller路径
        $controller = $this->get_file_path().$fileNmae.'Repository.php';
        // 文件内容
        $contents = $this->getStub('AdminApiRepository');
        // 替换
        $contents = $this->contents_str_replace($contents,$fileNmae);
        $contents = $this->contents_str_model_replace($contents);
        // 生成Request
        $this->create_file($controller,$contents,'Repository.php');

    }

    /**
     * 替换
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/8/5
     * Time: 16:17
     * @param $contents
     * @param $fileNmae
     * @return mixed
     */
    private function contents_str_replace($contents,$fileNmae)
    {
        $contents = str_replace('{name}',$fileNmae,$contents);
        $contents = str_replace('{namespace}',$this->namespace,$contents);

        return $contents;
    }

    /**
     * model替换
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/8/12
     * Time: 17:01
     * @param $contents
     * @return mixed
     */
    private function contents_str_model_replace($contents)
    {
        $model_str = $this->model_str;
        $model_arr = explode('\\',$model_str);

        $contents = str_replace('{model}',$model_str,$contents);
        $contents = str_replace('{model_name}',$model_arr[count($model_arr)-1],$contents);

        return $contents;
    }

    /**
     * 创建文件
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/8/5
     * Time: 16:00
     * @param $path
     * @param $contents
     */
    private function create_file($path,$contents,$fileNmae)
    {
        $fileNmae = $this->directory.$this->path.'/'.$this->fileName.$fileNmae;
        if(!is_file($path))
        {
            file_put_contents($path,$contents);
            // 输出
            $this->line('<info>文件创建成功:</info> '.$fileNmae);
        }
        else
        {
            // 输出
            $this->line('<info>文件创建失败:</info> '.$fileNmae);
        }
    }

    /**
     * 输出路由模板
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/9/24
     * Time: 15:52
     */
    private function echoRoute()
    {
        $fileNmae = $this->fileName;
        $name = strtolower(preg_replace('/(?<=[a-z])([A-Z])/', '-$1', 'fooBarB'));
        $this->line('');
        echo '路由模板:'.PHP_EOL;
        $this->line('$router->post('."'".$name."-list','".$fileNmae."Controller@".$fileNmae."List');    // list");
        $this->line('$router->post('."'".$name."-deta','".$fileNmae."Controller@".$fileNmae."Deta');    // 详情");
        $this->line('$router->post('."'".$name."-add','".$fileNmae."Controller@".$fileNmae."Add');      // 添加");
        $this->line('$router->post('."'".$name."-up','".$fileNmae."Controller@".$fileNmae."Up');        // 修改");
        $this->line('$router->post('."'".$name."-de','".$fileNmae."Controller@".$fileNmae."De');        // 删除");
    }

    /**
     * 获取文件路径
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/8/5
     * Time: 15:57
     * @return string
     */
    private function get_file_path()
    {
        return $this->app_path.$this->directory.$this->path.'/';
    }

    /**
     * Get stub contents.
     *
     * @param $name
     *
     * @return string
     */
    private function getStub($name)
    {
//        return file_get_contents(__DIR__."/stubs/$name.stub");
        return file_get_contents($this->app_path.'/Console/Commands/stubs/'.$name.'.stub');
    }
}