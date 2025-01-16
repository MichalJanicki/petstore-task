<?php

declare(strict_types=1);

namespace Modules\Petstore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Petstore\DTOs\Category;
use Modules\Petstore\DTOs\Pet;
use Modules\Petstore\DTOs\Tag;
use Modules\Petstore\Enums\PetStatus;

class StorePetRequest extends FormRequest
{
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
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function getDto(): Pet
    {
        $category = $this->input('category') ? new Category(null, $this->input('category')) : null;
        $tagsList = $this->input('tags') ? explode(',', $this->input('tags')) : [];

        $tags = array_map(fn($tag) => new Tag(null, $tag), $tagsList);

        return new Pet(
            null,
            $this->input('name'),
            $category, $this->input('photoUrls', []),
            $tags,
            $this->input('status'),
            null
        );
    }
}
