<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
            "image"                 => ['image'],
            "images"                => ['array'],
            "images.*"              => ['image'],
            "short_description"     => ['required'],
            "name"                  => ['required', Rule::unique('products')->ignore($this->product->id)],
            "price"                 => ['required', 'numeric'],        
            "quantity"              => ['required', 'integer'],        
            "discount_type"         => [''],
            "discount_value"        => ['numeric'],
            "categories"            => ['array', 'required'],
            "categories.*"          => ['exists:categories,id'],
            "properties"            => ['array'],
            "properties.*.title"    => [''],
            "properties.*.value"    => [''],
            "previous_images"       => ['array']
        ];
    }
}
