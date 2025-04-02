<?php

declare(strict_types=1);

namespace App\Services\BookService\Dto\V3\Responses;

readonly class ReviewDto
{
    public function __construct(
        public ?string $book_review_link,
        public ?string $first_chapter_link,
        public ?string $sunday_review_link,
        public ?string $article_chapter_link,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            book_review_link: $data['book_review_link'] ?? null,
            first_chapter_link: $data['first_chapter_link'] ?? null,
            sunday_review_link: $data['sunday_review_link'] ?? null,
            article_chapter_link: $data['article_chapter_link'] ?? null,
        );
    }
}
