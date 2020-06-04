<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ZeroOrExists;

class UserRequest extends FormRequest
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
        return [
            'id' => ['required', new ZeroOrExists('permissions', 'id')],
            'name' => 'required|string|min:4|max:64',
            'email' => 'required|unique:users|email',
            'password' => 'required_unless:id,0|string|min:5',
            'roles' => 'sometime|array',
            'roles.*' => 'required|string|exists:roles,name',
            'photo_url' => 'sometimes|url'
        ];
    }
}
