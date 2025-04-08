<?php

namespace App\Entrypoints\TelegramCommands;

use App\Models\TelegramChats;
use Telegram\Bot\Commands\Command;

class SubCommand extends Command
{
    protected string $name = 'sub';
    protected string $description = 'Команда для подписаня на рассылку обновлений по отзывам';
    protected string $pattern = '{subKey}';

    public function handle()
    {
        $subKey = $this->argument('subKey', $this->getUpdate()->getMessage()->from->subKey);
        $messageInfo = $this->getUpdate()
            ->getMessage()
            ->toArray();
        $chatId = $messageInfo['chat']['id'];

        if ($subKey !== env('TELEGRAM_BOT_SUB_KEY')) {
            $this->replyWithMessage([
                'text'=> 'Ключ не валиден, подписка невозможна!'
            ]);
        } elseif(TelegramChats::where('chatId', $chatId)->exists()) {
            $this->replyWithMessage([
                'text' => 'Вы кже подписаны!'
            ]);
        } else {
            TelegramChats::create([
                'chatId' => $chatId,
            ]);

            $this->replyWithMessage([
                'text' => 'Вы подписались'
            ]);
        }
    }
}
