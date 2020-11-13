<?php
require_once("../config/db_connect.php");

$page_flag = 0;

if (!empty($_POST['btn_submit'])) {
	$page_flag = 1;
}
?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta caharset="UTF-8">
    <title>削除フォーム</title>
</head>
<body>
    <h1>スケジュール管理用Webアプリ</h1>
    <hr>

<?php 
    if ($page_flag === 1) { // 確認画面 1

        $id = $_POST['id'];

        try {
            // DB接続
            $DBConnecter = new DBConnecter();

            $DBConnecter->pdo->beginTransaction();
            $sql = "DELETE FROM schedule WHERE id = :id";
            $stmt = $DBConnecter->pdo->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $DBConnecter->pdo->commit();
            echo '削除完了';
?>            
            <button onclick="location.href='../home/main.php'">スケジュール一覧へ戻る</button>
<?php            
        } catch(PDOException $Exception) {
            $DBConnecter->pdo->rollBack();
            die('エラー :' . $Exception->getMessage());
        }
    } else { // データ表示

        $id = $_POST['id'];
        
        try {
            // DB接続
            $DBConnecter = new DBConnecter();
            $sql = "SELECT * FROM schedule where id = $id";
            $stmt = $DBConnecter->pdo->prepare($sql);
            $stmt->execute();
            $recode = $stmt->fetch();
        } catch(PDOException $Exception) {
            die('エラー :' . $Exception->getMessage());
        }
?>
        <h3>削除フォーム</h3>
        <h3><font color="Red">最終確認</font></h3>
        <form action="" method=POST>
            <div>
                開始日時&emsp;<?= date("Y年m月d日 H時i分", strtotime($recode['begin'])) ?><br />
                終了日時&emsp;<?= date("Y年m月d日 H時i分", strtotime($recode['end'])) ?><br />
                場所&emsp;<?= $recode['place']; ?><br />
                内容&emsp;<br /><?= nl2br($recode['content']); ?><br />
            </div>
            <br />
            <input type="button" value="戻る" onClick="history.back()">
            <input type="submit" name="btn_submit" value="削除">
            <input type="hidden" name="id" value="<?php echo $recode['id']; ?>">
        </form>
<?php
    }
?>

</body>
</html>