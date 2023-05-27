<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

use OpenApi\Attributes as OA;
#[OA\Schema(
    title: 'Request body for comment model',
    properties: [
        new OA\Property(property: 'content', type: 'string', example: 'My first comment here'),
    ]
)]
class CommentRequest extends FormRequest
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
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'content' => 'required|min:10',
        ];
    }
}
