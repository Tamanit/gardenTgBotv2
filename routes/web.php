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
