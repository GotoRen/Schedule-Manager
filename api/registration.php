<?php
require_once("../config/db_connect.php");

session_start();

// ログイン状態チェック
if (!isset($_SESSION["USER_NAME"])) {
    header("Location: ../auth/signout.php");
    exit;
}

$page_flag = 0;

if (!empty($_POST['btn_confirm'])) {
	$page_flag = 1;
} elseif ( !empty($_POST['btn_submit'])) {
	$page_flag = 2;
}
?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta caharset="UTF-8">
    <title>新規登録フォーム</title>
</head>
<body>
    <h1>スケジュール管理用Webアプリ</h1>
    <hr>

<?php 
    $user_id = $_POST['user_id'];
    $begin = $_POST['begin'];
    $end = $_POST['end'];
    $place = htmlspecialchars($_POST['place']);
    $content = htmlspecialchars($_POST['content']);

    if ($page_flag === 1) { // 確認画面 page_flag = 1
?>        
        <h3>確認画面</h3>
        <form action="" method=POST>
        	<div>
        		<label>開始日時</label>&emsp;
                <?= date("Y年m月d日 H時i分", strtotime($begin)) ?><br />
                <label>終了日時</label>&emsp;
                <?= date("Y年m月d日 H時i分", strtotime($end)) ?><br />
                <label>場所</label>
                <?= $place ?>
                <br />
                <label>内容</label><br />
                <?= nl2br($content) ?>
        	</div>

            <br />
            <input type="button" value="戻る" onClick="history.back()">
            <input type="submit" name="btn_submit" value="登録">

            <input type="hidden" name="user_id" value="<?= $user_id ?>">
            <input type="hidden" name="begin" value="<?= $begin ?>">
        	<input type="hidden" name="end" value="<?= $end ?>">
            <input type="hidden" name="place" value="<?= $place ?>">
            <input type="hidden" name="content" value="<?= $content ?>">           
        </form>
<?php 
    } elseif ($page_flag === 2) { // 実行画面 page_flag = 2

        try {
            // DB接続
            $DBConnecter = new DBConnecter();
        
            $DBConnecter->pdo->beginTransaction();    
            $sql = "INSERT INTO schedule (user_id, begin, end, place, content) VALUES (:user_id, :begin, :end, :place, :content)";
            $stmt = $DBConnecter->pdo->prepare($sql);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':begin', $begin, PDO::PARAM_STR);
            $stmt->bindValue(':end', $end, PDO::PARAM_STR);
            $stmt->bindValue(':place', $place, PDO::PARAM_STR);
            $stmt->bindValue(':content', $content, PDO::PARAM_STR);
            $stmt->execute();
            $DBConnecter->pdo->commit();
            echo "登録完了";
?>            
            <button onclick="location.href='../home/main.php'">スケジュール一覧へ戻る</button>
<?php        
        } catch(PDOException $Exception) {
            $DBConnecter->pdo->rollBack();
            die('エラー :' . $Exception->getMessage());
        }
    } else { // 入力画面 page_flag = 0
        header("Location: ../home/main.php", true, 301);
        exit();
    }
?>

</body>
</html>