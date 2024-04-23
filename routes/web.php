<?php

use App\Module\Checkout\Controller\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'));

Route::get('/checkout/{skus}', [CheckoutController::class, 'checkout']);
