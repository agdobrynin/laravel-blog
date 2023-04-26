<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|min:5|max:100',
            'content' => 'required|min:10',
            'tags' => 'required|array|min:1',
            'thumb' => 'image|mimes:jpg,jpeg,png,svg,gif|max:2500|dimensions:min_width=200,min_height=100,max_width=4000,max_height=4000'
        ];
    }
}
