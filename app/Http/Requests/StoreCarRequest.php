<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarRequest extends FormRequest
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
            "manufacture" => "required|string|max:50",
            "model" => "required|string|max:50",
            "displacement" => "required|numeric",
            "engineCode" => "required|string",
            "whp" => "required|integer",
            "color" => "required|string|max:50",

            "modifications" => "nullable|array",
            "modifications.*.name" => "required|string|max:255",
            "modifications.*.description" => "nullable|string|max:255",
            "modifications.*.reason" => "required|string|max:255",

            "tags" => "nullable|array",
            "tags.*" => "integer|exists:tags,id",

            "types" => "nullable|array",
            "types.*" => "integer|exists:types,id",

            "story" => "required|string",

            "photos" => "nullable|array",
            "photos.*" => "image|mimes:png,jpg,jpeg|max:5120"
        ];
    }
}
