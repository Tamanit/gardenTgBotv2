<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramChats extends Model
{
    protected $fillable = [
        'chatId'
    ];

    public $timestamps = false;
}
