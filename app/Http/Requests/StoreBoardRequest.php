<?php

namespace App\Http\Requests;

use App\Actions\CreateNewPriority;
use Illuminate\Foundation\Http\FormRequest;

class StoreBoardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
    
        $priority = app(createNewPriority::class)->execute((array) $this->request);

        $this->merge([
            'id' => (! $this->id) ? 0 : $this->id ,
            'priority_id' => $priority->id
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required',
            'priority_id' => 'required',
        ];
    }
}
