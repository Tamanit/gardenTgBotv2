<?php

namespace App\Telegram\Commands;

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

        if ($subKey !== env('TELEGRAM_BOT_SUB_KEY')) {
            $this->replyWithMessage(['text' => 'Ключ не валиден, подписка невозможна!']);
        } else {
            $messageInfo = $this->getUpdate()
                ->getMessage()
                ->toArray();

            $chatId = $messageInfo['chat']['id'];

            TelegramChats::create([
                'chat_id' => $chatId,
            ]);

            $this->replyWithMessage(
                ['text' => "Вы успешно подписаны на обновления!"]
            );
        }
    }
}
