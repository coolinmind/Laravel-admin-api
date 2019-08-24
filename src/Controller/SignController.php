<?php

namespace Pl\LaravelAdminApi\Controller;

use Pl\LaravelAdminApi\Repository\SignRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Success;

class SignController extends Controller
{

    public $signRepository;

    public function __construct(SignRepository $signRepository)
    {
        $this->signRepository = $signRepository;
    }

    /**
     * 登录
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/30
     * Time: 14:43
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sign(Request $request)
    {
        $username = $request->input('username','');
        $password = $request->input('password','');

        if(!$username || !$password)
        {
            return success::success([],success::info,'账号或密码不能为空');
        }

        $re = $this->signRepository->sign($request);

        return success::success($re['data'],$re['msg'],$re['code']);
    }

    /**
     * 退出登录
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/30
     * Time: 15:28
     * @return \Illuminate\Http\JsonResponse
     */
    public function out()
    {
        $this->signRepository->out();

        return success::success([]);
    }

    /**
     * 获取管理员信息
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/7/30
     * Time: 15:30
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_user()
    {
        return success::success($this->signRepository->get_admin_auth());
    }
}
