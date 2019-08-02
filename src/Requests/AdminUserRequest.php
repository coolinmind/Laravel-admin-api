<?php
/**
 * Created by PhpStorm.
 * User: EricPan
 * Date: 2019/7/30
 * Time: 11:48
 */

namespace Pl\LaravelAdminApi\Requests;

use Illuminate\Foundation\Http\FormRequest;
class AdminUserRequest extends FormRequest
{
    public function __construct(array $query = [], array $request = [],
                                array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->validate($request,$this->rules(),$this->messages());
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|between:2,20',
            'password' => 'required|between:6,200',
            'name' => 'required|between:2,20',
            'avatar' => 'required',
            'role' => 'array',
            'permission' => 'array',
        ];
    }

    public function messages()
    {
        return [
            'username.required'  => '账号不能为空',
            'username.between'  => '账号长度为2-20个字符',
            'password.required'  => '密码不能为空',
            'password.between'  => '密码长度为2-200个字符',
            'name.required'  => '用户名不能为空',
            'name.between'  => '用户名长度为2-20个字符',
            'avatar.required'  => '头像不能为空',
            'role.array' => '关联角色类型错误,必须为数组',
            'permission.array' => '关联权限类型错误,必须为数组',
        ];
    }
}