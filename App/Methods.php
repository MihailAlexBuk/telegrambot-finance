<?php


namespace app\App;


class Methods extends Bot
{
    public function sendMessage($chat_id, $text, $params = [])
    {
        $response = $this->query("sendMessage", array_merge([
            'text' => $text,
            'chat_id' => $chat_id,
            'parse_mode' => 'html',
        ], $params));
        return $response;
    }

    public function sendPhoto($chat_id, $photo, $params = [])
    {
        $response = $this->query("sendPhoto", array_merge([
            'chat_id' => $chat_id,
            'photo' => $photo,
        ], $params));
        return $response;
    }

    public function sendDocument($chat_id, $document, $params =[])
    {
        $response = $this->query("sendDocument", array_merge([
            'chat_id' => $chat_id,
            'document' => new \CURLFile(realpath("$document")),
        ], $params));
        return $response;
    }

    public function sendInlineKeyboards($chat_id, $message, $keyboard)
    {
        $response = $this->query("sendMessage", [
            'text' => $message,
            'chat_id' => $chat_id,
            'parse_mode' => 'html',
            'disable_web_page_preview' => false,
            'reply_markup' => json_encode([
                'resize_keyboard' => true,
                'inline_keyboard' => $keyboard,
            ]),
        ]);
        return $response;
    }

    public function answerCallbackQuery($message, $cq_id)
    {
        $response = $this->query("answerCallbackQuery", [
            'text' => $message,
            'callback_query_id' => $cq_id,
        ]);
        return $response;
    }

    public function editMessage($chat_id, $message, $message_id, $keyboard)
    {
        $response = $this->query("editMessageText", [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'text' => $message,
            'reply_markup' => json_encode([
                'resize_keyboard' => true,
                'inline_keyboard' => $keyboard,
            ]),
        ]);
        return $response;
    }

}