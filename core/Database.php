<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

class Database
{
    private $conn;

    public function connect()
    {
        $this->conn = null;

        $host = $_ENV['DB_HOST'];
        $username = $_ENV['DB_USERNAME'];
        $password = $_ENV['DB_PASSWORD'];
        $db_name = $_ENV['DB_NAME'];

        try {
            // Подключение к базе данных с использованием переменных окружения
            $this->conn = new PDO('mysql:host=' . $host . ';dbname=' . $db_name, $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection error: ' . $e->getMessage();
        }
        return $this->conn;
    }
}

