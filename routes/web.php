<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'name' => 'Capital Immo Group API',
        'version' => '1.0.0',
        'status' => 'running',
        'documentation' => '/api/documentation',
    ]);
});
