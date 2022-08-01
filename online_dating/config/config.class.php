<?php
  class Config {
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $dbname = 'online_dating_db';

    final protected function connect() {
      try {
        $dsn = 'mysql:host='.$this->host.';dbname='.$this->dbname;
        $pdo = new PDO($dsn, $this->username, $this->password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
      } catch (PDOException $e) {
        echo 'DB Connection Error '.$e->getMessage();
      }
    }
  }
 ?>
