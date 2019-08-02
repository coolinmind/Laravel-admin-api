<?php
/**
 * Created by PhpStorm.
 * User: EricPan
 * Date: 2019/8/1
 * Time: 11:33
 */

namespace Pl\LaravelAdminApi\Repository;

use Pl\LaravelAdminApi\Models\Admin_operation_log;
use Illuminate\Http\Request;

class OperationLogRepository
{

    /**
     * 日志list
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/8/1
     * Time: 11:59
     * @param Request $request
     * @return array
     */
    public function log_list(Request $request)
    {
        $data = [];

        $paginate = $request->input('paginate',10); // 分页数量
        $method = $request->input('method',''); // 请求类型 GET POST DELETE PUT HEAD OPTIONS PATCH
        $u_id = $request->input('u_id','');     // 用户id
        $ip = $request->input('ip','');         // ip
        $path = $request->input('path','');     // 路径

        $query = Admin_operation_log::query();

        if($method) $query->where('method',$method);
        if($u_id) $query->where('u_id',$u_id);
        if($ip) $query->where('id','like','%'.$ip.'%');
        if($path) $query->where('path','like','%'.$path.'%');

        $query->with('user');

        $data = $query->paginate($paginate);

        return $data;
    }


    /**
     * 添加日志记录
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/8/1
     * Time: 11:36
     * @param $u_id
     * @param $path
     * @param $method
     * @param $input
     */
    public function add($u_id,$path,$method,$input)
    {
        $comm = new CommonRepository();
        $ip = $comm->ip();

        $admin_operation_log = new Admin_operation_log();

        $admin_operation_log->u_id = $u_id;
        $admin_operation_log->path = $path;
        $admin_operation_log->method = $method;
        $admin_operation_log->input = $input;
        $admin_operation_log->ip = $ip;
        $admin_operation_log->save();
    }
}