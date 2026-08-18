<?php

namespace App\Http\Requests\Audio;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AudioFileRequest extends FormRequest
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
            'file' => [
                'required',
                'file',
                'mimes:mp3,wav,m4a,ogg,aac',
                'max:51200', //Max 50MB
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'File audio wajid di upload',
            'file.file' => 'File yang dikirim tidak valid',
            'file.mimes' => 'Format audio yang diperbolehkan adalah MP3, WAV, M4A, OGG, dan AAC',
            'file.max' => 'Ukuran file audio maksimal 50 MB',
        ];
    }
}
