<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Services\BookService\Dto\V3\Responses\BestSellerHistoryResponseDto;
use App\Services\BookService\Dto\V3\Responses\BookDto;
use Illuminate\Http\Resources\Json\JsonResource;

class BestSellerHistoryResource extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array{
     * status: string,
     * num_results: int,
     * results: array<int, BookDto>
     * }
     */
    public function toArray($request): array
    {
        /** @var BestSellerHistoryResponseDto $dto */
        $dto = $this->resource;

        return [
            'status' => $dto->status,
            'num_results' => $dto->num_results ?? 0,
            'results' => $dto->results ?? [],
        ];
    }
}
