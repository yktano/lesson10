<?php
session_start();    //SESSIONを使用するときは宣言が必要
mb_internal_encoding("utf8");

//変数の初期化
$errors = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") { //アクセス方法がPOST通信かGET通信かを判別している
    //POST処理
    //エスケープ処理　不明なスクリプトが埋め込まれていないかXSS(クロスサイトスクリプティング)対策を行う
    $_SESSION["name"] = htmlentities($_POST["name"] ?? "", ENT_QUOTES);
    $_SESSION["mail"] = htmlentities($_POST["mail"] ?? "", ENT_QUOTES);
    $_SESSION["age"] = htmlentities($_POST["age"] ?? "", ENT_QUOTES);
    $_SESSION["password"] = htmlentities($_POST["password"] ?? "", ENT_QUOTES);
    $_SESSION["comments"] = htmlentities($_POST["comments"] ?? "", ENT_QUOTES);
    //バリデーションチェック
    $errors = validate_form();
    if (empty($errors)) {
        //エラーが無ければ、完了ページに遷移
        header("Location:confirm.php");
    }
}

//バリデーションチェックを行う関数
function validate_form()
{
    //エラーメッセージを初期化
    $form_errors = array();

    //１．氏名のバリデーション
    $input["name"] = trim($_POST["name"] ?? ""); //$_POSTが設定されていないときに備えて、null合体演算子で対応
    if (strlen($input["name"]) == 0) {  //入力させれているかの確認
        $form_errors["name"] = "氏名を入力して下さい";
    }

    //２．メールのバリデーション
    $input["mail"] = filter_input(INPUT_POST, "mail", FILTER_VALIDATE_EMAIL);
    if (!$input["mail"]) {
        $form_errors["mail"] = "メールアドレスは正しい形で入力して下さい";
    }
    //３．年齢のバリデーション
    $options = array(
        "options" => array(
            "min_range" => 18,
            "max_range" => 65,
        )
    );
    $input["age"] = filter_input(INPUT_POST, "age", FILTER_VALIDATE_INT, $options);   //整数かどうかのチェック
    if (is_null($input["age"]) || $input["age"] === false) {
        $form_errors["age"] = "年齢は18歳以上、65歳以下で入力して下さい";
    }

    //４．コメントのバリデーション
    $input["comments"] = trim($_POST["comments"] ?? "");  //$_POSTが設定されていないときに備えて、null合体演算子で対応
    if (strlen($input["comments"]) == 0) {    //入力されているかの確認
        $form_errors["comments"] = "コメントを入力して下さい";
    }

    //５．パスワードのバリテーション
    $input["password"] = trim($_POST["password"] ?? "");   //$_POSTが設定されていないときに備えてnull合体演算子で対応
    if (strlen($input["password"]) == 0) {      //入力されているかの確認
        $form_errors["password"] = "パスワードを入力して下さい";
    }

    return $form_errors;
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>フォームを作る</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <h1 class="form_title">登録フォーム</h1>
    <form method="POST" action="">
        <div class="item">
            <label>名前</label>
            <input type="text" class="text" size="35" name="name" value="<?php echo $_SESSION["name"] ?? ""; ?>">
            <?php if (!empty($errors["name"])) : ?>
                <p class="err_message"><?php echo $errors["name"]; ?></p>
            <?php endif; ?>
        </div>
        <div class="item">
            <label>メールアドレス</label>
            <input type="text" class="text" size="35" name="mail" value="<?php echo $_SESSION["mail"] ?? ""; ?>">
            <?php if (!empty($errors["mail"])) : ?>
                <p class="err_message"><?php echo $errors["mail"]; ?></p>
            <?php endif; ?>
        </div>
        <div class="item">
            <label>年齢</label>
            <input type="number" class="text" size="35" name="age" value="<?php echo $_SESSION["age"] ?? ""; ?>">
            <?php if (!empty($errors["age"])) : ?>
                <p class="err_message"><?php echo $errors["age"]; ?></p>
            <?php endif; ?>
        </div>
        <div class="item">
            <label>パスワード</label>
            <input type="password" class="text" size="35" name="password" value="<?php echo $_SESSION["password"] ?? ""; ?>">
            <?php if (!empty($errors["password"])) : ?>
                <p class="err_message"><?php echo $errors["password"]; ?></p>
            <?php endif; ?>
        </div>
        <div class="item">
            <label>コメント</label>
            <textarea cols="35" rows="7" name="comments"><?php echo $_SESSION["comments"] ?? ""; ?></textarea>
            <?php if (!empty($errors["comments"])) : ?>
                <p class="err_message"><?php echo $errors["comments"]; ?></p>
            <?php endif; ?>
        </div>
        <div class="item">
            <input type="submit" class="submit" value="入力内容を確認する">
        </div>
    </form>


</body>

</html>