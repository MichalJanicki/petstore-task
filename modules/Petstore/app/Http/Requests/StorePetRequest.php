<?php

declare(strict_types=1);

namespace Modules\Petstore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Petstore\Enums\PetStatus;
use Modules\Petstore\Traits\WithPetDto;

final class StorePetRequest extends FormRequest
{
    use WithPetDto;

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'category' => 'string|nullable',
            'tags' => 'string|nullable',
            'status' => [Rule::enum(PetStatus::class)],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
