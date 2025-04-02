<?php

declare(strict_types=1);

namespace tests\Unit;

use App\Services\BookService\Exceptions\NytBooksApiException;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class NytBooksApiExceptionTest extends TestCase
{
    public function test_exception_construction(): void
    {
        $message = 'Custom error message';
        $code = 500;

        $exception = new NytBooksApiException($message, $code);

        $this->assertEquals($message, $exception->getMessage());
        $this->assertEquals($code, $exception->getCode());
    }

    public function test_default_values_in_constructor(): void
    {
        $exception = new NytBooksApiException();

        $this->assertEquals('Service Unavailable', $exception->getMessage());
        $this->assertEquals(0, $exception->getCode());
    }

    public function test_render_method(): void
    {
        $exception = new NytBooksApiException();
        $response = $exception->render();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_SERVICE_UNAVAILABLE, $response->getStatusCode());
        $this->assertEquals(['error' => 'Service Unavailable'], $response->getData(true));
    }

    public function test_render_with_custom_message(): void
    {
        $customMessage = 'Custom Unavailable';
        $exception = new NytBooksApiException($customMessage);
        $response = $exception->render();

        $this->assertEquals(['error' => 'Service Unavailable'], $response->getData(true));
    }
}