<?php
/**
 * Created by PhpStorm.
 * User: EricPan
 * Date: 2019/8/1
 * Time: 13:55
 */
return [
    'route' => [
        'prefix' => 'gjgase',
        'middleware' => ['web','admin.api']
    ],

    /**
     * 系统管理员名称定义
     * 此角色免权限验证
     */
    'root_role_name' => '系统管理员',

    'upload' => [

        // Disk in `config/filesystem.php`.
        'disk' => 'admin',

        // Image and file upload path under the disk above.
        'directory' => [
            'image' => 'public/images/'.date('Y').'/'.date('m').'/'.date('d'),
            'file'  => 'public/files/'.date('Y').'/'.date('m').'/'.date('d'),
        ],
    ],

    /**
     * 模拟数据
     */
    'simulation' => [
        /**
         * 模拟用户信息
         * 有值时表示当前登录用户是此用户
         * 没有值时需要正常登录
         * 1 是系统管理员
         * 2 是管理员
         */
        'user' => 1,
//        'user' => '',
    ],

    /**
     * 常用类库配置信息
     */
    'common' => [
        // excel 导出下载目录
        'excel' => 'download/excel',

    ],
];