<?php

declare(strict_types=1);

namespace Modules\Petstore\Enums;

enum PetStatus: string
{
    case AVAILABLE = 'available';
    case PENDING = 'pending';
    case SOLD = 'sold';
}
