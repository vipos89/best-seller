<?php

declare(strict_types=1);

namespace tests\Feature;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BestSellerControllerFeatureTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Http::fake([
            '*' => Http::response([
                'status' => 'OK',
                'copyright'=> '<copyright>',
                'num_results' => 1,
                'results' => [
                    [
                        'age_group' => 'Test age group',
                        'title' => 'Test Book',
                        'description' => 'Test description',
                        'contributor' => 'Test contributor',
                        'contributor_note' => 'Test contributor note',
                        'author' => 'John Doe',
                        'isbn' => ['1234567890'],
                        'publisher' => 'Test publisher',
                    ]
                ]
            ], JsonResponse::HTTP_OK)
        ]);
    }

    public function test_valid_request(): void
    {
        $response = $this->getJson('/api/v1/best-sellers?author=John+Doe&offset=20');

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonStructure([
                'status',
                'num_results',
                'results' => [
                    '*' => [
                        'age_group',
                        'title',
                        'description',
                        'contributor',
                        'contributor_note',
                        'author',
                        'publisher',
                    ]
                ]
            ]);
    }

    public function test_invalid_offset(): void
    {
        $response = $this->getJson('/api/v1/best-sellers?offset=15');

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['offset']);
    }

    public function test_invalid_isbn(): void
    {
        $response = $this->getJson('/api/v1/best-sellers?isbn[]=invalid-isbn');

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['isbn.0']);
    }

    public function test_handles_empty_parameters(): void
    {
        $response = $this->getJson('/api/v1/best-sellers');

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonStructure([
                'status',
                'results'
            ]);
    }
}