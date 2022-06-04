<?php namespace App\Core;

use PDO;

class Database
{
    private $pdo, $dbname = "buka", $username = "root", $password = "";
    private $options = [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
    ];

    public function __construct()
    {
        $this->pdo = new PDO("mysql:host=localhost;dbname=$this->dbname", "$this->username", "$this->password", $this->options);
    }

    public function getPdo()
    {
        return $this->pdo;
    }
}