<?php

declare(strict_types=1);

namespace App\Services\BookService\Dto\V3\Responses;

readonly class IsbnDto
{
    public function __construct(
        public string $isbn10,
        public string $isbn13,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            isbn10: $data['isbn10'],
            isbn13: $data['isbn13'],
        );
    }
}
