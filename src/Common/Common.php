<?php
/**
 * Created by PhpStorm.
 * User: EricPan
 * Date: 2020/2/28
 * Time: 15:04
 */

namespace Pl\LaravelAdminApi\Common;


use Illuminate\Database\Eloquent\Model;

class Common
{
    /**
     * 根据手机号登录方法
     * 适用前需要安装JWT
     * 适用于小程序、微信授权登录等第三方授权登录，用户免密码登录
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/17
     * Time: 15:17
     * @param Model $model 用户模型
     * @param string $mobile mobile字段值
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public static function loginJwt($model, $mobile)
    {
        $token = '';
        $user = $model->where('mobile',$mobile)->first();
        if($user)
        {
            $password = \Ramsey\Uuid\Uuid::uuid1();
            // 更改用户密码
            \App\User::where('id',$user->id)->update([
                'password' => bcrypt($password)
            ]);
            // token生成
            $token = \Illuminate\Support\Facades\Auth::guard('api')->attempt([
                'mobile' => $mobile,
                'password' => $password
            ]);
            return 'Bearer '.$token;
        }
        return '';
    }

    /**
     * 二维数组指定key排序
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/29
     * Time: 14:29
     * @param array $data 排序的数组
     * @param string $key 要排序的key
     * @return mixed
     */
    public static function dataOrder($data, $key)
    {
        if(count($data))
        {
            //根据字段last_name对数组$data进行降序排列
            $last_names = array_column($data,$key);
            array_multisort($last_names,SORT_ASC,$data);
        }
        return $data;
    }

    /**
     * 二维数组指定key统计
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/8/16
     * Time: 15:56
     * @param array $arr 排序的数组
     * @param string $key 要排序的key
     * @return int
     */
    public static function sumArrKey($arr, $key)
    {
        $sum = 0;
        if(count($arr))
        {
            foreach ($arr as $v)
            {
                $sum+=$v[$key];
            }
        }
        return $sum;
    }

    /**
     * 获取二维数组指定key，组合为一维索引数组
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/11/12
     * Time: 10:11
     * @param array $arr 排序的数组
     * @param string $key 要排序的key
     * @return array
     */
    public static function getArrKey($data,$key)
    {
        $re = [];

        if(count($data))
        {
            foreach ($data as $v)
            {
                $re[] = $v[$key];
            }
        }
        return $re;
    }

    /**
     * 删除指定目录下的文件，不删除目录文件夹
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/9/19
     * Time: 16:26
     * @param string $path 路径
     */
    public static function delDirFile($path){
        //如果是目录则继续
        if(is_dir($path)){
            //扫描一个文件夹内的所有文件夹和文件并返回数组
            $p = scandir($path);
            foreach($p as $val){
                //排除目录中的.和..
                if($val !="." && $val !=".."){
                    //如果是目录则递归子目录，继续操作
                    if(is_dir($path.$val)){
                        //子目录中操作删除文件夹和文件
                        deldir($path.$val.'/');
                        //目录清空后删除空文件夹
                        @rmdir($path.$val.'/');
                    }else{
                        //如果是文件直接删除
                        unlink($path.$val);
                    }
                }
            }
        }
    }


    /**
     * 判断数组指定下标是否存在,存在则返回数据
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/9/27
     * Time: 16:24
     * @param array $arr 数组
     * @param string $key key
     * @param string 不存在返回值
     * @return string
     */
    public static function arrIsKey($arr, $key, $type = '')
    {
        return isset($arr[$key])?$arr[$key]:$type;
    }


    /**
     * 获取月的开始和接收时间
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/10/11
     * Time: 13:53
     * @param string $date 指定日期，默认适用当前日期
     * @return array
     */
    public static function getMonthstartEnd($date = '')
    {
        $date = $date?$date:date('Y-m-d H:i:s');
        $start = date('Y-m-01 00:00:00',strtotime($date));
        return [
            'start' => $start,
            'end' => date('Y-m-d',strtotime("+1 month -1 day $start")).' 23:59:59'
        ];
    }


    /**
     * 计算两个时间范围是否有交集
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/10/11
     * Time: 14:00
     * @param string $beginTime1 时间1开始
     * @param string $endTime1 时间1结束
     * @param string $beginTime2 时间2开始
     * @param string $endTime2 时间2结束
     * @return bool
     */
    public static function isTimeCross($beginTime1 = '', $endTime1 = '', $beginTime2 = '', $endTime2 = ''){
        if($beginTime1 < $beginTime2 && $endTime1 > $endTime2) return true;

        if($beginTime1 > $beginTime2 && $endTime1 < $endTime2) return true;

        if($beginTime1 > $beginTime2 && $endTime1 > $endTime2 && $beginTime1 < $endTime2) return true;

        if($beginTime1 < $beginTime2 && $endTime1 < $endTime2 && $endTime1 > $beginTime2) return true;

        return false;
    }

    /**
     * 日期范围拆分为天
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/11/1
     * Time: 15:44
     * @param string $start 开始时间
     * @param string $end 结束时间
     * @return array
     */
    public static function timeDay($start,$end)
    {
        $re = [];
        $endTime = strtotime($end);
        while (strtotime($start) <= $endTime)
        {
            $re[] = $start;
            $start = date('Y-m-d 00:00:00',strtotime("+1day $start"));
        }
        return $re;
    }

    /**
     * 大写字符串替换为指定字符串
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2020/2/28
     * Time: 17:16
     * @param string $oldStr 原字符串
     * @param string $replace 替换为
     * @return string
     */
    public static function CaStrReplaceStr($oldStr, $replace = '_')
    {
        $str = strtolower(preg_replace('/(?<=[a-z])([A-Z])/', $replace.'$1', $oldStr));
        return $str;
    }
}