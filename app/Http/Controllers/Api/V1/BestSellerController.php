<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\BestSellerRequest;
use App\Services\BookService\Contracts\Service\BookServiceInterface;
use App\Services\BookService\Dto\V3\Requests\BestSellerRequestDto;
use Illuminate\Http\JsonResponse;

class BestSellerController extends Controller
{
    public function __construct(private readonly BookServiceInterface $bookService)
    {
    }

    public function getBestSellers(BestSellerRequest $request): JsonResponse
    {
        $dto = BestSellerRequestDto::fromArray($request->all());
        $data = $this->bookService->getBestSellersHistory($dto);

        return response()->json($data);
    }
}
