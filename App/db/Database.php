<?php


namespace app\App\db;

class Database
{
    public \PDO $pdo;

    public function __construct()
    {
        $dsn = $_ENV['DB_DSN'] ?? '';
        $user = $_ENV['DB_USER'] ?? '';
        $password = $_ENV['DB_PASSWORD'] ?? '';
        $opt = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        ];
        $this->pdo = new \PDO($dsn, $user, $password, $opt);
    }

    public function getCategories($type = false)
    {
        if(false === $type){
            $statement = $this->pdo->prepare("SELECT * FROM finance_categories");
            $statement->execute();
        }else {
            $statement = $this->pdo->prepare("SELECT * FROM finance_categories WHERE type = ?");
            $statement->execute([$type]);
        }
        $html = '';
        foreach ($statement->fetchAll() as $item){
            $html .= $item['title'] . PHP_EOL;
        }
        return $html;
    }

    public function addFinance($type, $amount, $category)
    {
        $statement = $this->pdo->prepare("SELECT id FROM finance_categories WHERE type = ? AND title = ?");
        $statement->execute([$type, $category]);
        $data = $statement->fetch();
        if(!isset($data['id'])){
            return "Категория не найдена";
        }
        $statement = $this->pdo->prepare("INSERT INTO finance (amount, category, type) values (?,?,?)");
        if($statement->execute([$amount, $data['id'], $type])){
            return 'Запись добавлена';
        }else{
            return 'Произошла ошибка';
        }
    }

    public function getFinanceToday($type)
    {
        if($type === 1){
            $html = "<u><b>Доходы за сегодня: </b></u>" . PHP_EOL;
        }elseif($type === 0){
            $html = "<u><b>Расходы за сегодня: </b></u>" . PHP_EOL;
        }else{
            $html = "<u><b>Итого за сегодня: </b></u>" . PHP_EOL;
        }

        if(false === $type){
            $statement = $this->pdo->prepare("SELECT SUM(amount) as amount, type FROM finance WHERE 
DATE(date_add) = DATE(?) GROUP BY type ORDER BY type DESC");
            $statement->execute([date('Y-m-d')]);
            foreach ($statement->fetchAll() as $item) {
                if($item['type']){
                    $plus = $item['amount'];
                }else{
                    $minus = $item['amount'];
                }
            }
            $plus = $plus ?? 0;
            $minus = $minus ?? 0;
            $html .= $plus - $minus;
        }else{
            $statement = $this->pdo->prepare("SELECT SUM(f.amount) as amount, f.type, fc.title FROM
 finance f LEFT JOIN finance_categories fc ON f.category = fc.id WHERE f.type = ? AND DATE(date_add) = DATE(?)
 GROUP BY fc.title");
            $statement->execute([$type, date('Y-m-d')]);
            foreach ($statement->fetchAll() as $item) {
                $html .= "{$item['title']}: {$item['amount']}" . PHP_EOL;
            }
        }
        return $html;
    }

    public function getFinanceMonth($type)
    {
        $months = [1 => 'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь',
            'Октябрь', 'Ноябрь', 'Декабрь'];

        if($type === 1){
            $html = "<u><b>Доходы за " . $months[date('n')] . ": </b></u>" . PHP_EOL;
        }elseif($type === 0){
            $html = "<u><b>Расходы за " . $months[date('n')] . ": </b></u>" . PHP_EOL;
        }else{
            $html = "<u><b>Итого за " . $months[date('n')] . ": </b></u>" . PHP_EOL;
        }

        if(false === $type){
            $statement = $this->pdo->prepare("SELECT SUM(amount) as amount, type FROM finance WHERE 
DATE(date_add) BETWEEN DATE(?) AND DATE(?) GROUP BY type ORDER BY type DESC");
            $statement->execute([date('Y-m-'). '01', date('Y-m-d')]);
            foreach ($statement->fetchAll() as $item) {
                if($item['type']){
                    $plus = $item['amount'];
                }else{
                    $minus = $item['amount'];
                }
            }
            $plus = $plus ?? 0;
            $minus = $minus ?? 0;
            $html .= $plus - $minus;
        }else{
            $statement = $this->pdo->prepare("SELECT SUM(f.amount) as amount, f.type, fc.title FROM
 finance f LEFT JOIN finance_categories fc ON f.category = fc.id WHERE f.type = ? AND DATE(date_add) BETWEEN 
 DATE(?) AND DATE(?) GROUP BY fc.title");
            $statement->execute([$type, date('Y-m-'). '01', date('Y-m-d')]);
            foreach ($statement->fetchAll() as $item) {
                $html .= "{$item['title']}: {$item['amount']}" . PHP_EOL;
            }
        }
        return $html;
    }

}