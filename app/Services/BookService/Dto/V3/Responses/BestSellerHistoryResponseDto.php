<?php

declare(strict_types=1);

namespace App\Services\BookService\Dto\V3\Responses;

class BestSellerHistoryResponseDto
{
    public function __construct(
        public string $status,
        public ?string $copyright,
        public ?int $num_results = 0,
        /** @var array<BookDto> */
        public ?array $results = []
    ) {
    }

    /**
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            status: $data['status'],
            copyright: $data['copyright'],
            num_results: $data['num_results'],
            results: array_map(fn($book) => BookDto::fromArray($book), $data['results'])
        );
    }
}
