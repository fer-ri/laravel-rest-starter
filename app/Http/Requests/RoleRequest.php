<?php

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;

class RoleRequest extends FormRequest
{
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
        $unique = '';

        if ($this->method() == 'PUT') {
            $unique = ',name,'.$this->uuid.',uuid';
        }

        return [
            'name' => 'required|string|max:255|unique:roles'.$unique,
            'display_name' => 'string|max:255',
            'description' => 'string|max:255',
            'permissions' => 'permissions',
            'level' => 'numeric',
        ];
    }
}
