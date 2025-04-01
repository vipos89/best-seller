<?php

declare(strict_types=1);

namespace App\Services\BookService\Dto\V3\Responses;

readonly class RankHistoryDto
{
    public function __construct(
        public string $primary_isbn10,
        public string $primary_isbn13,
        public int $rank,
        public string $list_name,
        public string $display_name,
        public string $published_date,
        public string $bestsellers_date,
        public int $weeks_on_list,
        public ?int $ranks_last_week,
        public int $asterisk,
        public int $dagger
    ) {
    }

    /**
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            primary_isbn10: $data['primary_isbn10'],
            primary_isbn13: $data['primary_isbn13'],
            rank: $data['rank'],
            list_name: $data['list_name'],
            display_name: $data['display_name'],
            published_date: $data['published_date'],
            bestsellers_date: $data['bestsellers_date'],
            weeks_on_list: $data['weeks_on_list'],
            ranks_last_week: $data['ranks_last_week'] ?? null,
            asterisk: $data['asterisk'],
            dagger: $data['dagger']
        );
    }
}
