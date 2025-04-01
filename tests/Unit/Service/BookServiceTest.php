<?php

declare(strict_types=1);

namespace tests\Unit\Service;


use app\Services\BookService\BookService;
use app\Services\BookService\Contracts\Connectors\BestSellerConnectorInterface;
use app\Services\BookService\Dto\V3\Requests\BestSellerRequestDto;
use App\Services\BookService\Dto\V3\Responses\BestSellerHistoryResponseDto;
use Mockery;
use PHPUnit\Framework\TestCase;

class BookServiceTest extends TestCase
{
    private BookService $bookService;
    private BestSellerConnectorInterface $connector;

    protected function setUp(): void
    {
        parent::setUp();
        $this->connector = Mockery::mock(BestSellerConnectorInterface::class);
        $this->bookService = new BookService($this->connector);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_gets_best_sellers_history(): void
    {
        $dto = new BestSellerRequestDto(
            author: 'John Doe',
            isbn: ['1234567890'],
            title: 'Test Book',
            offset: 20
        );

        $mockResponse = [
            'status' => 'OK',
            'copyright' => 'Copyright (c) 2025 The New York Times Company. All Rights Reserved.',
            'num_results' => 100,
            'description' => 'Test description',
            'results' => [
                [
                    'title' => 'Test Book',
                    'author' => 'John Doe',
                    'description' => 'Test description',
                    'isbn' => ['1234567890']
                ]
            ]
        ];

        $this->connector
            ->shouldReceive('getBestSellersHistory')
            ->once()
            ->with($dto->toQueryParams())
            ->andReturn($mockResponse);

        $result = $this->bookService->getBestSellersHistory($dto);

        $this->assertInstanceOf(BestSellerHistoryResponseDto::class, $result);
    }
}