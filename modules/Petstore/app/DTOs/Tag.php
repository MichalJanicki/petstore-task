<?php

declare(strict_types=1);

namespace Modules\Petstore\DTOs;

final class Tag
{
    public function __construct(public ?int $id, public string $name)
    {
    }
}
