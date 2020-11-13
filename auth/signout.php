<?php
session_start();

if (isset($_SESSION["NAME"])) {
    $errorMessage = "ログアウトしました。";
} else {
    $errorMessage = "ログアウトしました。";
}

// セッションの変数をクリア
$_SESSION = array();

// セッションクリア
@session_destroy();
?>

<!Doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>ログアウト</title>
</head>
<body>
    <h1>スケジュール管理用Webアプリ</h1>
    <hr>

    <h3>ログアウト完了</h3>
    <div><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></div>
    <input type="button" onClick="location.href='../index.php'" value="ログインフォームへ戻る">
</body>
</html>