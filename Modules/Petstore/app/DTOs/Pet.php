<?php

declare(strict_types=1);

namespace Modules\Petstore\DTOs;

use Illuminate\Http\UploadedFile;

final class Pet
{
    public function __construct(
        public ?int $id,
        public string $name,
        public ?Category $category = null,
        public array $photoUrls,
        public array $tags,
        public string $status,
        public ?UploadedFile $photo,
    ) {
    }

    public function getTagsAsString(): string
    {
        $tags = array_column($this->tags, 'name');
        return implode(', ', $tags);
    }
}
