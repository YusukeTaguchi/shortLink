<?php

namespace App\Http\Requests\Backend\RedirectLinks;

use Illuminate\Foundation\Http\FormRequest;

class StoreRedirectLinksRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('create-redirect-link');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'domain' => ['required', 'max:191'],
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
            'domain.required' => 'Domain field is required.',
            'domain.max' => 'Domain may not be grater than 191 character.',
            'url.required' => 'Url field is required.',
        ];
    }
}
