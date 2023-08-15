<?php

namespace App\Http\Requests\Admin\Level;

use Illuminate\Foundation\Http\FormRequest;

class StoreLevelRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'order' => 'required|integer',
            'color' => 'required|string|max:255',
            'type' => 'required|string|in:score,evaluation',
            'evaluations' => 'required_if:type,evaluation|array',
            'evaluations.*.name' => 'required|string|max:255',
            'evaluations.*.criteria' => 'required|array',
            'evaluations.*.criteria.*.name' => 'required|string|max:255',
            'evaluations.*.criteria.*.weight' => 'required|integer',
            'evaluations.*.criteria.*.order' => 'required|integer',
        ];
    }
}
