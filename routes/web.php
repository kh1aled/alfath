<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

require __DIR__ . '/auth.php';
