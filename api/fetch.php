<?php
require_once("../config/db_connect.php");

class FetchData {
    // 全データを取得
    public function fetch_data() {

        $_SESSION["USER_ID"]; 
        $user_id = $_SESSION["USER_ID"]; 

        try {
            // DB接続
            $DBConnecter = new DBConnecter();
        
            $sql = "SELECT * FROM schedule WHERE user_id = $user_id"; // ユーザIDを元にデータを取得
            $stmt = $DBConnecter->pdo->prepare($sql);
            $stmt->execute();
        
            // 結果の取得
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch(PDOException $Exception) {
            die('エラー :' . $Exception->getMessage());
        }
    }

    // 指定された月ごとのデータを取得
    public function fetch_data_month($month) {
        $_SESSION["USER_ID"]; 
        $user_id = $_SESSION["USER_ID"]; 

        try {
            // DB接続
            $DBConnecter = new DBConnecter();

            $sql = "SELECT * FROM schedule WHERE user_id = $user_id AND DATE_FORMAT(begin, '%m') = $month";
            $stmt = $DBConnecter->pdo->prepare($sql);
            $stmt->execute();
        
            // 結果の取得
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch(PDOException $Exception) {
            die('エラー :' . $Exception->getMessage());
        }
    }

    // 直近、一週間のデータを取得
    public function fetch_data_week() {
        $_SESSION["USER_ID"]; 
        $user_id = $_SESSION["USER_ID"]; 

        try {
            // DB接続
            $DBConnecter = new DBConnecter();

            $sql = "SELECT * FROM schedule WHERE user_id = $user_id AND begin <= (NOW() + INTERVAL 7 DAY) AND begin >= (NOW() - INTERVAL 7 DAY)";
            $stmt = $DBConnecter->pdo->prepare($sql);
            $stmt->execute();
        
            // 結果の取得
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch(PDOException $Exception) {
            die('エラー :' . $Exception->getMessage());
        }
    }
}