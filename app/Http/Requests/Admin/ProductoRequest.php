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
        $isCreate = ! $isUpdate;

        return [
            'nombre' => ['required', 'string', 'min:3', 'max:150', 'not_regex:/<\/?[a-z][^>]*>/i'],
            'categoria_id' => ['required', 'integer', 'exists:categorias,id'],
            'precio' => ['required', 'numeric', 'gt:0', 'decimal:0,2'],
            'stock' => $isUpdate ? ['sometimes', 'integer', 'min:0'] : ['required', 'integer', 'min:0'],
            'imagen' => $isCreate
                ? ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048']
                : ['prohibited'],
            'eliminar_imagen' => ['prohibited'],
        ];
    }
}
