<?php

namespace App;

use Dotenv\Dotenv;
use PDO;

class Database
{
    private static $instance = null;
    private $conn;

    private function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        $dsn = 'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'];
        $this->conn = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS']);
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function db()
    {
        return self::getInstance()->getConnection();
    }

    public function getConnection()
    {
        return $this->conn;
    }

    private function __clone() {}
    private function __wakeup() {}
}
