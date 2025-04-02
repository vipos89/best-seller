<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\BookService\BookService;
use App\Services\BookService\Connectors\NytBookConnectorV3;
use App\Services\BookService\Contracts\Connectors\BestSellerConnectorInterface;
use App\Services\BookService\Contracts\Service\BookServiceInterface;
use Illuminate\Support\ServiceProvider;

class BookServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(BookServiceInterface::class, BookService::class);
        $this->app->bind(BestSellerConnectorInterface::class, NytBookConnectorV3::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
