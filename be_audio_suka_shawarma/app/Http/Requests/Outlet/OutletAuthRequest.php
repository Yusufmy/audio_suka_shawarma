<?php

namespace App\Http\Requests\Outlet;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class OutletAuthRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'exists:outlets,name',
            ],

            'device_info' => [
                'nullable',
                'array',
            ],

            // Identitas unik tablet, wajib ada di dalam device_info.
            'device_info.device_id' => [
                'required',
                'string',
                'max:191',
            ],

            'device_info.model' => [
                'nullable',
                'string',
                'max:255',
            ],

            'device_info.os' => [
                'nullable',
                'string',
                'max:255',
            ],

            'device_info.app_version' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama outlet wajib diisi.',
            'name.string' => 'Nama outlet harus berupa teks.',
            'name.exists' => 'Nama outlet tidak ditemukan.',

            'device_info.required' => 'Informasi perangkat wajib disertakan.',
            'device_info.array' => 'Informasi perangkat harus berupa object.',


            'device_info.device_id.required' => 'Identitas perangkat wajib disertakan.',
            'device_info.device_id.string' => 'Identitas perangkat tidak valid.',


            'device_info.model.string' => 'Model perangkat harus berupa teks.',
            'device_info.model.max' => 'Model perangkat maksimal 255 karakter.',

            'device_info.os.string' => 'Sistem operasi harus berupa teks.',
            'device_info.os.max' => 'Sistem operasi maksimal 255 karakter.',

            'device_info.app_version.string' => 'Versi aplikasi harus berupa teks.',
            'device_info.app_version.max' => 'Versi aplikasi maksimal 255 karakter.',
        ];
    }
}
