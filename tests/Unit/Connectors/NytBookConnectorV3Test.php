<?php

namespace tests\Unit\Connectors;

use App\Services\BookService\Connectors\NytBookConnectorV3;
use App\Services\BookService\Exceptions\NytBooksApiException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class NytBookConnectorV3Test extends TestCase
{
    private NytBookConnectorV3 $connector;

    protected function setUp(): void
    {
        parent::setUp();
        $this->connector = new NytBookConnectorV3();
    }

    public function testGetBestSellersHistory()
    {
        Http::fake([
            '*history.json*' => Http::response([
                'status' => 'OK',
                'results' => [
                    [
                        'title' => '#GIRLBOSS',
                        'author' => 'Sophia Amoruso',
                    ],
                ],
            ], JsonResponse::HTTP_OK),
        ]);

        $connector = new NytBookConnectorV3();
        $response = $connector->getBestSellersHistory();

        $this->assertIsArray($response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('results', $response);
        $this->assertCount(1, $response['results']);
        $this->assertEquals('#GIRLBOSS', $response['results'][0]['title']);
        $this->assertEquals('Sophia Amoruso', $response['results'][0]['author']);
    }

    public function testGetBestSellersHistoryConnectionError()
    {
        // Mock a connection error
        Http::fake([
            '*history.json*' => Http::response([], JsonResponse::HTTP_INTERNAL_SERVER_ERROR),
        ]);

        Log::shouldReceive('error')
            ->once();


        $this->expectExceptionCode(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);


        $connector = new NytBookConnectorV3();
        $connector->getBestSellersHistory();
    }

    public function testGetBestSellersHistoryNotFound()
    {
        // Mock a 404 error
        Http::fake([
            '*history.json*' => Http::response([], JsonResponse::HTTP_NOT_FOUND),
        ]);

        $this->expectException(NytBooksApiException::class);
        $this->expectExceptionCode(JsonResponse::HTTP_NOT_FOUND);

        $connector = new NytBookConnectorV3();
        $connector->getBestSellersHistory();
    }

    public function testGetBestSellersHistoryTimeout()
    {
        // Mock a timeout error
        Http::fake([
            '*history.json*' => Http::response([], JsonResponse::HTTP_INTERNAL_SERVER_ERROR),
        ]);

        $this->expectException(NytBooksApiException::class);
        $this->expectExceptionCode(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);

        $connector = new NytBookConnectorV3();
        $connector->getBestSellersHistory();
    }

    public function testGetBestSellersHistoryWithQueryParams()
    {
        Http::fake([
            '*history.json*' => Http::response([
                'status' => 'OK',
                'results' => [
                    [
                        'title' => '#GIRLBOSS',
                        'author' => 'Sophia Amoruso',
                    ],
                ],
            ], 200),
        ]);

        $queryParams = ['some_param' => 'value'];
        $connector = new NytBookConnectorV3();
        $response = $connector->getBestSellersHistory($queryParams);


        $this->assertIsArray($response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('results', $response);
        $this->assertCount(1, $response['results']);
        $this->assertEquals('#GIRLBOSS', $response['results'][0]['title']);
        $this->assertEquals('Sophia Amoruso', $response['results'][0]['author']);
    }

    public function testLoggingOnError()
    {
        Http::fake([
            '*history.json*' => Http::response([], JsonResponse::HTTP_INTERNAL_SERVER_ERROR),
        ]);

        Log::shouldReceive('error')
            ->once()
            ->withArgs(function ($message, $data) {
                return strpos($message, 'NYT BOOKS Exception: ') === 0
                    && is_array($data)
                    && array_key_exists('endpoint', $data)
                    && array_key_exists('status', $data)
                    && array_key_exists('params', $data)
                    && array_key_exists('message', $data);
            });

        $this->expectException(NytBooksApiException::class);

        $connector = new NytBookConnectorV3();
        $connector->getBestSellersHistory();
    }

    public function testConstants()
    {
        $this->assertEquals('v3', NytBookConnectorV3::CURRENT_VERSION);
        $this->assertEquals('lists/best-sellers/history.json', NytBookConnectorV3::BEST_SELLERS_HISTORY_URL);
    }
}