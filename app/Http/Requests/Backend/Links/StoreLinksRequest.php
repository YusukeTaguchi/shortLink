<?php

namespace App\Http\Requests\Backend\Links;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreLinksRequest.
 */
class StoreLinksRequest extends FormRequest
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
            'title' => ['required', 'max:500', 'unique:links,title'],
            'status' => ['integer', 'between:0,3'],
            'original_link' => ['string', 'nullable', 'url'],
            'keywords' => ['string', 'nullable'],
            'description' => ['string', 'nullable'],
            'thumbnail_image' => ['nullable', 'image'],
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
            'title.unique' => 'The link title already taken. Please try with different title.',
        ];
    }
}
