<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\QueryParameter(
    parameter: 'QueryParameterPerPage',
    name: 'perPage',
    description: 'How comments show per page',
    required: false,
    schema: new OA\Schema(type: 'integer', default: 15)
)]
#[OA\QueryParameter(
    parameter: 'QueryParameterPage',
    name: 'page',
    description: 'Current page',
    required: false,
    schema: new OA\Schema(type: 'integer', default: 1)
)]
class ApiPostCommentsPaginatorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'perPage' => 'nullable|numeric',
            'page' => 'nullable|numeric',
        ];
    }
}
