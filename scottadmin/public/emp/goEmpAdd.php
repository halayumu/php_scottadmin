<?php

/**
 * PH34 課題2 マスタテーブル管理  *
 * @author Ayumu ISHIDA
 *
 * ファイル名=goEmpAdd.php
 * フォルダ=/ph34/scottadmin/public/emp/ 
 */
require_once($_SERVER["DOCUMENT_ROOT"] . "/ph34/scottadmin/classes/Conf.php"); //サーバー変数
require_once($_SERVER["DOCUMENT_ROOT"] . "/ph34/scottadmin/classes/entity/Emp.php"); //エンティティ

$emp = new Emp(); //入力データが入る
if (isset($_SESSION["emp"])) {
    $emp = $_SESSION["emp"];
    $emp = unserialize($emp); //下記とセットで使う
    unset($_SESSION["emp"]); //セッションを消してる
}
$validationMsgs = null;
if (isset($_SESSION["validationMsgs"])) {
    $validationMsgs = $_SESSION["validationMsgs"];
    unset($_SESSION["validationMsgs"]); //セッションの値を消す
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Shinzo SAITO">
    <title>従業員情報追加 | scottadmin Sample</title>
    <link rel="stylesheet" href="/ph34/scottadmin/public/css/main.css" type="text/css">
</head>

<body>
    <h1>従業員情報追加</h1>
    <nav id="breadcrumbs">
        <ul>
            <li><a href="/ph34/scottadmin/public/">TOP</a></li>
            <li><a href="/ph34/scottadmin/public/emp/showEmpList.php">従業員リスト</a></li>
            <li>従業員情報追加</li>
        </ul>
    </nav>
    <?php
    if (!is_null($validationMsgs)) {
    ?>
        <section id="errorMsg">
            <p>以下のメッセージをご確認ください。</p>
            <ul>
                <?php
                foreach ($validationMsgs as $msg) {
                ?>
                    <li><?= $msg ?></li>
                <?php
                }
                ?>
            </ul>
        </section>
    <?php
    }
    ?>
    <section>
        <p>
            情報を入力し、登録ボタンをクリックしてください。
        </p>
        <form action="/ph34/scottadmin/public/emp/empAdd.php" method="post" class="box">
            <label for="addEmNo">
                従業員番号&nbsp;<span class="required">必須</span>
                <input type="number" min="1000" max="9999" step="0" id="addEmNo" name="addEmNo" value="<?= $emp->getemNo() ?>" required>
            </label><br>

            <label for="addEmName">
                従業員名&nbsp;<span class="required">必須</span>
                <input type="text" id="addEmName" name="addEmName" value="<?= $emp->getemName() ?>" required>
            </label><br>

            <label for="addEmJob">
                役職&nbsp;<span class="required">必須</span>
                <input type="text" id="addEmJob" name="addEmJob" value="<?= $emp->getemJob() ?>" required>
            </label><br>

            <label for="addEmMgr">
                上司番号&nbsp;<span class="required">必須</span>
                <input type="number" min="0" max="9999" step="0" id="addEmMgr" name="addEmMgr" value="<?= $emp->getemMgr() ?>" required>
            </label><br>

            <label for="addEmHiredate">
                雇用日&nbsp;<span class="required">必須</span>
                <input type="date" id="addEmHiredate" name="addEmHiredate" value="<?= $emp->getemHiredate() ?>" required>
            </label><br>


            <label for="addEmSal">
                給料&nbsp;<span class="required">必須</span>
                <input type="number" min="0" max="" step="0" id="addEmSal" name="addEmSal" value="<?= $emp->getemSal() ?>" required>
            </label><br>


            <label for="addDeptId">
                所属部門ID&nbsp;<span class="required">必須</span>
                <input type="text" id="addDeptId" name="addDeptId" value="<?= $emp->getdeptId() ?>" required>
                <button type="submit">登録</button>
        </form>
    </section>
</body>

</html>