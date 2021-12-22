<?php

namespace mohamadmurad\LaravelTelegramReport\Bot;

use Illuminate\Support\Facades\Http;

class Telegram
{

    const BASE_BOT_URL = 'https://api.telegram.org/bot';
    /**
     * @var array|false|string
     *
     * Access token for bot
     */
    private $accessToken = null;

    /** @var string The name of the environment variable that contains the Telegram Bot API Access Token. */
    const BOT_TOKEN_ENV_NAME = 'telegram-report.token';

    /**
     * @throws \Exception
     */
    public function __construct($token)
    {
        $this->accessToken = $token ?? config(static::BOT_TOKEN_ENV_NAME);

        if (!$this->accessToken || !is_string($this->accessToken)) {
            throw new \Exception('Required "token" not supplied in config and could not find fallback environment variable ' . static::BOT_TOKEN_ENV_NAME . '');

        }

    }


    public function sendMessage(array $params)
    {
        return $this->post($params, 'sendmessage');
    }


    private function post($params, $method)
    {

        $params = $this->replyMarkupToString($params);

        return $this->sendRequest('POST', $method, $params);

    }


    private function sendRequest($requestMethod, $method, $params)
    {


        $url = $this->generateURL($method);
        if ($requestMethod == 'POST') {
            $response = Http::post($url, $params);
            return $response->json();
        }

        return [];
    }

    protected function replyMarkupToString(array $params)
    {
        if (isset($params['reply_markup'])) {
            $params['reply_markup'] = (string)$params['reply_markup'];
        }

        return $params;
    }

    private function generateURL($method)
    {

        return static::BASE_BOT_URL . $this->accessToken . '/' . $method;
    }

}
