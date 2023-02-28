<?php

namespace app\App;

class Bot
{
    private static $updateId;

    public function query($method = 'getMe', $params = [])
    {
        $url = "https://api.telegram.org/bot".$_ENV['TELEGRAM_BOT_TOKEN']."/".$method;

        $ch = curl_init();
        $ch_post = [
            CURLOPT_HTTPHEADER => array(
                'Content-Type:multipart/form-data'
            ),
            CURLOPT_URL => $url,
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_POSTFIELDS => $params
        ];
        curl_setopt_array($ch, $ch_post);
        $res = curl_exec($ch);
        if(curl_error($ch)){
            var_dump($ch);
        }else{
            return json_decode($res, 1);
        }
    }

    public static function getUpdates()
    {
        $response = self::query('getUpdates', [
            'offset' => self::$updateId + 1
        ]);

        if(!empty($response['result'])){
            self::$updateId = $response['result'][count($response['result']) - 1]['update_id'];
        }
        return $response['result'];
    }

    public static function getFile($data)
    {
        $file_id = $data['message']['document']['file_id'];

        $response = self::query('getFile', [
            'file_id' => $file_id
        ]);
        if ($response['ok']) {
            $src  = 'https://api.telegram.org/file/bot' . $_ENV['TELEGRAM_BOT_TOKEN'] . '/' . $response['result']['file_path'];
            $dest = __DIR__ . '/user_files/' . basename($src);
            copy($src, $dest);
        }
        return basename($src) ?? '';
    }




}