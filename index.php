<?php
/******************
* user_id: 主キー
* user_name: ユーザID
* user_pass: パスワード
******************/
require_once("lib/password.php");
require_once("config/db_connect.php");

session_start();

if (isset($_SESSION["USER_NAME"])) {
    header("Location: home/main.php");
    exit;
}

$errorMessage = "";

?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>サインイン</title>
    <link rel="stylesheet" href="css/bootstrap.css">
</head>
<body>
<?php
    if (isset($_POST["login"])) {
        if (empty($_POST["user_name"])) {
            $errorMessage = 'ユーザIDが未入力です。';
        } else if (empty($_POST["user_pass"])) {
            $errorMessage = 'パスワードが未入力です。';
        } else {
            if (!empty($_POST["user_name"]) && !empty($_POST["user_pass"])) {
        
                $user_name = trim($_POST["user_name"]);
            
                try {
    
                    $DBConnecter = new DBConnecter();
                    $sql = "SELECT * FROM users WHERE user_name = ?";
                    $stmt = $DBConnecter->pdo->prepare($sql);
                    $stmt->execute(array($user_name));
    
                    $user_pass = $_POST["user_pass"];
    
                    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        if (password_verify($user_pass, $row['user_pass'])) {
                            // 認証成功
                            session_regenerate_id(true);
    
                            $user_id = $row['user_id'];
                            $sql = "SELECT * FROM users WHERE user_id = $user_id";
                            $stmt = $DBConnecter->pdo->query($sql);
    
                            foreach ($stmt as $row) {
                                $row['user_name'];
                            }
                            $_SESSION["USER_ID"] = $row['user_id'];
                            $_SESSION["USER_NAME"] = $row['user_name'];
                        
                            header("Location: home/main.php");
                            exit();
                        } else {
                            // 認証失敗
                            $errorMessage = 'ユーザIDあるいはパスワードに誤りがあります。';
                        }
                    } else {
                        $errorMessage = 'ユーザIDあるいはパスワードに誤りがあります。';
                    }
                } catch(PDOException $Exception) {
                    $errorMessage = 'データベースエラー';
                    die('エラー :' . $Exception->getMessage());
                }
            }        
        }
    }
?>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card login">
                    <div class="card-body">
                        <h1>- Signin -</h1>
                        <img class="col-md-12 title" src="img/title.png" alt="コンテンツ">
                        <form id="loginForm" name="loginForm" action="" method="POST">
                            <div class="form-group">
                                <!-- エラーメッセージ -->
                                <div class="err_msg"><?= htmlspecialchars($errorMessage, ENT_QUOTES); ?></div>
                                <!-- ユーザID -->
                                <input type="text" id="user_name" name="user_name" class="form-control" placeholder="ユーザID" value="<?php if (!empty($_POST["user_name"])) {echo htmlspecialchars($_POST["user_name"], ENT_QUOTES);} ?>">
                                <!-- パスワード -->
                                <input type="password" id="user_pass" name="user_pass" class="form-control" placeholder="パスワード" value="" >
                                <!-- 送信 -->
                                <input type="submit" id="login" name="login" class="btn btn-primary w-100" value="サインイン">
                            </div>
                            <div class="signup">
                                <div class="message">
                                    アカウントをお持ちではありませんか？&nbsp;
                                    <a href="auth/signup.php">作成する.</a>
                                </div>
                            </div>
                        </form>
                    <div>
                </div>
            </div>
        </div>
    <div>
</body>
</html>

