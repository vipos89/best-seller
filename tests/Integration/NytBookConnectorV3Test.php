<?php

declare(strict_types=1);

namespace tests\Integration;

use app\Services\BookService\Exceptions\NytBooksApiException;
use Illuminate\Http\Client\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class NytBookConnectorV3Test extends TestCase
{
    private NytBookConnectorV3 $connector;

    protected function setUp(): void
    {
        parent::setUp();
        $this->connector = new NytBookConnectorV3();
    }

    public function test_makes_correct_api_request(): void
    {
        Http::fake([
            '*' => Http::response([
                'status' => 'OK',
                'results' => []
            ], 200)
        ]);

        $queryParams = [
            'author' => 'John Doe',
            'isbn' => '1234567890'
        ];

        $this->connector->getBestSellersHistory($queryParams);

        Http::assertSent(function (Request $request) use ($queryParams) {
            return $request->url() === config('nytBooks.base_url') . '/v3/lists/best-sellers/history.json' &&
                $request->hasHeader('Accept', 'application/json') &&
                $request->query('author') === $queryParams['author'] &&
                $request->query('isbn') === $queryParams['isbn'] &&
                $request->query('api-key') === config('nytBooks.api_key');
        });
    }

    public function test_handles_api_error_response(): void
    {
        Http::fake([
            '*' => Http::response([
                'fault' => [
                    'faultstring' => 'API Key Invalid'
                ]
            ], 401)
        ]);

        $this->expectException(NytBooksApiException::class);
        $this->expectExceptionCode(401);

        $this->connector->getBestSellersHistory();
    }

    public function test_handles_connection_timeout(): void
    {
        Http::fake([
            '*' => Http::response(null, JsonResponse::HTTP_GATEWAY_TIMEOUT)
        ]);

        $this->expectException(NytBooksApiException::class);
        $this->expectExceptionMessage('Failed to connect to NYT Books API.');

        $this->connector->getBestSellersHistory();
    }

    public function test_retries_on_failure(): void
    {
        $responses = collect([
            Http::response(null, JsonResponse::HTTP_INTERNAL_SERVER_ERROR),
            Http::response(null, JsonResponse::HTTP_INTERNAL_SERVER_ERROR),
            Http::response(['status' => 'OK', 'results' => []], JsonResponse::HTTP_OK)
        ]);

        Http::fake(fn () => $responses->shift());

        $result = $this->connector->getBestSellersHistory();

        Http::assertSentCount(3);
        $this->assertEquals('OK', $result['status']);
    }


}