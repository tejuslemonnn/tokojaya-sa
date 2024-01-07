<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class SettingsInfoRequest extends FormRequest
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
            'phone'         => 'string|max:255',
            'address'      => 'string|max:255',
        ];
    }
}
