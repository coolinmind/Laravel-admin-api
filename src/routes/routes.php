<?php
/**
 * Created by PhpStorm.
 * User: EricPan
 * Date: 2019/8/1
 * Time: 13:58
 */

/**
 * Created by PhpStorm.
 * User: EricPan
 * Date: 2019/7/30
 * Time: 10:15
 */
use Illuminate\Routing\Router;
use \Illuminate\Support\Facades\Route;

Route::group([
    'namespace'     => 'Pl\LaravelAdminApi\Controller',
    'prefix'        => config('admin-api.route.prefix'),
],function (Router $router){

    // 登录
    Route::get('sign','SignController@sign')->middleware('web');


    Route::group([
        'middleware'    => config('admin-api.route.middleware'),
    ],function (Router $router){

        // 退出登录
        $router->get('out','SignController@out');
        // 获取管理员信息
        $router->get('get_user','SignController@get_user');

        /**
         * 系统管理
         */
        $router->group([
            'middleware'    => ['admin.api.role.permission'],
        ],function (Router $router){

            $router->get('admin_list','AdminController@admin_list');                // 管理员list
            $router->get('admin_detail','AdminController@admin_detail');            // 管理员详情
            $router->post('admin_add','AdminController@admin_add');                 // 管理员添加
            $router->post('admin_up','AdminController@admin_up');                   // 管理员修改
            $router->get('admin_de','AdminController@admin_de');                    // 管理员删除


            $router->get('role_list','RoleController@role_list');                   // 角色list
            $router->get('role_detail','RoleController@role_detail');               // 角色详情
            $router->post('role_add','RoleController@role_add');                    // 角色添加
            $router->post('role_up','RoleController@role_up');                      // 角色修改
            $router->post('role_de','RoleController@role_de');                      // 角色删除

            $router->get('permission_list','PermissionController@permission_list'); // 权限列表
            $router->get('permission_deta','PermissionController@permission_deta'); // 权限详情
            $router->post('permission_add','PermissionController@permission_add');  // 权限添加
            $router->post('permission_up','PermissionController@permission_up');    // 权限修改
            $router->get('permission_de','PermissionController@permission_de');     // 权限删除

            $router->get('log_list','OperationLogController@log_list');             // 日志list
        });
    });

});