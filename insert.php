<?php
mb_internal_encoding("utf8");  //文字化けしないように決まり文句
session_start();

//パスワードのハッシュ化  文字列を置換して元の文字を推測できなくすること
$password = password_hash($_POST["password"], PASSWORD_DEFAULT);

try {     //｛｝内に例外処理を記述
    $pdo = new PDO("mysql:dbname=php_practice;host=localhost;", "root", "");  //DBに接続
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  //エラーモードを「例外」に設定

    $stmt = $pdo->prepare("INSERT INTO user(name,mail,age,password,comments)VALUES(?,?,?,?,?)");  //プリペアードステートメント
    $stmt->execute(array($_POST["name"], $_POST["mail"], $_POST["age"], $password, $_POST["comments"]));
} catch (PDOException $e) {
    $e->getMessage();  //例外発生時にエラーメッセージを出力
}

// DB切断　必要な処理を完了したらセキュリティ上必ずDB切断すること
$pdo = null;

//セッション変数を全て解除する
$_SESSION = array();

if (isset($_COOKIE["session_name()"])) {
    setcookie("session_name()", "", time() - 1800, "/");  //sessionIDの削除
}

session_destroy();  //セッションの破棄

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>フォームを作る</title>
    <link rel="stylesheet" type="text/css" href="style2.css">
</head>

<body>
    <h1>登録完了</h1>
    <div class="confirm">
        <p>登録有難うございました。</p>
        <form action="index.php">
            <input type="submit" class="button1" value="TOPに戻る">
        </form>
    </div>
</body>

</html>