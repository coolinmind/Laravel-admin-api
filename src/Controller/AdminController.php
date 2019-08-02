<?php

namespace Pl\LaravelAdminApi\Controller;

use Pl\LaravelAdminApi\Requests\AdminUserRequest;
use Pl\LaravelAdminApi\Repository\AdminRepository;
use Illuminate\Http\Request;
use Pl\LaravelAdminApi\success;

class AdminController extends Controller
{

    public $adminRepository;

    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    /**
     * 管理员list
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/30
     * Time: 13:43
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function admin_list(Request $request)
    {
        $data = $this->adminRepository->admin_list($request);

        return success::success($data);
    }

    /**
     * 管理员详情
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/31
     * Time: 13:41
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function admin_detail(Request $request)
    {
        $id = $request->input('id','');
        $code = success::code_success;
        // 不能为空
        if(!$id)
        {
            return success::success([],'id不能为空',success::info);
        }
        else
        {
            $data = $this->adminRepository->admin_detail($request);
        }

        return success::success($data,$code);

    }

    /**
     * 管理员添加
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/30
     * Time: 10:30
     * @param Request $request
     */
    public function admin_add(AdminUserRequest $request)
    {
        $re = $this->adminRepository->admin_add($request);

        if($re === true)
        {
            return success::success();
        }

        return success::success([],$re,success::info);
    }

    /**
     * 管理员修改
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/30
     * Time: 13:20
     * @param AdminUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function admin_up(AdminUserRequest $request)
    {
        $id = $request->input('id','');

        // 不能为空
        if(!$id)
        {
            return success::success([],'id不能为空',success::info);
        }
        else
        {
            $re = $this->adminRepository->admin_up($request);
            if($re === true)
            {
                return success::success();
            }

            return success::success([],$re,success::info);
        }
    }

    /**
     * 管理员删除
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/30
     * Time: 13:38
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function admin_de(Request $request)
    {
        $id = $request->input('id','');
        // 不能为空
        if(!$id)
        {
            return success::success([],'id不能为空',success::info);
        }
        {
            $this->adminRepository->admin_de($request);
        }
        return success::success();
    }
}
