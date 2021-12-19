<?php
    class DB {
        protected $pdo;

        public function __construct()
        {
            require(__DIR__ . '/db_config.php');
        
            $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
        
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];
        
            try {
                $this -> pdo = new PDO($dsn, $username, $password, $options);
            } catch (\PDOException $e) {
                echo $e->getMessage();
                // TODO In production, there should be a redirection
            }
        }
    }
?>