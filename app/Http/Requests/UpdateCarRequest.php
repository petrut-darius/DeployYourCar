<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "manufacture" => "sometimes|string",
            "model" => "sometimes|string",
            "displacement" => "sometimes|string",
            "engineCode" => "sometimes|string",
            "whp" => "sometimes|integer",
            "color" => "sometimes|string",

            "modifications" => "nullable|array",
            "modifications.*.name" => "sometimes|string|max:20",
            "modifications.*.description" => "nullable|string",
            "modifications.*.reason" => "sometimes|string",

            "tags" => "nullable|array",
            "tags.*.id" => "sometimes|integer",

            "types" => "nullable|array",
            "types.*.id" => "sometimes|integer",

            "storyBodyText" => "sometimes|string",
            "storyBodyHtml" => "sometimes"
        ];
    }
}
