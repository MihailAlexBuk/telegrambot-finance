<?php


namespace app\App;

use app\App\db\Database;

class Response
{
    public Methods $methods;
    public Database $db;

    public function __construct()
    {
        $this->methods = new Methods();
        $this->db = new Database();
    }

    public function getResponse($message, $chatId, $message_id, $fname)
    {
        $message = mb_strtolower($message);
        if($message === '/start'){
            $this->methods->sendMessage($chatId, "Приветствую, $fname ! " .
                "\nЭто бот, помогающий вести домашнюю бухгалтерию. " .
                "\nДля получения справки отправьте команду /help или нажмите на соответствующую кнопку на клавиатуре ниже.",
                [
                    'reply_markup' => json_encode([
                        'resize_keyboard' => true,
                        'keyboard' => Keyboards::start_keyboard(),
                    ])
                ]);
        }

        elseif($message === 'категории расходов') {
            $categories = $this->db->getCategories(0);
            $this->methods->sendMessage($chatId, "<u>Категории доходов: </u>" . PHP_EOL .$categories,
            [
                'reply_markup' => json_encode([
                    'resize_keyboard' => true,
                    'keyboard' => Keyboards::start_keyboard(),
                ])
            ]);
        }

        elseif($message === 'категории доходов') {
            $categories = $this->db->getCategories(1);
            $this->methods->sendMessage($chatId, "<u>Категории доходов: </u>" . PHP_EOL .$categories,
                [
                    'reply_markup' => json_encode([
                        'resize_keyboard' => true,
                        'keyboard' => Keyboards::start_keyboard(),
                    ])
                ]);
        }

        elseif(preg_match("#^доход: (\d+) - ([\w\s]+)#u", $message, $matches)){
            $res = $this->db->addFinance(1, $matches[1], $matches[2]);
            $this->methods->sendMessage($chatId, $res,
                [
                    'reply_markup' => json_encode([
                        'resize_keyboard' => true,
                        'keyboard' => Keyboards::start_keyboard(),
                    ])
                ]);
        }

        elseif(preg_match("#^расход: (\d+) - ([\w\s]+)#u", $message, $matches)){
            $res = $this->db->addFinance(0, $matches[1], $matches[2]);
            $this->methods->sendMessage($chatId, $res,
                [
                    'reply_markup' => json_encode([
                        'resize_keyboard' => true,
                        'keyboard' => Keyboards::start_keyboard(),
                    ])
                ]);
        }

        elseif($message === 'расходы за сегодня'){
            $res = $this->db->getFinanceToday(0);
            $this->methods->sendMessage($chatId, $res,
                [
                    'reply_markup' => json_encode([
                        'resize_keyboard' => true,
                        'keyboard' => Keyboards::start_keyboard(),
                    ])
                ]);
        }

        elseif($message === 'доходы за сегодня'){
            $res = $this->db->getFinanceToday(1);
            $this->methods->sendMessage($chatId, $res,
                [
                    'reply_markup' => json_encode([
                        'resize_keyboard' => true,
                        'keyboard' => Keyboards::start_keyboard(),
                    ])
                ]);
        }

        elseif($message === 'итого за сегодня'){
            $res = $this->db->getFinanceToday(false);
            $this->methods->sendMessage($chatId, $res,
                [
                    'reply_markup' => json_encode([
                        'resize_keyboard' => true,
                        'keyboard' => Keyboards::start_keyboard(),
                    ])
                ]);
        }

        elseif($message === 'расходы за текущий месяц'){
            $res = $this->db->getFinanceMonth(0);
            $this->methods->sendMessage($chatId, $res,
                [
                    'reply_markup' => json_encode([
                        'resize_keyboard' => true,
                        'keyboard' => Keyboards::start_keyboard(),
                    ])
                ]);
        }

        elseif($message === 'доходы за текущий месяц'){
            $res = $this->db->getFinanceMonth(1);
            $this->methods->sendMessage($chatId, $res,
                [
                    'reply_markup' => json_encode([
                        'resize_keyboard' => true,
                        'keyboard' => Keyboards::start_keyboard(),
                    ])
                ]);
        }

        elseif($message === 'итого за текущий месяц'){
            $res = $this->db->getFinanceMonth(false);
            $this->methods->sendMessage($chatId, $res,
                [
                    'reply_markup' => json_encode([
                        'resize_keyboard' => true,
                        'keyboard' => Keyboards::start_keyboard(),
                    ])
                ]);
        }

        elseif($message === '/help' || $message === 'help'){
            $this->methods->sendMessage($chatId, "Для ведения учета просто введите свой доход или расход" .
                " в текущем формате: " .
                "\n<b>Тип: сумма - категория</b>" .
                "\n<u>Пример команд: </u>" .
                "\nДоход: 1000 - Зарплата" .
                "\nРасход: 1000 - Коммунальные услуги");

        }

        else{
            $this->methods->sendMessage($chatId, "Неверный формат команды!",
                [
                    'reply_markup' => json_encode([
                        'resize_keyboard' => true,
                        'keyboard' => Keyboards::start_keyboard(),
                    ])
                ]);
        }
    }

}
