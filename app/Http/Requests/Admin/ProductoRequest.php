<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isUpdate = $this->route('producto') !== null;

        return [
            'nombre' => ['required', 'string', 'min:3', 'max:150'],
            'categoria_id' => ['required', 'integer', 'exists:categorias,id'],
            'precio' => ['required', 'numeric', 'gt:0', 'decimal:0,2'],
            'stock' => $isUpdate ? ['sometimes', 'integer', 'min:0'] : ['required', 'integer', 'min:0'],
            'imagen' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'eliminar_imagen' => ['sometimes', 'boolean'],
        ];
    }
}
