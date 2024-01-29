<?php
session_start();
mb_internal_encoding("utf8");

if (!isset($_SESSION['id'])) {
    header("Location:login.php");
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>マイページ</title>
    <link rel="stylesheet" type="text/css" href="style2.css">
</head>

<body>
    <h1>ようこそ<?php echo $_SESSION["name"]; ?>さん</h1>
    <div class="confirm">
        <p>正しくログインできました！</p>
        <div class="user_info">
            <p>氏名:<?php echo $_SESSION["name"]; ?></p>
            <p>年齢:<?php echo $_SESSION["age"]; ?>歳</p>
            <p>メール:<?php echo $_SESSION["mail"]; ?></p>
            <p>パスワード:******</p>
            <p>コメント:<?php echo $_SESSION["comments"]; ?></p>
        </div>
        <form action="logout.php">
            <input type="submit" class="button1" value="ログアウト">
        </form>
    </div>
</body>

</html>