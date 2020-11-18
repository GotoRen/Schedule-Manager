<?php
require_once("init.php");

class DBConnecter {
    public $pdo;

    public function __construct() {
        $this->db_connect();
    }
            
    //----------------------------------------------------
    // データベース接続
    //----------------------------------------------------
    private function db_connect() {
        try {
            $this->pdo = new PDO(_DSN, _DB_USER, _DB_PASS);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch(PDOException $Exception) {
	    echo "エラー：データベースに接続できません";
            die('エラー :' . $Exception->getMessage());
        }
    }
}
