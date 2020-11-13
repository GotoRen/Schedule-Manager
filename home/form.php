<?php
session_start();

// ログイン状態チェック
if (!isset($_SESSION["USER_NAME"])) {
    header("Location: ../auth/signout.php");
    exit;
}

$_SESSION["USER_ID"]; 
$user_id = $_SESSION["USER_ID"]; 
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>新規登録フォーム</title>
    <script src="error_check.js" defer></script>
</head>
<body>
    <h1>スケジュール管理用Webアプリ</h1>
    <hr>

    <h3>新規登録フォーム</h3>
    
    <!-- 登録 -->
    <form action="../api/registration.php" method="POST" name="loginCheck" onSubmit="return check();">
        <input type=hidden name=user_id value="<?= $user_id ?>"> <!-- user_id も同時にPOSTする -->

        <label>開始日時   </label><input type="datetime-local" name="begin" value="" required><br /><br />
        <label>終了日時   </label><input type="datetime-local" name="end" value="" required><br /><br />
        <label>場所   </label><input type="text" name="place" value="" required><br /><br />
        <label>内容   </label><br /><textarea cols="30" rows="5" name="content" value="" required></textarea><br /><br />
        
        <font color=Red><p id="errorMessage"></p></font>
        <br />
        <input type="button" value="戻る" onClick="history.back()">
    <input type="submit" name="btn_confirm" value="登録確認">
    <br /><br />
    </form>
</boby>
</html>