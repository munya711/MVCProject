<?php
require_once('./config.php');

class database {
    private $dsn;
    private $username;
    private $password;
    private $pdo;

    public function __construct() {
        $this->dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_DATABASE . ";charset=utf8mb4";
        $this->username = DB_USER;
        $this->password = DB_PASSWORD;
        $this->connect();
    }
    //connect to database
    private function connect() {
        try {
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $this->pdo = new PDO($this->dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function query($sql, $bind = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bind);
        return $stmt;
    }

    public function execute($sql, $bind = []) {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($bind);
    }


    public function close() {
        $this->pdo = null;
    }
}
?>
