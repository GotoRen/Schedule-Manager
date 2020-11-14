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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>スケジュール管理用Webアプリ</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <style>
        .navbar-text-content {
            color: #32CD32;
            font-size: 25px;
            font-weight: 300;
        }
        .navbar {
            margin: 20px;
        }
    </style>
</head>
<body>
    <!-- ヘッダ -->
    <nav class="navbar navbar-light mb-4 mx-auto" style="width: 80%; background-color: #FFF;">
        <div class="navbar-brand">
            <img src="../img/title.png" style="width: 18rem;" alt="コンテンツ">
            <h6><font color=#87cefa>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;~ What your schedules today? ~</font></h6>
        </div>
        <div class="navbar-text-content m-3">
            ログイン中：  <?php echo htmlspecialchars($_SESSION["USER_NAME"], ENT_QUOTES); ?> さん <!-- ユーザー名をechoで表示 --> 
            <button class="btn btn-outline-warning p-3 m-2" onclick="location.href='../auth/signout.php'">ログアウト</a>
        </div>
    </nav>
    <!----------->
    <!-- コンテンツ -->
    <div class="container-fluid" mx-auto style="width: 90%;">
        <div class="card">
            <h2 class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-center stripe">〜 スケジュールの表示 〜</p>
                    <form action="" method="post">
                        <button type="submit" name="getdata" class="btn btn-outline-primary" value="all">すべて</button>
                        <button type="submit" name="getdata" class="btn btn-outline-primary" value="week">週ごと</button>
                        <button type="submit" name="getdata" class="btn btn-outline-primary" value="month">月ごと</button>
                    </form>
                </div>
                <button class="btn btn-primary p-3" onclick="location.href='form.php'">スケージュルを追加</button>
            </h2>
              
            <div class="container-fluid">
                <div class="row">
                    <!-- 月の選択画面を表示 -->
                    <!------------------------------------------------------------------------------>
                    <div class=" col-1.5 content pull-right p-0">
                        <div class="h-100 card-footer d-flex justify-content-between">
                            <div class="pull-center p-3 select-box"> 
                                <p>月を選択</p>
                                <form action="" method=POST >
                                    <select size="12" name="choose_month">
                                    <?php for ($i=1; $i<=12; $i++) { ?>
                                        <option value="<?= $i ?>"><?= $i ?>月</option>
                                    <?php } ?>
                                    </select>
                                    <br /><br />
                                    <input type="hidden" name="getdata" value="month">
                                    <input type="submit" name="submit" class="btn btn-danger m-2" value="検索">
                                </form> 
                            </div>
                        </div>
                    </div>
                    <!------------------------------------------------------------------------------>

                    <!-- スケジュール表示 -->
                    <!------------------------------------------------------------------------------>
                    <div class="container col-10">
                        <br>
                        <table class="table table_style table_border_radius" border="1">
                            <tr>
                                <th class="table-danger text-center header" style="width: 2.5em;">開始日時</th>
                                <th class="table-danger text-center header" style="width: 2.5em;">終了日時</th>
                                <th class="table-success text-center header" style="width: 2.5em;">場所</th>
                                <th class="table-warning text-center header" style="width:200px;">内容</th>
                                <th class="table-info text-center header" style="width: 1em;">更新</th>
                                <th class="table-info text-center header" style="width: 1em;">削除</th>
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
                                echo "すべてのスケジュールを表示しています。";   
                            }
                        
                        // データ表示
                        foreach ($data as $recode) {
?>              
                            <tr>
                                <!-- データ -->
                                <td>
                                    <?= date("Y年<br />m月d日", strtotime($recode['begin'])); ?><br />
                                    <?= date("H時i分 ~", strtotime($recode['begin'])); ?>
                                </td>
                                <td>
                                    <?= date("Y年<br />m月d日", strtotime($recode['end'])); ?><br />
                                    <?= date("~ H時i分", strtotime($recode['end'])); ?>
                                </td>
                                <td><?= $recode['place'] ?></td>
                                <td><?= nl2br($recode['content']) ?></td>
                                <!-- 更新 -->
                                <form action=../api/update.php method=POST>
                                    <input type=hidden name=id value="<?= $recode["id"] ?>">
                                    <td class="align-middle text-center p-1"><input type=submit class="btn btn-danger" value=更新></td>
                                </form>
                                <!-- 削除 -->
                                <form action = ../api/delete.php method=POST>
                                    <input type=hidden name=id value="<?= $recode["id"] ?>">
                                    <td class="text-center align-middle p-1"><input type=submit class="btn btn-danger" value=削除></td>
                                </form>
                            </tr>   
<?php       
                        }
?>
                        </table>
                    </div>
                    <!------------------------------------------------------------------------------>
                </div>
            </div>
        </div>
    </div>
    <!----------->
</body>
</html>