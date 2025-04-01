<?php

declare(strict_types=1);

namespace tests\Unit\Dto\Requests;

use app\Services\BookService\Dto\V3\Requests\BestSellerRequestDto;
use PHPUnit\Framework\TestCase;

class BestSellerRequestDtoTest extends TestCase
{
    public function test_creates_dto_from_complete_array(): void
    {
        $data = [
            'author' => 'John Doe',
            'isbn' => ['1234567890'],
            'title' => 'Test Book',
            'offset' => 20
        ];

        $dto = BestSellerRequestDto::fromArray($data);

        $this->assertEquals('John Doe', $dto->author);
        $this->assertEquals(['1234567890'], $dto->isbn);
        $this->assertEquals('Test Book', $dto->title);
        $this->assertEquals(20, $dto->offset);
    }

    public function test_creates_dto_with_null_values(): void
    {
        $data = [];

        $dto = BestSellerRequestDto::fromArray($data);

        $this->assertNull($dto->author);
        $this->assertNull($dto->isbn);
        $this->assertNull($dto->title);
        $this->assertNull($dto->offset);
    }

    public function test_converts_to_query_params(): void
    {
        $dto = new BestSellerRequestDto(
            author: 'John Doe',
            isbn: ['1234567890', '0987654321'],
            title: 'Test Book',
            offset: 20
        );

        $params = $dto->toQueryParams();

        $this->assertEquals([
            'author' => 'John Doe',
            'isbn' => '1234567890;0987654321',
            'title' => 'Test Book',
            'offset' => 20
        ], $params);
    }

    public function test_converts_to_query_params_with_partial_data(): void
    {
        $dto = new BestSellerRequestDto(
            author: 'John Doe',
            isbn: null,
            title: null,
            offset: null
        );

        $params = $dto->toQueryParams();

        $this->assertEquals(['author' => 'John Doe'], $params);
    }


}