<?php

namespace Pl\LaravelAdminApi\Models;

use Illuminate\Database\Eloquent\Model;

class Admin_operation_log extends Model
{
    protected $table = 'admin_api_operation_log';

    /**
     * 用户表
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/8/1
     * Time: 13:17
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(Admin_user::class,'id','u_id')->select('id','name','avatar');
    }

    /**
     * 请求参数字段设置
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/8/1
     * Time: 11:43
     * @param $v
     */
    public function getInputAttribute($v)
    {
        return json_decode($v,true);
    }

    /**
     * 保存参数字段设置
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/8/1
     * Time: 11:50
     * @param $v
     * @return false|string
     */
    public function setInputAttribute($v)
    {
        return $this->attributes['input'] = json_encode($v);
    }

}
