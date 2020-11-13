<?php
require_once("../config/db_connect.php");

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
    <title>更新フォーム</title>
    <script src="error_check.js" defer></script>
</head>
<body>
    <h1>スケジュール管理用Webアプリ</h1>
    <hr>

<?php 
    if ($page_flag === 1) { // 確認画面 1

        $id = $_POST['id'];
        $begin = $_POST['begin'];
        $end = $_POST['end'];
        $place = $_POST['place'];
        $content = $_POST['content'];
?>
        <h3>確認画面</h3>
        <form action="" method=POST>
            <div>
                開始日時&emsp;<?= date("Y年m月d日 H時i分", strtotime($begin)) ?><br />
                終了日時&emsp;<?= date("Y年m月d日 H時i分", strtotime($end)) ?><br />
                場所&emsp;<?= $place ?><br />
                内容&emsp;<br /><?= nl2br($content); ?><br />
            </div>

            <br /><br />
            <input type="button" value="戻る" onClick="history.back()">
            <input type="submit" name="btn_submit" value="更新">

            <input type="hidden" name="user_id" value="<?= $user_id; ?>">
            <input type="hidden" name="begin" value="<?= $begin; ?>">
        	<input type="hidden" name="end" value="<?= $end; ?>">
            <input type="hidden" name="place" value="<?= $place; ?>">
            <input type="hidden" name="content" value="<?= $content; ?>">   
            <input type="hidden" name="id" value="<?= $id; ?>">
        </form>
<?php 
    } elseif ($page_flag === 2) { // 実行画面 2

        $id = $_POST['id'];
        $begin = $_POST['begin'];
        $end = $_POST['end'];
        $place = $_POST['place'];
        $content = $_POST['content'];


        try {
            // DB接続
            $DBConnecter = new DBConnecter();

            $DBConnecter->pdo->beginTransaction();    
            $sql = "UPDATE schedule SET begin = :begin, end = :end, place = :place, content = :content WHERE id = :id";
            $stmt = $DBConnecter->pdo->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':begin', $begin, PDO::PARAM_STR);
            $stmt->bindValue(':end', $end, PDO::PARAM_STR);
            $stmt->bindValue(':place', $place, PDO::PARAM_STR);
            $stmt->bindValue(':content', $content, PDO::PARAM_STR);
            $stmt->execute();
            $DBConnecter->pdo->commit();

            if ($stmt->rowCount() > 0) {
                echo "データを " . $stmt->rowCount() . "件 更新しました。<br />";
            } else {
                echo "更新はありませんでした。<br />";
            }
            echo "更新完了";
?>
            <button onclick="location.href='../home/main.php'">スケジュール一覧へ戻る</button>
<?php
        } catch(PDOException $Exception) {
            $DBConnecter->pdo->rollBack();
            die('エラー :' . $Exception->getMessage());
        }
    } else { // 入力画面 0

        $id = $_POST['id'];
       
        try {
            // DB接続
            $DBConnecter = new DBConnecter();
            $sql = "SELECT * FROM schedule where id = $id";
            $stmt = $DBConnecter->pdo->prepare($sql);
            $stmt->execute();
            $recode = $stmt->fetch();
?>
            <h3>更新フォーム</h3>
            <form action="" method="POST" name="loginCheck" onSubmit="return check();">
                <label>開始日時   </label><input type="datetime-local" name="begin" value="<?= str_replace(" ", "T", $recode['begin']); ?>" required><br /><br />
                <label>終了日時   </label><input type="datetime-local" name="end" value="<?= str_replace(" ", "T", $recode['end']); ?>" required><br /><br />
                <label>場所   </label><input type="text" name="place" value="<?= $recode['place']; ?>" required><br /><br />
                <label>内容   </label><br /><textarea cols="30" rows="5" name="content" required><?= str_replace("<br />", "", nl2br($recode['content'])); ?></textarea><br /><br />
        
                <font color=Red><p id="errorMessage"></p></font>

                <br />
                <input type=hidden name=id value="<?= $id ?>">
                <input type="button" value="戻る" onClick="history.back()">
                <input type=submit name="btn_confirm" value=更新確認>
            </form>
            <?php
        } catch(PDOException $Exception) {
            die('エラー :' . $Exception->getMessage());
        }
    }
?>

</body>
</html>
