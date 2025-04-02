<?php

declare(strict_types=1);

namespace app\Services\BookService\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class NytBooksApiException extends Exception
{
    public function __construct(string $message = "Service Unavailable", int $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'error' => 'Service Unavailable'
        ], Response::HTTP_SERVICE_UNAVAILABLE);
    }
}
