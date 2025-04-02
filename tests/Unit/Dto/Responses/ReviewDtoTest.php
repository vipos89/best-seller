<?php

declare(strict_types=1);

namespace tests\Unit\Dto\Responses;

use App\Services\BookService\Dto\V3\Responses\ReviewDto;
use PHPUnit\Framework\TestCase;

class ReviewDtoTest extends TestCase
{
    private array $fullData = [
        'book_review_link' => 'https://review.com',
        'first_chapter_link' => 'https://chapter.com',
        'sunday_review_link' => 'https://sunday.com',
        'article_chapter_link' => 'https://article.com'
    ];

    public function test_creation(): void
    {
        $dto = new ReviewDto(...array_values($this->fullData));
        $this->assertInstanceOf(ReviewDto::class, $dto);
    }

    public function test_from_array(): void
    {
        $dto = ReviewDto::fromArray($this->fullData);
        foreach ($this->fullData as $key => $value) {
            $this->assertEquals($value, $dto->$key);
        }
    }

    public function test_nullable_fields(): void
    {
        $dto = ReviewDto::fromArray([]);
        $this->assertNull($dto->book_review_link);
    }

    public function test_readonly(): void
    {
        $dto = ReviewDto::fromArray($this->fullData);
        $this->expectException(\Error::class);
        $dto->book_review_link = 'new';
    }

    public function test_type_safety(): void
    {
        $this->expectException(\TypeError::class);
        ReviewDto::fromArray(['book_review_link' => 123]);
    }
}