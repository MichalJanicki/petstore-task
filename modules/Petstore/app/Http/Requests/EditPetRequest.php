<?php

declare(strict_types=1);

namespace Modules\Petstore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class EditPetRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            //
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
