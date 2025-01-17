<?php

declare(strict_types=1);

namespace Modules\Petstore\Repositories;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Modules\Petstore\DTOs\Category;
use Modules\Petstore\DTOs\Pet;
use Modules\Petstore\DTOs\Tag;
use Modules\Petstore\Exceptions\ConnectionErrorException;
use Modules\Petstore\Exceptions\InvalidPetStatusException;
use Modules\Petstore\Exceptions\PetNotFoundException;

final class PetstoreRepository implements IPetstoreRepository
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
    public function update(int $id, Pet $pet): void
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

    /**
     * @throws ConnectionErrorException
     * @throws PetNotFoundException
     */
    public function delete(int $id): void
    {
        $url = config('petstore.resource_url');
        $response = Http::delete("{$url}/{$id}");

        if (404 === $response->status()) {
            throw new PetNotFoundException('Pet not found');
        }

        if ($response->failed()) {
            throw new ConnectionErrorException("Unexpected error - try again later");
        }
    }

    /**
     * @throws ConnectionErrorException
     * @throws InvalidPetStatusException
     */
    public function getByStatus(string $status): array
    {
        $petList = [];
        $url = config('petstore.resource_url');
        $response = Http::get("{$url}/findByStatus?status=$status");

        if (400 === $response->status()) {
            throw new InvalidPetStatusException('Invalid pet status');
        }

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
     * @throws ConnectionErrorException
     */
    public function get(int $id): ?Pet
    {
        $url = config('petstore.resource_url');
        $response = Http::get("{$url}/{$id}");

        if (404 === $response->status()) {
            throw new PetNotFoundException('Pet not found');
        }

        if ($response->failed()) {
            throw new ConnectionErrorException("Unexpected error - try again later");
        }

        return $this->mapToPet($response->json());
    }

    /**
     * @throws ConnectionException
     * @throws PetNotFoundException
     */
    public function updatePhoto(int $id, UploadedFile $photo): void
    {
        $url = config('petstore.resource_url');
        $response = Http::attach(
            'file',
            fopen(
                $photo->getPathname(),
                'r'
            ),
            $photo->getClientOriginalName()
        )
            ->post("{$url}/{$id}/uploadImage");

        if (404 === $response->status()) {
            throw new PetNotFoundException('Pet not found');
        }
    }

    private function mapToPet(array $data): Pet
    {
        $category = (array_key_exists('category', $data)) ? $this->mapToCategory(
            $data['category']['id'],
            $data['category']['name'] ?? ''
        ) : null;

        return new Pet(
            $data['name'] ?? '',
            $data['photoUrls'],
            $this->mapToTags($data['tags']),
            $data['status'],
            $data['id'],
            $category,
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
