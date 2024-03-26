<?php

namespace App\Http\Requests\Backend\Links;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateLinksRequest.
 */
class UpdateLinksRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('edit-link');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['required', 'max:500'],
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
            'title.unique' => 'Link title already exists, please enter a different title.',
            'title.required' => 'Please insert Link Title',
            'title.max' => 'Link Title may not be greater than 500 characters.',
        ];
    }
}
