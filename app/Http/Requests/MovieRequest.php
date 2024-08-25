<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MovieRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'summary' => 'required|string',
            'year' => 'required|integer',
            'genre_id' => 'nullable|exists:genres,id',
            'poster' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ];
    }
    public function messages(): array
    {
        return [
            'title.required' => 'Inputan Title harus diisi',
            'title.max' => 'Inputan Title maksimal 255 karakter',
            'summary.required' => 'Inputan Summary harus diisi',
            'year.required' => 'Inputan Year harus diisi',
            'year.integer' => 'Inputan Year harus berupa angka',
            'poster.mimes' => 'Inputan gambar harus dalam format jpg, jpeg, atau png',
            'genre_id.required' => 'Inputan genre_id harus diisi',
        ];
    }
    
}
