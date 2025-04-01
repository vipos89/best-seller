<?php

declare(strict_types=1);

namespace App\Services\BookService\Dto\V3\Responses;

readonly class BookDto
{
    public function __construct(
        public string $title,
        public ?string $description,
        public ?string $contributor,
        public ?string $author,
        public ?string $contributor_note,
        public ?float $price,
        public ?string $age_group,
        public ?string $publisher,
        /** @var array<IsbnDto> */
        public array $isbns = [],
        /** @var array<RankHistoryDto> */
        public array $ranks_history = [],
        /** @var array<ReviewDto> */
        public array $reviews = []
    ) {
    }

    /**
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            description: $data['description'],
            contributor: $data['contributor'],
            author: $data['author'],
            contributor_note: $data['contributor_note'],
            price: isset($data['price']) ? (float)$data['price'] : null,
            age_group: $data['age_group'],
            publisher: $data['publisher'],
            isbns: array_map(fn($isbn) => IsbnDto::fromArray($isbn), $data['isbns'] ?? []),
            ranks_history: array_map(fn($rank) => RankHistoryDto::fromArray($rank), $data['ranks_history'] ?? []),
            reviews: array_map(fn($review) => ReviewDto::fromArray($review), $data['reviews'] ?? [])
        );
    }
}
