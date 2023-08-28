<?php

namespace App\Http\Requests\Admin\Setting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'image' => ['nullable', 'image', 'max:2048'],
            'color' => 'required|string|max:255',
            'sound_1' => ['nullable', 'mimes:mp3,mpga,wav', 'max:2048'],
            'sound_2' => ['nullable', 'mimes:mp3,mpga,wav', 'max:2048'],
            'sound_3' => ['nullable', 'mimes:mp3,mpga,wav', 'max:2048'],
        ];
    }
}
