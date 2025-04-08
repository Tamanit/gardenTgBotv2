<?php

namespace App\Entrypoints\TelegramCommands;

use Telegram\Bot\Commands\Command;

class TestCommand extends Command
{
    protected string $name = 'test';
    protected string $description = 'Команда для проверки работоспособности бота';

    /**
     * @inheritDoc
     */
    public function handle()
    {
        $this->replyWithMessage(['text' => 'Я работаю!']);
    }
}
