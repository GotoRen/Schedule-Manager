<?php
require_once("../lib/password.php");
require_once("../config/db_connect.php");

session_start();

$errorMessage = "";
$signUpMessage = "";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>サインアップ</title>
</head>
<body>
<?php
    echo "";
    if (isset($_POST["signUp"])) {
        if (empty($_POST['user_name']) || ctype_space($_POST['user_name'])) {  // 値が空のとき
            $errorMessage = 'ユーザーIDが未入力です。';
        } else if (empty($_POST['user_pass'])) {
            $errorMessage = 'パスワードが未入力です。';
        } else if (empty($_POST['user_pass2'])) {
            $errorMessage = 'パスワードが未入力です。';
        } else {
            if ($_POST["user_pass"] === $_POST["user_pass2"]) {
                // 入力したユーザIDとパスワードを格納
                $user_name = trim($_POST["user_name"]); // 余分な空白は削除して登録
                $user_pass = trim($_POST["user_pass"]); // 余分な空白は削除して登録

                try {
                    $DBConnecter = new DBConnecter();
    
                    // 重複検出
                    $sql = "SELECT * FROM users WHERE user_name = ? limit 1";
                    $smth = $DBConnecter->pdo->prepare($sql);
                    $smth->execute(array($user_name));
                    $result = $smth->fetch();
    
                    if($result > 1){
                        $errorMessage = 'このアカウントは登録済みです。';
                    } else {
                        $stmt = $DBConnecter->pdo->prepare("INSERT INTO users (user_name, user_pass) VALUES (?, ?)");
    
                        $stmt->execute(array($user_name, password_hash($user_pass, PASSWORD_DEFAULT)));  // パスワードのハッシュ化を行う（今回は文字列のみなのでbindValue(変数の内容が変わらない)を使用せず、直接excuteに渡しても問題ない）
                        $user_id = $DBConnecter->pdo->lastinsertid();  // 登録した(DB側でauto_incrementした)IDを$user_idに入れる
                        
                        header("Location: signup_completed.php", true, 301);
                        exit();
                    }
                } catch(PDOException $Exception) {
                    $errorMessage = 'データベースエラー';
                    die('エラー :' . $Exception->getMessage());
                }
            } else if($_POST["user_pass"] != $_POST["user_pass2"]) {
                $errorMessage = 'パスワードに誤りがあります。';
            } else {
                $errorMessage = '例外エラーが発生しました。r0719en@pluslab.org まで連絡をください。';
            }
        }    
    }
?>
    <h1>スケジュール管理用Webアプリ</h1>
    <hr>

    <h3>ユーザ登録フォーム</h3>
    <form id="loginForm" name="loginForm" action="" method="POST">
        <fieldset>
            <legend>新規登録フォーム</legend>
            <div><font color="#ff0000"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></font></div>
            <div><font color="#0000ff"><?php echo htmlspecialchars($signUpMessage, ENT_QUOTES); ?></font></div>
            <label for="user_name">ユーザー名</label><input type="text" id="user_name" name="user_name" placeholder="ユーザー名を入力" value="<?php if (!empty($_POST["user_name"])) {echo htmlspecialchars($_POST["user_name"], ENT_QUOTES);} ?>">
            <br>
            <label for="user_pass">パスワード</label><input type="password" id="user_pass" name="user_pass" value="" placeholder="パスワードを入力">
            <br>
            <label for="user_pass2">パスワード(確認用)</label><input type="password" id="user_pass2" name="user_pass2" value="" placeholder="再度パスワードを入力">
            <br>
            <input type="submit" id="signUp" name="signUp" value="新規登録">
        </fieldset>
    </form>
    <br>
    <form action="../index.php">
        <input type="submit" value="戻る">
    </form>
</body>
</html>