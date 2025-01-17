<?php

namespace Modules\Petstore\Repositories;

use Illuminate\Http\UploadedFile;
use Modules\Petstore\DTOs\Pet;

interface IPetstoreRepository
{
    public function get(int $id): ?Pet;

    public function create(Pet $pet): void;

    public function update(int $id, Pet $pet): void;

    public function delete(int $id): void;

    public function getByStatus(string $status): ?array;

    public function updatePhoto(int $id, UploadedFile $photo): void;
}
