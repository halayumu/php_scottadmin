<?php

/**
 * PH34 課題2 マスタテーブル管理  *
 * @author Ayumu ISHIDA
 *
 * ファイル名=empDelete.php
 * フォルダ=/ph34/scottadmin/public/emp/ 
 */
require_once($_SERVER["DOCUMENT_ROOT"] . "/ph34/scottadmin/classes/Conf.php"); //サーバー変数
require_once($_SERVER["DOCUMENT_ROOT"] . "/ph34/scottadmin/classes/entity/Emp.php"); //エンティティ

$deleteEmpId = $_POST["deleteEmpId"];

try {
    $db = new PDO(Conf::DB_DNS, Conf::DB_USERNAME, Conf::DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    $sql = "DELETE FROM emps WHERE id = :id";

    $stmt = $db->prepare($sql);
    $stmt->bindValue(":id", $deleteEmpId, PDO::PARAM_INT);
    $result = $stmt->execute();
    if (!$result) {
        $_SESSION["errorMsg"] = "情報削除に失敗しました。もう一度はじめからやり直してください。";
    }
} catch (PDOException $ex) {
    var_dump($ex);
    $_SESSION["errorMsg"] = "DB接続に失敗しました。";
} finally {
    $db = null;
}
if (isset($_SESSION["errorMsg"])) {
    header("Location: /ph34/scottadmin/public/error.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Shinzo SAITO">
    <title>従業員情報削除完了 | ScottAdmin Sample</title>
    <link rel="stylesheet" href="/ph34/scottadmin/public/css/main.css" type="text/css">
</head>

<body>
    <h1>従業員情報削除完了</h1>
    <nav id="breadcrumbs">
        <ul>
            <li><a href="/ph34/scottadmin/public/">TOP</a></li>
            <li><a href="/ph34/scottadmin/public/emp/showEmpList.php">従業員リスト</a></li>
            <li>従業員情報削除確認</li>
            <li>従業員情報削除完了</li>
        </ul>
    </nav>
    <section>
        <p>
            従業員ID<?= $deleteEmpId = $_POST["deleteEmpId"]; ?>の情報を削除しました。
        </p>
        <p>
            従業員リストに<a href="/ph34/scottadmin/public/emp/showEmpList.php">戻る</a>
        </p>
    </section>
</body>

</html>