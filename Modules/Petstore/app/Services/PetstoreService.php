<?php

declare(strict_types=1);

namespace Modules\Petstore\Services;

use Illuminate\Support\Facades\Http;
use Modules\Petstore\DTOs\Category;
use Modules\Petstore\DTOs\Pet;
use Modules\Petstore\DTOs\Tag;
use Modules\Petstore\Exceptions\ConnectionErrorException;
use Modules\Petstore\Exceptions\PetNotFoundException;

final class PetstoreService implements IPetstoreService
{
    /**
     * @throws ConnectionErrorException
     */
    public function create(Pet $pet): void
    {
        $url = config('petstore.resource_url');
        $response = Http::post("{$url}", [
            'name' => $pet->name,
            'category' => $pet->category,
            'tags' => $pet->tags,
            'status' => $pet->status,
        ]);

        if ($response->failed()) {
            throw new ConnectionErrorException("Unexpected error - try again later");
        }
    }

    /**
     * @throws ConnectionErrorException
     */
    public function update(string $id, Pet $pet): void
    {
        $url = config('petstore.resource_url');
        $response = Http::put("{$url}", [
            'id' => $id,
            'name' => $pet->name,
            'category' => $pet->category,
            'tags' => $pet->tags,
            'status' => $pet->status,
        ]);
        
        if ($response->failed()) {
            throw new ConnectionErrorException("Unexpected error - try again later");
        }
    }

    public function delete(string $id): void
    {
        // TODO: Implement delete() method.
    }

    /**
     * @throws ConnectionErrorException
     */
    public function getByStatus(?string $status): ?array
    {
        if (is_null($status)) {
            return [];
        }

        $petList = [];
        $url = config('petstore.resource_url');
        $response = Http::get("{$url}/findByStatus?status=$status");

        if ($response->failed()) {
            throw new ConnectionErrorException('Unexpected error - try again later');
        }

        $jsonResponse = $response->json();

        foreach ($jsonResponse as $element) {
            $petList[] = $this->mapToPet($element);
        }

        return $petList;
    }

    /**
     * @throws PetNotFoundException
     */
    public function get(string $id): ?Pet
    {
        $url = config('petstore.resource_url');
        $response = Http::get("{$url}/{$id}");

        if ($response->failed()) {
            throw new PetNotFoundException('Pet not found');
        }

        return $this->mapToPet($response->json());
    }

    private function mapToPet(array $data): Pet
    {
        $category = (array_key_exists('category', $data)) ? $this->mapToCategory(
            $data['category']['id'],
            $data['category']['name'] ?? ''
        ) : null;

        return new Pet(
            $data['id'] ?? null,
            $data['name'] ?? '',
            $category,
            $data['photoUrls'],
            $this->mapToTags($data['tags']),
            $data['status'],
            null
        );
    }

    private function mapToCategory(?int $id, string $name): Category
    {
        return new Category($id ?? null, $name);
    }

    private function mapToTags(array $tags): array
    {
        return array_map(fn($tag) => new Tag($tag['id'] ?? null, $tag['name'] ?? ''), $tags);
    }
}
