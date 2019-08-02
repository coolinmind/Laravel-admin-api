<?php
/**
 * Created by PhpStorm.
 * User: EricPan
 * Date: 2019/8/1
 * Time: 14:41
 */

namespace Pl\LaravelAdminApi;


class success
{
    const info = 201;
    const code_success = 200;
    const code_sign_error = 202;

    /**
     * 返回方法
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/8/1
     * Time: 14:41
     * @param array $data
     * @param string $msg
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    static function success($data = [],$msg = '成功',$code = success::code_success)
    {
        return response()->json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ]);
    }
}