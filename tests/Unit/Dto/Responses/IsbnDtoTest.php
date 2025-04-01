<?php

declare(strict_types=1);

namespace tests\Unit\Dto\Responses;

use App\Services\BookService\Dto\V3\Responses\IsbnDto;
use PHPUnit\Framework\TestCase;

class IsbnDtoTest extends TestCase
{
    public function test_constructor_and_properties()
    {
        $isbn10 = '0123456789';
        $isbn13 = '978-0123456789';

        $dto = new IsbnDto($isbn10, $isbn13);

        $this->assertEquals($isbn10, $dto->isbn10);
        $this->assertEquals($isbn13, $dto->isbn13);
    }

    public function test_from_array_creation()
    {
        $data = [
            'isbn10' => '1234567890',
            'isbn13' => '978-1234567890'
        ];

        $dto = IsbnDto::fromArray($data);

        $this->assertInstanceOf(IsbnDto::class, $dto);
        $this->assertEquals($data['isbn10'], $dto->isbn10);
        $this->assertEquals($data['isbn13'], $dto->isbn13);
    }

    public function test_from_array_with_missing_keys_throws_error()
    {
        $this->expectException(\Error::class);

        $invalidData = [
            'isbn10' => '1234567890'
            // missing isbn13
        ];

        IsbnDto::fromArray($invalidData);
    }

    public function test_properties_are_readonly()
    {
        $dto = new IsbnDto('1234567890', '978-1234567890');

        $this->expectException(\Error::class);
        $dto->isbn10 = 'new-value';
    }

}