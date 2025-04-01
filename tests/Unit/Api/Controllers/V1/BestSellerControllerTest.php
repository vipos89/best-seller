<?php

namespace Tests\Unit\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\BestSellerController;
use App\Http\Requests\V1\BestSellerRequest;
use App\Services\BookService\Contracts\Service\BookServiceInterface;
use App\Services\BookService\Dto\V3\Requests\BestSellerRequestDto;
use App\Services\BookService\Dto\V3\Responses\BestSellerHistoryResponseDto;
use Illuminate\Http\JsonResponse;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class BestSellerControllerTest extends TestCase
{
    private MockInterface $bookServiceMock;
    private BestSellerController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bookServiceMock = Mockery::mock(BookServiceInterface::class);
        $this->controller = new BestSellerController($this->bookServiceMock);
    }


    /**
     *
     * @return void
     */
    public function test_get_best_sellers_success(): void
    {
        $requestData = [
            'author' => 'J.K. Rowling',
            'isbn' => ['9780439708180'],
            'title' => 'Harry Potter',
            'offset' => 20,
        ];

        $request = new BestSellerRequest($requestData);

        $responseDto = new BestSellerHistoryResponseDto(
            status: 'OK',
            copyright: 'Copyright (c) 2025 The New York Times Company. All Rights Reserved.',
            num_results: 1,
            results: [['mocked_response']]
        );

        $this->bookServiceMock
            ->shouldReceive('getBestSellersHistory')
            ->once()
            ->with(Mockery::on(fn($arg) => $arg instanceof BestSellerRequestDto))
            ->andReturn($responseDto);

        /** @var JsonResponse $response */
        $response = $this->controller->getBestSellers($request);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->status());
        $this->assertEquals([
            'status' => 'OK',
            'copyright' => 'Copyright (c) 2025 The New York Times Company. All Rights Reserved.',
            'num_results' => 1,
            'results' => [['mocked_response']],
        ], $response->getData(true));
    }

    public function test_get_best_sellers_service_exception(): void
    {
        $request = new BestSellerRequest(['title' => 'The Hobbit']);

        $this->bookServiceMock
            ->shouldReceive('getBestSellersHistory')
            ->once()
            ->andThrow(new \Exception('Service error'));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Service error');

        /** @var JsonResponse $response */
        $response = $this->controller->getBestSellers($request);

        $this->assertEquals(JsonResponse::HTTP_SERVICE_UNAVAILABLE, $response->status());
        $this->assertEquals(['error' => 'Service Unavailable'], $response->getData(true));
    }
}
