<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;

class SubCommand extends Command
{
    protected string $name = 'sub';
    protected string $description = 'Команда для подписаня на рассылку обновлений по отзывам';
    protected string $pattern = '{subKey}';

    public function handle()
    {
        $subKey = $this->argument('subKey', $this->getUpdate()->getMessage()->from->subKey);

        $update = $this->getUpdate();

        $this->replyWithMessage(
            ['text' => "{$subKey}" . json_encode($update->getMessage()->toArray())]
        );
//        if ($subKey !== env('TELEGRAM_BOT_SUB_KEY')) {
//            $this->replyWithMessage(['text' => 'Ключ не валиден, подписка невозможна!']);
//            return false;
//        }


    }
}
