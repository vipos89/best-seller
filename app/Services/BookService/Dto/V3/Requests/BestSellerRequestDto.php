<?php

declare(strict_types=1);

namespace app\Services\BookService\Dto\V3\Requests;

use App\Services\BookService\Dto\V3\AbstractNytRequestDto;

class BestSellerRequestDto extends AbstractNytRequestDto
{
    public function __construct(
        public readonly ?string $author,
        /** @var array<string> */
        public readonly ?array $isbn,
        public readonly ?string $title,
        public readonly ?int $offset
    ) {
    }

    /**
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            author: $data['author'] ?? null,
            isbn: $data['isbn'] ?? null,
            title: $data['title'] ?? null,
            offset: isset($data['offset']) ? (int)$data['offset'] : null
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toQueryParams(): array
    {
        $params = [];

        if ($this->author !== null) {
            $params['author'] = $this->author;
        }

        if (!empty($this->isbn)) {
            $params['isbn'] = implode(';', $this->isbn);
        }

        if ($this->title !== null) {
            $params['title'] = $this->title;
        }

        if ($this->offset !== null) {
            $params['offset'] = $this->offset;
        }

        return $params;
    }
}
