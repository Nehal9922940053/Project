<?php

namespace App\Http\Requests\Admin\Vet;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateVet extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.vet.edit', $this->vet);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string'],
            'image' => ['sometimes', 'string'],
            'phone_number' => ['sometimes', 'string'],
            'address' => ['sometimes', 'string'],
            'details' => ['sometimes', 'string'],
            'gender' => ['sometimes', 'integer'],
            'latitude' => ['sometimes', 'numeric'],
            'longitude' => ['sometimes', 'numeric'],
            'enabled' => ['sometimes', 'boolean'],
            
        ];
    }

    /**
     * Modify input data
     *
     * @return array
     */
    public function getSanitized(): array
    {
        $sanitized = $this->validated();


        //Add your code for manipulation with request data here

        return $sanitized;
    }
}
