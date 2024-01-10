<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            "image"                 => ['required', 'image'],
            "images"                => ['array',  'required', 'min:3'],
            "images.*"              => ['image'],
            "short_description"     => ['required'],
            "name"                  => ['required', 'unique:products,name'],
            "price"                 => ['required', 'numeric'],        
            "quantity"              => ['required', 'integer'],        
            "discount_type"         => [''],
            "discount_value"        => ['numeric'],
            "categories"            => ['array', 'required'],
            "categories.*"          => ['required', 'exists:categories,id'],
            "properties"            => ['array'],
            "properties.*.title"    => [''],
            "properties.*.value"    => ['']
        ];
    }

    public function messages(): array
    {
        return [
            "image.required" => "Please upload a product image",
            "images.min"     => "Please upload at least :min other images to create this product",
            "images.*.image" => "File uploaded must be an image",
            "image.image"    => "File uploaded must be an image",
        ];
    }
}
