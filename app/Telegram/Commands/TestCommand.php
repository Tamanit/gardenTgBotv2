<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;

class TestCommand extends Command
{
    protected string $name = 'test';
    protected string $description = 'Тестовая команда';

    public function handle()
    {
        $this->replyWithMessage([
            'text' => 'Hello world!',
        ]);
    }
}
