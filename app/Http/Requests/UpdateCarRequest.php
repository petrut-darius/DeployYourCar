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
            "manufacture" => "sometimes|string|max:50",
            "model" => "sometimes|string|max:50",
            "displacement" => "sometimes|numeric",
            "engineCode" => "sometimes|string",
            "whp" => "sometimes|integer",
            "color" => "sometimes|string|max:50",

            "modifications" => "nullable|array",
            "modifications.*.name" => "sometimes|string|max:255",
            "modifications.*.description" => "nullable|string|max:255",
            "modifications.*.reason" => "sometimes|string|max:255",

            "tags" => "nullable|array",
            "tags.*" => "integer|exists:tags,id",

            "types" => "nullable|array",
            "types.*" => "integer|exists:types,id",

            "story" => "sometimes|string",
            "photos" => "nullable|array",
            "photos.*" => "image|mimes:png,jpg,jpeg|max:51"
        ];
    }

    public function messages():array {
        return [
            "modifications.*.name.string" => "The name of the modification can't be null",
            "modifications.*.description.string" => "The description of the modification can't be null",
            "modifications.*.reason.string" => "The reason of the modification can't be null"
        ];
    }

    public function attributes():array {
        return [
            "modifications.*.name" => "modification name",
            "modifications.*.description" => "modification description",
            "modifications.*.reson" => "modification reason",
        ];
    }
}
