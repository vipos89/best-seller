<?php

declare(strict_types=1);

namespace app\Services\BookService\Contracts\Connectors;

interface BestSellerConnectorInterface
{
    /**
     * @param array<string, mixed> $queryParams
     * @return array<string, mixed>
     */
    public function getBestSellersHistory(array $queryParams = []): array;
}
