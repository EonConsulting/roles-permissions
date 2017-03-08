<?php
/**
 * Created by PhpStorm.
 * User: jharing10
 * Date: 2017/02/21
 * Time: 5:55 PM
 */

namespace EONConsulting\RolesPermissions\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class UpdateDepartmentRequest extends FormRequest {
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
            'name' => ''
        ];
    }
}
