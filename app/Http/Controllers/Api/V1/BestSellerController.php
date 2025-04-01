<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\BestSellerRequest;
use App\Services\BookService\Contracts\Service\BookServiceInterface;
use App\Services\BookService\Dto\V3\Requests\BestSellerRequestDto;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class BestSellerController extends Controller
{
    public function __construct(private readonly BookServiceInterface $bookService)
    {
    }

    public function getBestSellers(BestSellerRequest $request): JsonResponse
    {
        $dto = BestSellerRequestDto::fromArray($request->all());

        $hashKey = sprintf('best_sellers_%s', md5(json_encode($dto, JSON_FORCE_OBJECT)));

        $data = Cache::remember($hashKey, 600, function ()use ($dto){
            return $this->bookService->getBestSellersHistory($dto);
        });

        return response()->json($data);
    }
}
