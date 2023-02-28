<?php

namespace app\App;

class App
{
    private Response $response;

    public function __construct()
    {
        $this->response = new Response();
    }

    public function start()
    {
        while (true)
        {
            sleep(2);
            $updates = Bot::getUpdates();

            foreach ($updates as $update)
            {
//                file_put_contents(__DIR__.'/logs.txt', print_r($update, 1), FILE_APPEND);

                $message = $update['message']['text'] ?? '';
                $message_id = $update['message']['message_id'] ?? '';
                $chat_id = $update['message']['chat']['id'] ?? '';
                $fname = $update['message']['chat']['first_name'] ?? '';

                $this->response->getResponse($message, $chat_id, $message_id, $fname);
            }
        }
    }

}