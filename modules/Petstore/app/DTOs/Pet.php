<?php

declare(strict_types=1);

namespace Modules\Petstore\DTOs;

use Illuminate\Http\UploadedFile;

final class Pet
{
    public function __construct(
        public string $name,
        public array $photoUrls,
        public array $tags,
        public string $status,
        public ?int $id = null,
        public ?Category $category = null,
        public ?UploadedFile $photo = null,
    ) {
    }

    public function getTagsAsString(): string
    {
        $tags = array_column($this->tags, 'name');
        return implode(', ', $tags);
    }
}
