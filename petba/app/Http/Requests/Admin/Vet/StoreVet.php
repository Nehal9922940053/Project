<?php

namespace App\Http\Requests\Admin\Vet;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreVet extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.vet.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'image' => ['required', 'string'],
            'phone_number' => ['required', 'string'],
            'address' => ['required', 'string'],
            'details' => ['required', 'string'],
            'gender' => ['required', 'integer'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'enabled' => ['required', 'boolean'],
            
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
