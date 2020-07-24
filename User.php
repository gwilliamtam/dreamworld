<?php

class User
{
    private $host = '';
    private $database = '';
    private $username = '';
    private $password = '';
    private $connected = false;
    private $error = '';
    private $pdo;
    private $result;

    public function __construct()
    {
    }

    public function getByEmail($email)
    {
        $query = "select * from users where email = '$email' limit 1";
        $this->connect();
        if ($this->connected) {
            $this->result = $this->pdo->query($query)->fetch();
            $this->close();
        }
        return $this->result;
    }

    public function getAll()
    {
        $query = "select * from users order by id limit 100";
        $this->connect();
        if ($this->connected) {
            $this->result = $this->pdo->query($query)->fetchAll();
            $this->close();
        }
        return $this->result;
    }

    public function insert($email, $password, $birthday)
    {
        if (empty($email) || empty($password) || empty($birthday)) {
            return;
        }
        $email = htmlentities($email, ENT_QUOTES, 'UTF-8');
        $password = htmlentities($password, ENT_QUOTES, 'UTF-8');
        $encriptedPassword = password_hash($password, PASSWORD_BCRYPT);

        $insertRecord = <<<SQL
            insert into users (email, password, birthday) 
                values ('$email', '$encriptedPassword', '$birthday')
SQL;
        $this->query($insertRecord);
    }

    private function query($sqlText)
    {
        $this->connect();
        if ($this->connected) {
            $this->result = $this->pdo->exec($sqlText);
            $this->close();
        }
        return $this->result;
    }

    private function connect()
    {
        require_once('Config.php');
        $this->connected = false;
        try {
            $this->pdo = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database, $this->username, $this->password);
            $this->connected = true;
        } catch (PDOException $e){
            $this->error = $e->getMessage();
        }
    }

    public function close()
    {
        $this->pdo = null;
    }

    public function populateTable()
    {
        $total = 10;
        $filename = 'lorem_ipsum.txt';
        $handle = fopen($filename, "r");
        $contents = fread($handle, filesize($filename));
        fclose($handle);
        $contents = str_replace('.', '', $contents);
        $contents = str_replace(',', '', $contents);
        $contents = str_replace(PHP_EOL, '', $contents);
        $words = explode(' ', $contents);
        $minDate = strtotime('1950-01-01');
        $maxDate = strtotime(date('Y-m-d', strtotime('-18 year')));

        $values = "";
        for ($i=0; $i<$total; $i++) {
            $email = strtolower($words[array_rand($words)]) . '@' . strtolower($words[array_rand($words)]) . ".com";
            $password = strtolower($words[array_rand($words)])
                . strtolower($words[array_rand($words)])
                . strtolower($words[array_rand($words)])
                . rand(1000000,9999999);
            $dateValue = rand($minDate, $maxDate);
            $birthday = date('Y-m-d', $dateValue);

            $values .= ",('" . $email . "','" . $password . "','" .$birthday . "')";
        }
        $values = substr($values, 1); // remove first comma
        $insertQuery = "insert into users (email, password, birthday) values " . $values;
        $this->query($insertQuery);
    }

    public function createTable()
    {
        $queryCreateTable = <<<SQL
            CREATE TABLE IF NOT EXISTS users (
                id int(20) NOT NULL AUTO_INCREMENT,
                email char(100) NOT NULL UNIQUE KEY,
                password char(200) NOT NULL,
                birthday date NOT NULL,
                PRIMARY KEY (id) 
            )
SQL;
        $this->query($queryCreateTable);
    }
}