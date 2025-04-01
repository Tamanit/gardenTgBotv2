<?php

use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;

Route::get('/review/sync', function () {
    return "Hello world!";
});

Route::get('/review/test', [ReviewController::class, 'sync']);

Route::get('test-tg', function () {
   echo Telegram::getMe();
});

Route::post('webhook/getMessage', function () {
    $update = Telegram::commandsHandler(true);

    // Commands handler method returns the Update object.
    // So you can further process $update object
    // to however you want.

    return 'ok';
})
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

Route::get('/', function () {
    echo env('TELEGRAM_BOT_SUB_KEY');
});
