<?php

namespace Pl\LaravelAdminApi\Controller;

use Pl\LaravelAdminApi\Requests\PermissionRequest;
use Pl\LaravelAdminApi\Repository\PermissionRepository;
use Illuminate\Http\Request;
use App\Http\Success;

class PermissionController extends Controller
{
    public $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * 权限列表
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/31
     * Time: 14:30
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function permission_list(Request $request)
    {
        $data = $this->permissionRepository->permission_list($request);

        return success::success($data);
    }

    /**
     * 权限详情
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/31
     * Time: 14:29
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function permission_deta(Request $request)
    {
        if(!$request->input('id',''))
        {
            return success::success([],'id不能为空',success::info);
        }
        else
        {
            $data = $this->permissionRepository->permission_deta($request);
        }

        return success::success($data);
    }

    /**
     * 权限添加
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/31
     * Time: 14:10
     * @param PermissionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function permission_add(PermissionRequest $request)
    {

        $re = $this->permissionRepository->permission_add($request);
        if($re === true)
        {
            return success::success();
        }
        return success::success([],$re,success::info);
    }

    /**
     * 权限修改
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/31
     * Time: 14:19
     * @param PermissionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function permission_up(PermissionRequest $request)
    {
        if(!$request->input('id',''))
        {
            return success::success([],'id不能为空',success::info);
        }
        else
        {
            $re = $this->permissionRepository->permission_up($request);
            if($re === true)
            {
                return success::success();
            }

            return success::success([],$re,success::info);
        }
    }

    /**
     * 权限删除
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/31
     * Time: 14:24
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function permission_de(Request $request)
    {
        if(!$request->input('id',''))
        {
            return success::success([],'id不能为空',success::info);
        }
        else
        {
            $this->permissionRepository->permission_de($request);
        }

        return success::success();
    }
}
