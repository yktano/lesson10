<?php
mb_internal_encoding("utf8");

try {
    $pdo = new PDO("mysql:dbname=php_practice;host=localhost;","root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->query("SELECT * FROM user");
} catch (PDOException $e) {
    $e->getMessage();
}

$pdo = null;
//文字列きーによる配列としてテーブル全行取得
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ユーザーリスト</title>
    <style>
        td {
            padding: 7px 40px;
        }
    </style>
</head>

<body>
<h1>ユーザーリスト</h1>
<div class="confirm">
    <table>
        <tr>
            <th>ID</th>
            <th>名前</th>
            <th>メールアドレス</th>
            <th>年齢</th>
            <th>コメント</th>
        </tr>
        <?php
        foreach($users as $user) {
            echo "<tr>\n";
            echo "<td>{$user['id']}<td>\n";
            echo "<td>{$user['name']}<td>\n";
            echo "<td>{$user['mail']}<td>\n";
            echo "<td>{$user['age']}歳<td>\n";
            echo "<td>{$user['comments']}<td>\n";
            echo "</tr>\n";
        }
        ?>
    </table>
    </div>
</body>
</html>