<?php

declare(strict_types=1);

namespace Modules\Petstore\Traits;

use Modules\Petstore\DTOs\Category;
use Modules\Petstore\DTOs\Pet;
use Modules\Petstore\DTOs\Tag;

trait WithPetDto
{
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
