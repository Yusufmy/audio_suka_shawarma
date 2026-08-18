<?php

namespace App\Http\Requests\Broadcast;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BroadcastRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'target_mode' => [
                'required',
                'in:all,specific',
            ],

            'outlet_ids' => [
                'required_if:target_mode,specific',
                'array',
                'min:1',
            ],

            'outlet_ids.*' => [
                'integer',
                'exists:outlets,id',
            ],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'target_mode.required' => 'Target broadcast wajib dipilih.',
            'target_mode.in' => 'Target broadcast tidak valid.',

            'outlet_ids.required_if' => 'Outlet tujuan wajib dipilih jika target mode specific.',
            'outlet_ids.array' => 'Outlet tujuan harus berupa array.',
            'outlet_ids.min' => 'Minimal pilih satu outlet.',
            'outlet_ids.*.integer' => 'ID outlet harus berupa angka.',
            'outlet_ids.*.exists' => 'Outlet yang dipilih tidak ditemukan.',
        ];
    }
}
