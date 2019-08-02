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
         * 有值时表示当前登录用户是次用户
         * 没有值时需要正常登录
         */
//        'user' => json_decode('{"id":1,"username":"admin","name":"\u7ba1\u7406\u5458","avatar":"https:\/\/eaznuftest.beats-digital.com\/storage\/images\/2019\/07\/30\/bcovr1q5wtOAvXwLx3UDBuPJMLvQ9eZJ258I4Hzs.jpeg","created_at":"2019-07-30 13:17:32","updated_at":"2019-07-30 14:49:56"}',true),
        'user' => '',
    ]
];