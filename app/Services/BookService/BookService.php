<?php

declare(strict_types=1);

namespace app\Services\BookService;

use App\Services\BookService\Contracts\Connectors\BestSellerConnectorInterface;
use App\Services\BookService\Contracts\Service\BookServiceInterface;
use App\Services\BookService\Dto\V3\AbstractNytRequestDto;
use App\Services\BookService\Dto\V3\Responses\BestSellerHistoryResponseDto;

readonly class BookService implements BookServiceInterface
{
    public function __construct(private BestSellerConnectorInterface $bestSellerConnector)
    {
    }

    /**
     * @param AbstractNytRequestDto $dto
     * @return BestSellerHistoryResponseDto
     */
    public function getBestSellersHistory(AbstractNytRequestDto $dto): BestSellerHistoryResponseDto
    {
        $result = $this->bestSellerConnector->getBestSellersHistory($dto->toQueryParams());

        return BestSellerHistoryResponseDto::fromArray($result);
    }
}
