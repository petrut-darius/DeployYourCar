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
            "manufacture" => "required|string",
            "model" => "required|string",
            "displacement" => "required|string",
            "engineCode" => "required|string",
            "whp" => "required|integer",
            "color" => "required|string",

            "modifications" => "nullable|array",
            "modifications.*.name" => "required|string|max:20",
            "modifications.*.description" => "nullable|string",
            "modifications.*.reason" => "required|string",

            "tags" => "nullable|array",
            "tags.*.id" => "required|integer",

            "types" => "nullable|array",
            "types.*.id" => "required|integer",

            "storyBodyText" => "required|string",
            "storyBodyHtml" => "required|string",

            "photos" => "required",
            "photos.*" => "image|mimes:png,jpg,jpeg|max:5120"
        ];
    }
}
