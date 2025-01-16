<?php

namespace Modules\Petstore\DTOs;

class Category
{

    public function __construct(public ?int $id, public string $name)
    {
    }
}
