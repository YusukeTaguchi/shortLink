<?php

namespace App\Http\Requests\Backend\Links;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateLinksRequest.
 */
class CreateLinksRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('create-link');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:500',
            'thumbnail_image' => 'required',
            'keywords' => 'required',
            'description' => 'required',
            'original_link' => 'required',
        ];
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => 'Please insert Link Title',
            'title.max' => 'Link Title may not be greater than 500 characters.',
            'title.unique' => 'The Link title already taken. Please try with different name.',
        ];
    }
}
