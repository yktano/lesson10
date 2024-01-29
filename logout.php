<?php
session_start();
//セッション変数を全て解除する
$_SESSION = array();
if (isset($_COOKIE[session_name()])) {      //全てのsessionを削除（無効化）ログアウトすることが出来る
    setcookie(session_name(), "", time() - 1800, "/");   //sessionIDの削除
}
session_destroy();  //セッションの破棄
header("Location:login.php");
