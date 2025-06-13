<?php

namespace App\Services;

use App\Notifications\SlackNotification;
use Illuminate\Notifications\Notifiable;

/**
 * スラック通知サービスクラス
 *
 * @see https://qiita.com/freeneer/items/aa82a0c49842b7c379ec
 */
class SlackService
{
    use Notifiable;

    /**
     * 通知処理
     */
    public function send(?string $message = null): void
    {
        $this->notify(new SlackNotification($message));
    }

    /**
     * Slack通知用URLを指定する
     */
    protected function routeNotificationForSlack(): string
    {
        return config('slack.url');
    }

    /**
     * Get the value of the notifiable's primary key.
     *
     * @return mixed
     */
    public function getKey()
    {
        //
    }
}
