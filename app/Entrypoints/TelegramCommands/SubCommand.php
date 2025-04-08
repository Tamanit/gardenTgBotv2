<?php

namespace App\Entrypoints\TelegramCommands;

use App\Models\TelegramChats;
use App\Services\TelegramService;
use App\useCases\SubscribeForNotificationAboutNewReviewsUseCase;
use Telegram\Bot\Api;
use Telegram\Bot\Commands\Command;

class SubCommand extends Command
{
    protected string $name = 'sub';
    protected string $description = 'Команда для подписаня на рассылку обновлений по отзывам';
    protected string $pattern = '{subKey}';

    public function __construct(
        protected SubscribeForNotificationAboutNewReviewsUseCase $useCase,
    ) {

    }

    public function handle()
    {
        $subKey = $this->argument('subKey', $this->getUpdate()->getMessage()->from->subKey);
        $messageInfo = $this->getUpdate()
            ->getMessage()
            ->toArray();
        $chatId = $messageInfo['chat']['id'];

        $message = $this->useCase->use($subKey, $chatId);

        $this->replyWithMessage(
            ['text' => $message]
        );
    }
}
