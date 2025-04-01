<?php

declare(strict_types=1);

namespace App\Services\BookService\Dto\V3;

abstract class AbstractNytRequestDto
{
    /**
     * @param array<string, mixed> $data
     * @return self
     */
    abstract public static function fromArray(array $data): self;

    /**
     * @return array<string, mixed>
     */
    abstract public function toQueryParams(): array;
}
