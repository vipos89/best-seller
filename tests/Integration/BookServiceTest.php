<?php

declare(strict_types=1);

namespace tests\Integration;

use app\Services\BookService\BookService;
use app\Services\BookService\Connectors\NytBookConnectorV3;
use app\Services\BookService\Dto\V3\Requests\BestSellerRequestDto;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use tests\TestCase;

class BookServiceTest extends TestCase
{

    public function test_get_best_sellers_history(): void
    {
        Http::fake([
            'books/*' => Http::response(['data' => 'response'], Response::HTTP_OK),
        ]);

        $dto = new BestSellerRequestDto(
            author: 'J.K. Rowling',
            isbn: ['1234567890'],
            title: 'Harry Potter',
            offset: 0
        );

        $service = new BookService(new NytBookConnectorV3());
        $response = $service->getBestSellersHistory($dto);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('response', $response['data']);
    }
}