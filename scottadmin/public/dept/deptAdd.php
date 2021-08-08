<?php

/**
 * PH34 サンプル3 マスタテーブル管理 Src08/12
 * 部門情報登録。
 *
 * @author Shinzo SAITO
 *
 * ファイル名=deptAdd.php
 * フォルダ=/ph34/scottadmin/public/dept/
 */
require_once($_SERVER["DOCUMENT_ROOT"] . "/ph34/scottadmin/classes/Conf.php"); //$_SERVER組込サーバ変数サーバの情報を取り出せる
require_once($_SERVER["DOCUMENT_ROOT"] . "/ph34/scottadmin/classes/entity/Dept.php");
//-------------------------------[入力値が入っている]-------------------------------
$addDpNo = $_POST["addDpNo"];
$addDpName = $_POST["addDpName"];
$addDpLoc = $_POST["addDpLoc"];
$addDpName = trim($addDpName); //前後の空白を取り除く関数全角は消せない
$addDpLoc = trim($addDpLoc); //
//-------------------------------[入力値をDeptインスタンスに保存処理]-------------------
$dept = new Dept();
$dept->setDpNo($addDpNo);
$dept->setDpName($addDpName);
$dept->setDpLoc($addDpLoc);

$validationMsgs = [];
try {
    //-------------------------------[DB接続処理]-----------------------------------
    $db = new PDO(Conf::DB_DNS, Conf::DB_USERNAME, Conf::DB_PASSWORD); //Conf定数はコロンで呼び出せる
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    //-------------------------------[構文作成]----------------------------------------
    $sqlSelect = "SELECT COUNT(*) count FROM depts WHERE dp_no = :dp_no";
    $sqlInsert = "INSERT INTO depts (dp_no, dp_name, dp_loc) VALUES (:dp_no, :dp_name,:dp_loc)";

    //-----------------------------------[構文実行処理]---------------------------------
    $stmt = $db->prepare($sqlSelect);
    $stmt->bindValue(":dp_no", $dept->getDpNo(), PDO::PARAM_INT);
    $result = $stmt->execute();
    $count = 1;
    if ($result && $row = $stmt->fetch(PDO::FETCH_ASSOC)) { //部門番号
        $count = $row["count"];
    }
    if ($count > 0) { //
        $validationMsgs[] = "その部門番号はすでに使われています。別のものを指定してください。";
    }

    if (empty($validationMsgs)) { //バリデーションメッセージが空だったらtru//入ってたら
        $stmt = $db->prepare($sqlInsert);
        $stmt->bindValue(":dp_no", $dept->getDpNo(), PDO::PARAM_INT);
        $stmt->bindValue(":dp_name", $dept->getDpName(), PDO::PARAM_STR);
        $stmt->bindValue(":dp_loc", $dept->getDpLoc(), PDO::PARAM_STR);
        $result = $stmt->execute();
        if ($result) {
            $dpId = $db->lastInsertId();
        } else {
            $_SESSION["errorMsg"] =
                "情報登録に失敗しました。もう一度はじめからやり直してください。";
        }
    } else { //バリデーションに文字が入ってたら
        $_SESSION["dept"] = serialize($dept); //入力した値が入ってくる//serializeはインスタンスかした値を代入可能関数
        $_SESSION["validationMsgs"] = $validationMsgs; //未入力でしたのメッセージを表示しる
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
} elseif (!empty($validationMsgs)) {
    header("Location: /ph34/scottadmin/public/dept/goDeptAdd.php");
    exit;
}

//コードの書き方ポイント
/**
 * 
 */
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Shinzo SAITO">
    <title>部門情報追加完了 | ScottAdmin Sample</title>
    <link rel="stylesheet" href="/ph34/scottadmin/public/css/main.css" type="text/css">
</head>

<body>
    <h1>部門情報追加完了</h1>
    <nav id="breadcrumbs">
        <ul>
            <li><a href="/ph34/scottadmin/">TOP</a></li>
            <li><a href="/ph34/scottadmin/public/dept/showDeptList.php">部門リスト</a></li>
            <li>部門情報追加</li>
            <li>部門情報追加完了</li>
        </ul>
    </nav>
    <section>
        <p>
            以下の部門情報を登録しました。
        </p>
        <dl>
            <dt>ID(自動生成)</dt>
            <dd><?= $dpId ?></dd>
            <dt>部門番号</dt>
            <dd><?= $dept->getDpNo() ?></dd>
            <dt>部門名</dt>
            <dd><?= $dept->getDpName() ?></dd>
            <dt>所在地</dt>
            <dd><?= $dept->getDpLoc() ?></dd>
        </dl>
        <p>
            部門リストに<a href="/ph34/scottadmin/public/dept/showDeptList.php">戻る</a>
        </p>
    </section>
</body>

</html>