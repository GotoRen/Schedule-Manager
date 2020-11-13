<?php
require_once("../api/fetch.php");

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
    <title>スケジュール管理用Webアプリ</title>
</head>
<body>
    <h1>スケジュール管理用Webアプリ</h1>
    <hr>
    <p>ようこそ【 <u><?php echo htmlspecialchars($_SESSION["USER_NAME"], ENT_QUOTES); ?></u> 】さん</p>  <!-- ユーザー名をechoで表示 --> 
    <ul>
        <li><a href="../auth/signout.php">ログアウト</a></li>
    </ul> 
    <hr>

    <button onclick="location.href='form.php'">スケージュルを追加</button>
    <hr>
    
    <form action="" method="post">
        <button type="submit" name="getdata" value="all">すべて</button>
        <button type="submit" name="getdata" value="week">週ごと</button>
        <button type="submit" name="getdata" value="month">月ごと</button>
    </form>
    <hr>

    <table border=1>
        <tr>
            <td>開始日時</td>
            <td>終了日時</td>
            <td>場所</td>
            <td>内容</td>
            <td>更新</td>
            <td>削除</td>
        </tr>
<?php
    $FetchData = new FetchData;

    if ($_POST['getdata'] == "week") {
        echo "<h1>週間のスケジュール</h1>";
        $data = $FetchData->fetch_data_week();
        echo "直近一週間のスケジュールを表示しています。";
    } elseif ($_POST['getdata'] == "month") {
        echo "<h1>月間のスケジュール</h1>"; 
?>        
        <form action="" method=POST >
            <select size="12" name="choose_month">
            <?php for ($i=1; $i<=12; $i++) { ?>
                <option value="<?= $i ?>"><?= $i ?>月</option>
            <?php } ?>

            </select>
            <input type="hidden" name="getdata" value="month"><br />
            <input type="submit" name="submit" value="検索"><br /><br />
        </form>     
<?php
        $choose_month = $_POST['choose_month'];

        if (isset($choose_month)) {
            $data = $FetchData->fetch_data_month($choose_month);
            echo "<br />" . $choose_month . "月中のスケジュールを表示しています。";
        } else {
            $data = $FetchData->fetch_data_month(date('m')); // デフォルトでは今の月を選択
            echo "今月中のスケジュールを表示しています。";
        }
    } else {
        echo "<h1>スケジュール一覧</h1>"; 
        $data = $FetchData->fetch_data();
        
    }

        // データ表示
        foreach ($data as $recode) {
?>              
            <tr>
                <!-- データ -->
                <td><?= date("Y年m月d日 H時i分", strtotime($recode['begin'])); ?></td>
                <td><?= date("Y年m月d日 H時i分", strtotime($recode['end'])); ?></td>
                <td><?= $recode['place'] ?></td>
                <td><?= nl2br($recode['content']) ?></td>
                <!-- 更新 -->
                <form action = ../api/update.php method=POST>
                  <input type=hidden name=id value="<?= $recode["id"] ?>">
                  <td><input type=submit value=更新></td>
                </form>
                <!-- 削除 -->
                <form action = ../api/delete.php method=POST>
                  <input type=hidden name=id value="<?= $recode["id"] ?>">
                  <td><input type=submit value=削除></td>
                </form>
            </tr>            
<?php       
        }
?>
    <table>

</body>
</html>