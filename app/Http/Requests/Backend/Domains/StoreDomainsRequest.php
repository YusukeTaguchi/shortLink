<?php

namespace App\Http\Requests\Backend\Domains;

use Illuminate\Foundation\Http\FormRequest;

class StoreDomainsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('create-domain');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'max:500'],
            'url' => ['required', 'string'],
            'status' => ['boolean'],
        ];
    }

    /**
     * Show the Messages for rules above.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Name field is required.',
            'name.max' => 'Name may not be grater than 500 character.',
            'url.required' => 'Url field is required.',
        ];
    }
}
