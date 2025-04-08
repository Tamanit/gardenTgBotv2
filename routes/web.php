<?php

use App\Entrypoints\HttpControllers\ReviewController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;

Route::get('/', function () {
    return "Garden review bot!";
});


Route::post('/webhook/getMessage', function () {
    $update = Telegram::commandsHandler(true);

    // Commands handler method returns the Update object.
    // So you can further process $update object
    // to however you want.

    return 'ok';
})->withoutMiddleware([VerifyCsrfToken::class]);

Route::get('/review/test', [ReviewController::class, 'sync']);

Route::get('/bot', function () {
    return "<pre>" . Telegram::bot()->getMe() . "</pre>";
});
