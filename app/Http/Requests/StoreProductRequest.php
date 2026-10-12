<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'name')->ignore($productId),
            ],
            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
            ],
            'price' => [
                'required',
                'integer',
                'min:0',
            ],
            'stock' => [
                'required',
                'integer',
                'min:0',
            ],
        ];
    }
}