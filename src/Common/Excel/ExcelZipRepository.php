<?php
/**
 * Created by PhpStorm.
 * User: EricPan
 * Date: 2020/2/28
 * Time: 15:37
 */

namespace Pl\LaravelAdminApi\Common\Excel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Pl\LaravelAdminApi\Exceptions\Common\ExcelException;

class ExcelZipRepository
{
    private $query;      // 查询query
    private $name;       // 压缩文件名称
    private $path;       // 存储目录
    private $excel_name;        // excel名称
    private $count;      // 总条数
    private $paginate;   // 每个sheet数量
    private $offset = 0; // 偏移量
    private $ex = 'xlsx'; // 后缀 xlsx csv

    /**
     * ExcelZipRepository constructor.
     * @param Model $query 模型query
     * @param string $name excel名称
     * @param string $path /目录名称
     * @param int $paginate 每页数量
     */
    public function __construct($query, $name, $path = '', $paginate=10000)
    {
        $this->path = config('admin-api.common.excel').$path;
        $this->query = $query;
        $query_count = $query;
        $this->count = $query?$query_count->count():0;
        $this->excel_name = $name;
        $this->paginate = $paginate;

        // 参数验证
        $this->validate($path);

    }

    /**
     *
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2020/2/28
     * Time: 16:56
     */
    private function validate($path)
    {
        // 目录名称格式
        if($path)
        {
            if(substr($path,0,1) != '/' || substr($path,-1,1) != '/')
            {
                throw new ExcelException('目录名称以"/"开始,以"/"结束');
            }
        }
        // 路径是否存在
        if(!is_dir($this->path))
        {
            throw new ExcelException($this->path.'目录不存在,请先创建');
        }

        // 目录权限修改
        if(!is_writable($this->path))
        {
            throw new ExcelException($this->path.'目录没有可写的权限');
        }
    }

    /**
     * excel单元格等号处理
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/11/3
     * Time: 20:11
     * @param $name
     * @return string
     */
    static public function ExcelInitEs($str)
    {
        if(0 === strpos($str,'='))
        {
            return "'".$str;
        }
        return $str;
    }

    /**
     * 分页查询数据
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/11/1
     * Time: 13:34
     * @param Request $request 支持从第多少页开始导出
     * @param $callback
     * @return array
     */
    public function excel_init(Request $request,$callback)
    {
        $page = $request->input('page',1);
        $page = $page-1;

        $excel_count = $this->count;
        $paginate = $this->paginate;
        $offset = $page*$excel_count;
        $count_page = ceil($excel_count/$paginate);

        $queryC = $this->query;
        // 验证是否有数据
        $data = $queryC->offset(($page*$paginate+$offset))->limit($paginate)->count();
        if($data == 0)
        {
            return ['url'=>'','count'=>$this->count,'count_page'=>$count_page,'msg'=>'无数据'];
        }


        $this->offset = $offset;    // 偏移量
        $this->excel_name = $this->excel_name.$page;
        // 数据导出
        $this->excel($callback);

        return ['url'=>$this->get_file_url(),'count'=>$this->count,'msg'=>'','count_page'=>$count_page];
    }

    /**
     * 数组补空，避免导出报错
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/11/1
     * Time: 13:51
     * @param array $data 数组
     * @return mixed
     */
    public function addNull($data)
    {
        $numMax = 0;
        if(count($data))
        {
            foreach ($data as $v)
            {
                $num = count($v);
                if($num>$numMax) $numMax = $num;
            }

            foreach ($data as $k=>$v)
            {
                for($i=0;$i<$numMax;$i++)
                {
                    if(!isset($v[$i])) $data[$k][$i] = '';
                }
            }
        }

        return $data;
    }

    /**
     * 数据查询
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/9/9
     * Time: 13:29
     * @param $callback
     */
    private function excel_sheet($callback,$excel)
    {
        $paginate = $this->paginate;
        $count = $this->count;
        $offset = $this->offset;
        for($i = 0; $i < ceil($count/$paginate);$i++)
        {
            $data = $this->query->offset(($i*$paginate+$offset))->limit($paginate)->get();
            if(count($data))
            {
                $re = $callback($data);
                // 不是第一页的情况删除表头
                if($i != 0)
                {
                    unset($re[0]);
                }
                $excel->data($re);
            }
            if($i == 100)
            {
                return $excel;
            }
        }
        return $excel;
    }

    /**
     * 输出导出
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/9/9
     * Time: 13:32
     * @param $callback
     */
    private function excel($callback)
    {
        ini_set("memory_limit","1024M");
        $config   = ['path' => $this->path];
        $excel  = new \Vtiful\Kernel\Excel($config);
        $excel->fileName($this->excel_name.'.'.$this->ex, 'sheet1');
        $excel = $this->excel_sheet($callback,$excel);
        $filePath = $excel->output();
    }

    /**
     * 获取excel文件路径
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/9/20
     * Time: 17:07
     * @return string
     */
    private function get_file_url()
    {
        return config('app.url').$this->path.$this->excel_name.'.'.$this->ex;
    }
}