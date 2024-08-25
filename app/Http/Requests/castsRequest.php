<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class castsRequest extends FormRequest
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
            'name' => 'required|max:255',
            'age' => 'required|max:3',
            'bio' => 'required'
        ];
    }
    public function messages():array
    {
        return [
            'name.required' => 'Inputan Name harus diisi',
            'name.max' => 'Inputan Name maksimal 255 karakter',
            'age.required' => 'Inputan Age harus diisi',
            'name.max' => 'Inputan Age maksimal 3 digit',
            'bio.required' => 'Inputan Bio harus diisi'
        ];
    }
}
