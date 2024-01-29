<?php

session_start();
mb_internal_encoding("utf8");

//ログイン状態であれば、マイページにリダイレクト
if (isset($_SESSION['id'])) {
    header("Location:mypage.php");
}

//変数の初期化
$errors = "";

//POST処理
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //エスケープ処理
    $input["mail"] = htmlentities($_POST["mail"] ?? "", ENT_QUOTES);
    $input["password"] = htmlentities($_POST["password"] ?? "", ENT_QUOTES);

    //1.バリテーションチェック
    if (!filter_input(INPUT_POST, "mail", FILTER_VALIDATE_EMAIL)) { //メールの形式確認
        $errors = "メールアドレスとパスワードを正しく入力して下さい。";
    }
    if (strlen(trim($_POST["password"] ?? "")) == 0) {  //入力されているかの確認
        $errors = "メールアドレスとパスワードを正しく入力して下さい。";
    }


    //ログイン認証
    if (empty($errors)) {
        //DBに接続
        try {
            $pdo = new PDO("mysql:dbname=php_practice;host=localhost;", "root", "");     //DBに接続
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   //エラーモードを例外に設定
            //入力されたメールアドレスをもとにユーザー情報を取り出す
            $stmt = $pdo->prepare("SELECT * FROM user WHERE mail = ?");
            $stmt->execute(array($input["mail"]));
            $user = $stmt->fetch(PDO::FETCH_ASSOC);     //文字列キーによる配列としてテーブル取得
        } catch (PDOException $e) {
            echo mb_convert_encoding($e->getMessage(), 'utf-8', 'sjis');  //例外発生時にエラーメッセージを出力
        }

        //DBを切断
        $pdo = NULL;

        //ユーザー情報が取り出せた　かつ　パスワードが一致すればセッションに値を代入しマイページへ遷移
        if ($user && password_verify($input["password"], $user["password"])) {  //password_verify()とはパスワードがハッシュ値に
            $_SESSION['id'] = $user['id'];                                      //適合するかどうかを調査する関数
            $_SESSION['name'] = $user['name'];                                  //第1引数にヘイブンのパス、第2引数にハッシュ値を
            $_SESSION['mail'] = $user['mail'];                                  //指定しパスとハッシュが適合する場合にTRUE、それ以外の
            $_SESSION['age'] = $user['age'];                                    //場合にFALSEを返します
            $_SESSION['password'] = $input["password"];
            $_SESSION['comments'] = $user['comments'];

            //「ログイン情報を保持する」にチェックsがあればセッションにセットする
            if ($_POST['login_keep'] == 1) {
                $_SESSION['login_keep'] = $_POST["login_keep"];
            }
            //「ログイン情報を保持する」にチェックがあればクッキーをセット、なければ削除する。
            if (!empty($_SESSION['id']) && !empty($_SESSION['login_keep'])) {                 //ログインに成功している、かつ
                setcookie('mail', $_SESSION['mail'], time() + 60 * 60 * 24 * 7);              //$_SESSION[login_keep]が空ではない場合
                setcookie('password', $_SESSION['password'], time() + 60 * 60 * 24 * 7);      //cookieにデータを保存
                setcookie('login_keep', $_SESSION['login_keep'], time() + 60 * 60 * 24 * 7);  //有効期限を7日後としている
            } elseif (empty($_SESSION['login_keep'])) {     //$_SESSION[login_keep]が空の場合
                setcookie('mail', '', time() - 1);             //チェックを入れてないcookieのデータを削除する
                setcookie('password', '', time() - 1);         //time()-1で過去の時間を指定したこととなり
                setcookie('login_keep', '', time() - 1);       //cookieからデータを削除できる
            }
            header("Location:mypage.php");
        } else {
            $errors = "メールアドレスとパスワードを正しく入力して下さい。";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ログインページ</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <h1 class="form_title">ログインページ</h1>
    <form method="POST" action="">
        <div class="item">
            <label>メールアドレス</label>
            <input type="text" class="text" size="35" name="mail" value="<?php  //ログイン状態を保持するにチェックを入れていた場合メルアドとパスワードの入力項目にcookieから値を呼び出して表示させる
                                                                            if ($_COOKIE['login_keep'] ?? '') {
                                                                                echo $_COOKIE['mail'];
                                                                            }
                                                                            ?>">
        </div>
        <div class="item">
            <label>パスワード</label>
            <input type="password" class="text" size="35" name="password" value="<?php
                                                                                    if ($_COOKIE['login_keep'] ?? '') {
                                                                                        echo $_COOKIE['password'];
                                                                                    }
                                                                                    ?>">
            <?php if (!empty($errors)) : ?>
                <p class="err_message"><?php echo $errors; ?></p>
            <?php endif; ?>
        </div>
        <div class="item">
            <label>
                <input type="checkbox" name="login_keep" value="1" <?php
                                                                    if ($_COOKIE['login_keep'] ?? '') {
                                                                        echo "checked='checked'";   //前回チェック入れた場合自動的にチェックが入るようにする
                                                                    }
                                                                    ?>>ログイン情報を保持する
            </label>
        </div>

        <div class="item">
            <input type="submit" class="submit" value="ログイン">
        </div>
    </form>
</body>

</html>