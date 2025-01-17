<?php

namespace Modules\Petstore\Services;

use Illuminate\Http\UploadedFile;
use Modules\Petstore\DTOs\Pet;

interface IPetstoreService
{
    public function get(string $id): ?Pet;

    public function create(Pet $pet): void;

    public function update(string $id, Pet $pet): void;

    public function delete(string $id): void;

    public function getByStatus(string $status): ?array;

    public function updatePhoto(string $id, UploadedFile $photo): void;
}
