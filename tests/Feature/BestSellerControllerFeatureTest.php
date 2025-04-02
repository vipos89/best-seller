<?php

declare(strict_types=1);

namespace tests\Feature;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class BestSellerControllerFeatureTest extends TestCase
{
    private const string BEST_SELLERS_URL = '/api/v1/best-sellers';

    protected function setUp(): void
    {
        parent::setUp();

        Http::fake([
            '*' => Http::response([
                'status' => 'OK',
                'copyright' => '<copyright>',
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
            ], Response::HTTP_OK)
        ]);
    }

    public function test_valid_request(): void
    {
        $response = $this->getJson(
            sprintf('%s?author=John+Doe&offset=20', self::BEST_SELLERS_URL)
        );

        $response->assertStatus(Response::HTTP_OK)
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
        $response = $this->getJson(
            sprintf('%s?offset=15', self::BEST_SELLERS_URL)
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['offset']);
    }

    public function test_invalid_isbn(): void
    {
        $response = $this->getJson(
            sprintf('%s?isbn[]=invalid-isbn', self::BEST_SELLERS_URL)
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['isbn.0']);
    }

    public function test_handles_empty_parameters(): void
    {
        $response = $this->getJson(self::BEST_SELLERS_URL);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'status',
                'results'
            ]);
    }
}