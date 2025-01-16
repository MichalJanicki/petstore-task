<?php

declare(strict_types=1);

namespace Modules\Petstore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePhotoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
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
