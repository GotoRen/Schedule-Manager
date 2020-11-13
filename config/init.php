<?php
//----------------------------------------------------
// エラー表示
//----------------------------------------------------

// 開発中
//ini_set( "error_reporting", E_ALL );

// 運用中
ini_set( "error_reporting", E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED );


//----------------------------------------------------
// データベース関連
//----------------------------------------------------

// データベース接続ユーザー名
define("_DB_USER", "root");

// データベース接続パスワード
define("_DB_PASS", "password");

// データベースホスト名
define("_DB_HOST", "localhost");

// データベース名
define("_DB_NAME", "schedule_db");

// データベースの種類
define("_DB_TYPE", "mysql");

// データソースネーム
define("_DSN", _DB_TYPE . ":host=" . _DB_HOST . ";dbname=" . _DB_NAME. ";charset=utf8");
