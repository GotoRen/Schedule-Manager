# Schedule-Manager
## ✏️ Name
<img alt="タイトル" width="700" src="https://raw.github.com/wiki/GotoRen/Schedule-Manager/images/title.png" />

## 💡 Overview
- 2020後期 Webプログラミング及び演習 自由課題 としてスケジュール管理アプリを制作
- ユーザ登録とスケジュール登録を行うことができる
  - ユーザはサインアップまたはサインインによりサービスを利用  
- スケジュールの形式は『開始日時』『終了日時』『場所』『内容』の 4項目
- 追加した予定は「条件検索」「更新」「削除」ができる
  - 条件検索には「全件」「直近一週間」「月ごとで検索」ができる
- スケジュールはログインするユーザによって、自分のスケジュールを利用 することができる
  - 複数のユーザが個々のスケジュールを管理することができる
  - 他のユーザが他人のスケジュールを見ることはない
- ログイン / ログアウトの機能
  - ログインした状態で画面を閉じた場合、再度画面を開くとログインした状態から
  - ログアウトした状態で画面を閉じた場合、再度画面を開くとログイン項目の入力を要求

## 📝 Description
- 環境
  - XAMPP（MAMP） + Apache Web Server + MariaDB（MySQL）
  - WHOAMI：`localhost`
  - Loopback ADDR：`127.0.0.1`
- DB構築
  - `/Applications/XAMPP/xamppfiles/bin`配下
  - `$ mysql -u root -p`  
    - ユーザ：root
    - パスワード：password
  - データベース
    - `> create database schedule_db character set utf8 collate utf8_general_ci;`
  - テーブル
    - ユーザ管理テーブル
      ```sql
      DROP TABLE IF EXISTS users;

      CREATE TABLE users (
          user_id    int(11)       NOT NULL AUTO_INCREMENT,
          user_name  varchar(255)  NOT NULL,
          user_pass  varchar(255)  NOT NULL,
          PRIMARY KEY (user_id)
      );
      ```
        - user_id：ユーザ識別子（プライマリキー）
        - user_name：ユーザ名（ユーザIDとして使用）
        - user_pass：ログインパスワード
    - スケジュール管理テーブル
      ```sql
      DROP TABLE IF EXISTS schedule;

      CREATE TABLE schedule (
          id       int(11)       NOT NULL AUTO_INCREMENT,
          user_id  int(11)       NOT NULL,
          begin    datetime      NOT NULL,
          end      datetime      NOT NULL,
          place    varchar(255)  NOT NULL,
          content  text          NOT NULL,
          PRIMARY KEY (id)
      );
      ```
        - id：スケジュール識別子（プライマリキー）
        - user_id：ユーザ識別子（ユーザ管理テーブルと連携）
        - begin：開始日時
        - end：終了日時
        - place：場所
        - content：予定内容
- DB接続
  ```php
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
  ```
        