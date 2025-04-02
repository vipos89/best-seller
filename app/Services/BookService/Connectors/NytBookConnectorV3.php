<?php

declare(strict_types=1);

namespace app\Services\BookService\Connectors;

use App\Services\BookService\Contracts\Connectors\BestSellerConnectorInterface;
use App\Services\BookService\Exceptions\NytBooksApiException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NytBookConnectorV3 implements BestSellerConnectorInterface
{
    private PendingRequest $client;
    public const string CURRENT_VERSION = 'v3';
    public const string BEST_SELLERS_HISTORY_URL = 'lists/best-sellers/history.json';

    public function __construct()
    {
        $this->client = Http::withOptions([
            'base_uri' => sprintf("%s/%s/", config('nytBooks.base_url'), self::CURRENT_VERSION),
            'timeout' => 5,
            'verify' => false
        ])
            ->withQueryParameters(['api-key' => config('nytBooks.api_key')])
            ->retry(3, 1000)
            ->acceptJson();
    }

    /**
     * Get bestsellers history.
     *
     * @param array<string, mixed> $queryParams
     * @return array<string, mixed>
     * @throws NytBooksApiException
     */
    public function getBestSellersHistory(array $queryParams = []): array
    {
        return $this->sendRequest(self::BEST_SELLERS_HISTORY_URL, $queryParams);
    }

    /**
     * Send request to API NYT Books.
     *
     * @param string $endpoint
     * @param array<string, mixed> $queryParams
     * @return array<string, mixed>
     * @throws NytBooksApiException
     *
     */
    private function sendRequest(string $endpoint, array $queryParams = []): array
    {
        try {
            $response = $this->client->get($endpoint, $queryParams);

            if ($response->failed()) {
                throw new NytBooksApiException('NYT BOOKS API Connection Error', $response->status());
            }
        } catch (ConnectionException $e) {
            throw new NytBooksApiException('Failed to connect to NYT Books API.', $e->getCode());
        } catch (\Throwable $e) {
            Log::error('NYT BOOKS Exception: ', [
                'endpoint' => $endpoint,
                'status' => $e->getCode(),
                'params' => $queryParams,
                'message' => $e->getMessage(),
            ]);

            throw new NytBooksApiException('NYT BOOKS API Exception', $e->getCode());
        }

        return $response->json();
    }
}
