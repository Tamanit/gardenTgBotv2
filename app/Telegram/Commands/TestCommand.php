<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;

class TestCommand extends Command
{
    protected string $name = 'test';
    protected string $description = 'Start Command to get you started';

    public function handle()
    {
        $this->replyWithMessage([
            'text' => 'Hey, there! Welcome to our bot!',
        ]);
    }
}
