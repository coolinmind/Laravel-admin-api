<?php

namespace Pl\LaravelAdminApi\Controller;

use Pl\LaravelAdminApi\Requests\RoleRequest;
use Pl\LaravelAdminApi\Repository\RoleRepository;
use Illuminate\Http\Request;
use Pl\LaravelAdminApi\success;

class RoleController extends Controller
{
    public $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * 角色list
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/31
     * Time: 13:23
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function role_list(Request $request)
    {
        $data = $this->roleRepository->role_list($request);

        return success::success($data);
    }

    /**
     * 角色详情
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/31
     * Time: 13:35
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function role_detail(Request $request)
    {
        if(!$request->input('id',''))
        {
            return success::success([],'id不能为空',success::info);
        }
        else
        {
            $data = $this->roleRepository->role_detail($request);
        }

        return success::success($data);
    }

    /**
     * 管理员添加
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/31
     * Time: 11:48
     * @return \Illuminate\Http\JsonResponse
     */
    public function role_add(RoleRequest $request)
    {
        $re = $this->roleRepository->role_add($request);
        if($re === true)
        {
            return success::success();
        }
        return success::success([],$re,success::info);
    }

    /**
     * 角色修改
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/31
     * Time: 13:12
     * @param RoleRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function role_up(RoleRequest $request)
    {
        if(!$request->input('id',''))
        {
            return success::success([],'id不能为空',success::info);
        }
        else
        {
            $re = $this->roleRepository->role_up($request);
            if($re === true)
            {
                return success::success();
            }

            return success::success([],$re,success::info);
        }
    }

    /**
     * 角色删除
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/31
     * Time: 13:19
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function role_de(Request $request)
    {
        if(!$request->input('id',''))
        {
            return success::success([],'id不能为空',success::info);
        }
        else
        {
            $this->roleRepository->role_de($request);
        }

        return success::success();
    }
}
