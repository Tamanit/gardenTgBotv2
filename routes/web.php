<?php

use App\Entrypoints\HttpControllers\ReviewController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;

Route::get('/', function () {
    return "Garden review bot!";
});


Route::post('/webhook/getMessage', function () {
    $updates = Telegram::getWebhookUpdate();

    return 'ok';
})
    ->withoutMiddleware([VerifyCsrfToken::class]);

Route::get('/review/test', [ReviewController::class, 'sync']);
