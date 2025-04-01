<?php

use App\Http\Controllers\Api\V1\BestSellerController;
use Illuminate\Support\Facades\Route;

Route::get('best-sellers', [BestSellerController::class, 'getBestSellers']);
