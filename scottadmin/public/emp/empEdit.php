<?php

/**
 * PH34 サンプル3 マスタテーブル管理 Src07/12
 * 部門情報登録画面表示。
 *
 * @author Shinzo SAITO
 *
 * ファイル名=EmpEdit.php
 * フォルダ=/ph34/scottadmin/public/Emp/
 */

require_once($_SERVER["DOCUMENT_ROOT"] . "/ph34/scottadmin/classes/Conf.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/ph34/scottadmin/classes/entity/Emp.php");

//---------------------------[入力値取得処理]----------------------------//
$editEpId = $_POST["editEmId"]; //hidduのidを取得
$editEmNo = $_POST["editEmNo"]; //従業員番号
$editEmName = $_POST["editEmName"]; //従業員名
$editEmJob = $_POST["editEmJob"]; //役所
$editEmMgr = $_POST["editEmMgr"]; //上司番号
$editEmHiredate = $_POST["editEmHiredate"]; //雇用日
$editEmSal = $_POST["editEmSal"]; //給料
$editdeptId = $_POST["editDeptId"]; //所属部門ID

//----[入力前後空白削除処理]---//
$editEmName = trim($editEmName); //従業員名
$editEmJob = trim($editEmJob); //役所

//-----------------------[Empインスタンス保管処理]-------------------//
$emp = new Emp();
$emp->setId($editEpId);
$emp->setEmNo($editEmNo); //従業員番号
$emp->setEmName($editEmName); //従業員名
$emp->setEmJob($editEmJob); //役所
$emp->setEmMgr($editEmMgr); //上司番号
$emp->setEmHiredate($editEmHiredate); //雇用日
$emp->setEmSal($editEmSal); //給料
$emp->setdeptId($editdeptId); //所属部門ID

$validationMsgs = [];
try {
    //-----------------------[SQL処理]--------------------------//
    $db = new PDO(Conf::DB_DNS, Conf::DB_USERNAME, Conf::DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    //-----[SQL構文]----//
    $sqlSelect = "SELECT id FROM emps WHERE em_no = :em_no";
    $sqlUpdate = "UPDATE emps SET em_no = :em_no, em_name = :em_name, em_job = :em_job
    , em_mgr = :em_mgr , em_hiredate = :em_hiredate, em_sal = :em_sal, dept_id = :dept_id
    WHERE id = :id";

    //-----[構文実行]----//
    $stmt = $db->prepare($sqlSelect);
    $stmt->bindValue(":em_no", $emp->getemNo(), PDO::PARAM_INT);
    $result = $stmt->execute();

    $idInDB = 0;
    if ($result && $row = $stmt->fetch(PDO::FETCH_ASSOC)) { //dbで空き番号だったらこの処理は入らない
        $idInDB = $row["id"];
    }

    if ($idInDB > 0 && $idInDB != $editEpId) { //部門番号が被ってたらすでにあるメッセージを表示する
        $validationMsgs[] = "その部門番号はすでに使われています。別のものを指定してください。";
    }

    if (empty($validationMsgs)) {
        $stmt = $db->prepare($sqlUpdate);
        $stmt->bindValue(":em_no", $emp->getemNo(), PDO::PARAM_INT);
        $stmt->bindValue(":em_name", $emp->getemName(), PDO::PARAM_STR);
        $stmt->bindValue(":em_job", $emp->getemJob(), PDO::PARAM_STR);
        $stmt->bindValue("em_mgr", $emp->getemMgr(), PDO::PARAM_INT);
        $stmt->bindValue("em_hiredate", $emp->getemHiredate(), PDO::PARAM_STR);
        $stmt->bindValue("em_sal", $emp->getemSal(), PDO::PARAM_INT);
        $stmt->bindValue("dept_id", $emp->getdeptId(), PDO::PARAM_INT);
        $stmt->bindValue("id", $emp->getId(), PDO::PARAM_INT);
        $result = $stmt->execute();
        if (!$result) {
            $_SESSION["errorMsg"] =
                "情報更新に失敗しました。もう一度はじめからやり直してください。";
        }
    } else {
        $_SESSION["emp"] = serialize($emp);
        $_SESSION["validationMsgs"] = $validationMsgs;
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
    header("Location: /ph34/scottadmin/public/dept/prepareEmpEdit.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Shinzo SAITO">
    <title>従業員情報編集完了 | ScottAdmin Sample</title>
    <link rel="stylesheet" href="/ph34/scottadmin/public/css/main.css" type="text/css">
</head>

<body>
    <h1>従業員情報追加完了</h1>
    <nav id="breadcrumbs">
        <ul>
            <li><a href="/ph34/scottadmin/">TOP</a></li>
            <li><a href="/ph34/scottadmin/public/emp/showEmpList.php">管理リスト</a></li>
            <li>従業員情報追加</li>
            <li>従業員情報追加完了</li>
        </ul>
    </nav>
    <section>
        <p>
            以下の従業員情報を登録しました。
        </p>
        <dl>
            <bt>ID</bt>
            <dd><?= $emp->getid() ?></dd>
            <dt>従業員番号</dt>
            <dd><?= $emp->getemNo() ?></dd>
            <dt>従業員名</dt>
            <dd><?= $emp->getemName() ?></dd>
            <dt>役職</dt>
            <dd><?= $emp->getemJob() ?></dd>
            <dt>上司番号</dt>
            <dd><?= $emp->getemMgr() ?></dd>
            <dt>雇用日</dt>
            <dd><?= $emp->getemHiredate() ?></dd>
            <dt>給料</dt>
            <dd><?= $emp->getemSal() ?></dd>
            <dt>所属部門ID</dt>
            <dd><?= $emp->getdeptId() ?></dd>
        </dl>
        <p>
            部門リストに<a href="/ph34/scottadmin/public/emp/showEmpList.php">戻る</a>
        </p>
    </section>
</body>

</html>