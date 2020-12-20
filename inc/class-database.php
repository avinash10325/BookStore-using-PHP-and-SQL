<?php
class Database {
    private $connection;

    private $dbhost = "localhost";
    private $dbname = "bookstore";
    private $dbuser = "root";
    private $dbpass = "";
    private $dbchar = "utf8";

    public function __construct() {
        $this->connection = null;
    }

    public function connect() {
        try {
            $this->connection = new PDO("mysql:host=$this->dbhost; dbname=$this->dbname; charset=$this->dbchar", $this->dbuser, $this->dbpass);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $this->connection;
        }
        catch(PDOException $error) {
            die('Error: ' . $error->getMessage());
        }
    }
}

?>