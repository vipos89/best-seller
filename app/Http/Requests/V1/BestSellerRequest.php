<?php

declare(strict_types=1);

namespace app\Http\Requests\V1;

use App\Rules\IsbnRule;
use Illuminate\Foundation\Http\FormRequest;

class BestSellerRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'author' => 'string|nullable',
            'isbn' => 'array|nullable',
            'isbn.*' => ['string', new IsbnRule()],
            'title' => 'string|nullable',
            'offset' => 'nullable|integer|min:0|multiple_of:20',
        ];
    }
}
