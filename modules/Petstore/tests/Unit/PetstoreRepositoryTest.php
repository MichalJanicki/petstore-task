<?php

declare(strict_types=1);

namespace Modules\Petstore\Tests\Unit;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Modules\Petstore\DTOs\Category;
use Modules\Petstore\DTOs\Pet;
use Modules\Petstore\DTOs\Tag;
use Modules\Petstore\Enums\PetStatus;
use Modules\Petstore\Exceptions\ConnectionErrorException;
use Modules\Petstore\Exceptions\InvalidPetStatusException;
use Modules\Petstore\Exceptions\PetNotFoundException;
use Modules\Petstore\Repositories\PetstoreRepository;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class PetstoreRepositoryTest extends TestCase
{
    protected PetstoreRepository $petstoreRepository;
    private string $url;

    #[Test] public function throws_exception_when_create_fails(): void
    {
        $this->expectException(ConnectionErrorException::class);
        $this->expectExceptionMessage('Unexpected error - try again later');

        $pet = $this->getPet();
        Http::fake([
            $this->url => Http::response([
                "code" => 500,
                "type" => "unknown",
                "message" => "something bad happened"
            ], 500)
        ]);

        $this->petstoreRepository->create($pet);
    }

    #[Test] public function should_create_pet_without_exception()
    {
        Http::fake([
            $this->url => Http::response([
                "id" => 55,
                "name" => "pet",
                "photoUrls" => [],
                "tags" => [],
                "status" => "sold"
            ], 200)
        ]);

        $pet = $this->getPet();
        $this->petstoreRepository->create($pet);

        $this->expectNotToPerformAssertions();
    }

    #[Test] public function should_update_pet_without_exception(): void
    {
        Http::fake([
            $this->url => Http::response([
                "id" => 55,
                "name" => "pet",
                "photoUrls" => [],
                "tags" => [],
                "status" => "sold"
            ], 200)
        ]);

        $pet = $this->getPet();
        $this->petstoreRepository->update("1", $pet);

        $this->expectNotToPerformAssertions();
    }

    #[Test] public function throws_exception_when_update_fails(): void
    {
        $this->expectException(ConnectionErrorException::class);
        $this->expectExceptionMessage('Unexpected error - try again later');

        $pet = $this->getPet();
        Http::fake([
            $this->url => Http::response([
                "code" => 500,
                "type" => "unknown",
                "message" => "something bad happened"
            ], 500)
        ]);

        $this->petstoreRepository->update("1", $pet);
    }

    #[Test] public function should_delete_pet_without_exception(): void
    {
        Http::fake([
            "{$this->url}/1" => Http::response([
                "code" => 200,
                "type" => "unknown",
                "message" => "1"
            ], 200)
        ]);

        $this->petstoreRepository->delete("1");

        $this->expectNotToPerformAssertions();
    }

    #[Test] public function throws_exception_when_delete_fails(): void
    {
        $this->expectException(ConnectionErrorException::class);
        $this->expectExceptionMessage('Unexpected error - try again later');

        Http::fake([
            "{$this->url}/1" => Http::response([
                "code" => 500,
                "type" => "unknown",
                "message" => "something bad happened"
            ], 500)
        ]);

        $this->petstoreRepository->delete("1");
    }

    #[Test] public function throws_exception_when_deleted_pet_not_found(): void
    {
        $this->expectException(PetNotFoundException::class);
        $this->expectExceptionMessage('Pet not found');

        Http::fake([
            "{$this->url}/1" => Http::response([], 404)
        ]);

        $this->petstoreRepository->delete("1");
    }

    #[Test] public function should_get_pets_by_status()
    {
        Http::fake([
            "{$this->url}/findByStatus?status=sold" => $this->getPetsByStatusResponse()
        ]);

        $pets = $this->petstoreRepository->getByStatus(PetStatus::SOLD->value);

        $this->assertCount(2, $pets);
        $this->assertInstanceOf(Pet::class, $pets[0]);
        $this->assertInstanceOf(Pet::class, $pets[1]);
    }

    #[Test] public function throws_exception_when_get_pet_by_status_fails(): void
    {
        $this->expectException(ConnectionErrorException::class);
        $this->expectExceptionMessage('Unexpected error - try again later');

        Http::fake([
            "{$this->url}/findByStatus?status=sold" => Http::response([
                "code" => 500,
                "type" => "unknown",
                "message" => "something bad happened"
            ], 500)
        ]);

        $this->petstoreRepository->getByStatus(PetStatus::SOLD->value);
    }

    #[Test] public function throws_not_found_on_invalid_status(): void
    {
        $this->expectException(InvalidPetStatusException::class);
        $this->expectExceptionMessage('Invalid pet status');

        Http::fake([
            "{$this->url}/findByStatus?status=invalid-status" => Http::response([], 400)
        ]);

        $this->petstoreRepository->getByStatus('invalid-status');
    }

    #[Test] public function should_can_get_a_pet_by_id(): void
    {
        Http::fake([
            "{$this->url}/1" => $this->getPetByStatusResponse()
        ]);

        $pet = $this->petstoreRepository->get("1");
        $this->assertInstanceOf(Pet::class, $pet);
    }

    #[Test] public function throws_not_found_when_pet_absent(): void
    {
        $this->expectException(PetNotFoundException::class);
        $this->expectExceptionMessage('Pet not found');

        Http::fake([
            "{$this->url}/1" => Http::response([], 404)
        ]);

        $this->petstoreRepository->get("1");
    }

    #[Test] public function throws_exception_on_get_pet_by_id_failure(): void
    {
        $this->expectException(ConnectionErrorException::class);
        $this->expectExceptionMessage('Unexpected error - try again later');

        Http::fake([
            "{$this->url}/1" => Http::response([
                "code" => 500,
                "type" => "unknown",
                "message" => "something bad happened"
            ], 500)
        ]);

        $this->petstoreRepository->get("1");
    }

    #[Test] public function should_can_update_pet_photo_without_exception(): void
    {
        Http::fake([
            "{$this->url}/1/uploadImage" => Http::response([
                "code" => 200,
                "type" => "unknown",
                "message" => "additionalMetadata: null\nFile uploaded to ./test.jpg, 172260 bytes"
            ], 200)
        ]);

        $photo = UploadedFile::fake()->image('test.jpg');
        $this->petstoreRepository->updatePhoto('1', $photo);
        $this->expectNotToPerformAssertions();
    }

    #[Test] public function throws_pet_not_found_on_update_photo(): void
    {
        $this->expectException(PetNotFoundException::class);
        $this->expectExceptionMessage('Pet not found');

        Http::fake([
            "{$this->url}/1/uploadImage" => Http::response([], 404)
        ]);

        $photo = UploadedFile::fake()->image('test.jpg');
        $this->petstoreRepository->updatePhoto('1', $photo);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->petstoreRepository = new PetstoreRepository();
        config(['petstore.resource_url' => 'http://petstore.test']);
        $this->url = config('petstore.resource_url');
    }

    private function getPet(): Pet
    {
        $category = new Category(null, 'categoryu-1');
        $tag = new Tag(null, 'tagu-1');

        return new Pet(
            'Pet Name',
            [],
            [$tag],
            PetStatus::SOLD->value,
            null,
            $category,
            null
        );
    }

    private function getPetsByStatusResponse(): PromiseInterface
    {
        return Http::response([
            [
                "id" => 1,
                "category" => [
                    "id" => 1,
                    "name" => "category-1",
                ],
                "name" => "dog-1",
                "photoUrls" => [
                    "url"
                ],
                "tags" => [
                    [
                        "id" => 1,
                        "name" => "tag-1",
                    ]
                ],
                "status" => "sold"
            ],
            [
                "id" => 2,
                "category" => [
                    "id" => 2,
                    "name" => "category-2",
                ],
                "name" => "dog-2",
                "photoUrls" => [
                    "url"
                ],
                "tags" => [
                    [
                        "id" => 2,
                        "name" => "tag-2",
                    ]
                ],
                "status" => "sold"
            ]
        ], 200);
    }

    private function getPetByStatusResponse(): PromiseInterface
    {
        return Http::response(
            [
                "id" => 1,
                "category" => [
                    "id" => 1,
                    "name" => "category-1",
                ],
                "name" => "dog-1",
                "photoUrls" => [
                    "url"
                ],
                "tags" => [
                    [
                        "id" => 1,
                        "name" => "tag-1",
                    ]
                ],
                "status" => "sold"
            ]
        );
    }
}
