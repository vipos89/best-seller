<?php

declare(strict_types=1);

namespace app\Services\BookService\Contracts\Service;

use App\Services\BookService\Dto\V3\AbstractNytRequestDto;
use App\Services\BookService\Dto\V3\Responses\BestSellerHistoryResponseDto;

interface BookServiceInterface
{
    public function getBestSellersHistory(AbstractNytRequestDto $dto): BestSellerHistoryResponseDto;
}
