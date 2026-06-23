<?php

use App\Http\Controllers\Api\RpcController;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'Inventory RPC Server',
    ]);
});

Route::post('/rpc', [RpcController::class, 'handle']);