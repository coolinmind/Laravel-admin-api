<?php

namespace Pl\LaravelAdminApi\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|between:2,95',
            'permission' => 'array',
        ];
    }

    public function messages()
    {
        return [
            'name.required'  => '角色名称不能为空',
            'name.between'  => '角色名称长度为2-95个字符',
            'permission.array' => '关联权限类型错误,必须为数组',
        ];
    }
}
