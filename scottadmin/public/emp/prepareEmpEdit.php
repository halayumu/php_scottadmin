<?php

/**
 * PH34 サンプル3 マスタテーブル管理 Src07/12
 * 部門情報登録画面表示。
 *
 * @author Shinzo SAITO
 *
 * ファイル名=prepareEmpEdit.php
 * フォルダ=/ph34/scottadmin/public/emp/
 */

require_once($_SERVER["DOCUMENT_ROOT"] . "/ph34/scottadmin/classes/Conf.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/ph34/scottadmin/classes/entity/Emp.php");

$emp = new Emp();
$validationMsgs = null;

//-------------------------------[DB処理]-------------------------//
if (isset($_POST["editEmpId"])) {
    $editEmpId = $_POST["editEmpId"];
    try {
        //----[接続処理]----//
        $db = new PDO(Conf::DB_DNS, Conf::DB_USERNAME, Conf::DB_PASSWORD);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        //----[SQL構文作成]----//
        $sql = "SELECT * FROM emps WHERE id = :id";

        //----[sql文実行処理]----//
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":id", $editEmpId, PDO::PARAM_INT);
        $result = $stmt->execute();

        //----[個人データ取得]----//
        if ($result && $row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $id = $row["id"];
            $emNo = $row["em_no"];
            $emName = $row["em_name"];
            $emJob = $row["em_job"];
            $emMgr = $row["em_mgr"];
            $emHiredate = $row["em_hiredate"];
            $emSal = $row["em_sal"];
            $deptId = $row["dept_id"];

            $emp = new Emp();
            $emp->setId($id);
            $emp->setemNo($emNo);
            $emp->setemName($emName);
            $emp->setemJob($emJob);
            $emp->setemMgr($emMgr);
            $emp->setemHiredate($emHiredate);
            $emp->setemSal($emSal);
            $emp->setdeptId($deptId);
        } else {
            $_SESSION["errorMsg"] = "部門情報の取得に失敗しました。";
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
} else { //----[empデータ削除]----//
    if (isset($_SESSION["emp"])) {
        $emp = $_SESSION["emp"];
        $emp = unserialize($emp);
        unset($_SESSION["emp"]);
    } //----[検証データ削除]----//
    if (isset($_SESSION["validationMsgs"])) {
        $validationMsgs = $_SESSION["validationMsgs"];
        unset($_SESSION["validationMsgs"]);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/ph34/scottadmindao/public/css/main.css" type="text/css">

    <title>Document</title>
</head>

</html>

<body>
    <h1>従業員情報編集</h1>
    <nav id="breadcrumbs">
        <ul>
            <li><a href="/ph34/scottadmin/public/">TOP</a></li>
            <li><a href="/ph34/scottadmin/public/emp/showEmpList.php">従業員リスト</a></li>
            <li>従業員情報編集</li>
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
            情報を入力し、更新ボタンをクリックしてください。
        </p>
        <form action="/ph34/scottadmin/public/emp/empEdit.php" method="post" class="box">
            従業員ID:&nbsp;<?= $emp->getId() ?><br>
            <input type="hidden" name="editEmId" value="<?= $emp->getId() ?>">

            <label for="editEmNo">
                従業員番号&nbsp;<span class="required">必須</span>
                <input type="number" min="1000" max="9999" step="0" id="editEmNo" name="editEmNo" value="<?= $emp->getemNo() ?>" required>
            </label><br>

            <label for="editEmName">
                従業員名&nbsp;<span class="required">必須</span>
                <input type="text" id="editEmName" name="editEmName" value="<?= $emp->getemName() ?>" required>
            </label><br>

            <label for="editEmJob">
                役職&nbsp;<span class="required">必須</span>
                <input type="text" id="editEmJob" name="editEmJob" value="<?= $emp->getemJob() ?>" required>
            </label><br>

            <label for="editEmMgr">
                上司番号&nbsp;<span class="required">必須</span>
                <input type="number" min="0" max="9999" step="0" id="editEmMgr" name="editEmMgr" value="<?= $emp->getemMgr() ?>" required>
            </label><br>

            <label for="editEmHiredate">
                雇用日&nbsp;<span class="required">必須</span>
                <input type="date" id="editEmHiredate" name="editEmHiredate" value="<?= $emp->getemHiredate() ?>" required>
            </label><br>


            <label for="editEmSal">
                給料&nbsp;<span class="required">必須</span>
                <input type="number" min="0" max="" step="0" id="editEmSal" name="editEmSal" value="<?= $emp->getemSal() ?>" required>
            </label><br>


            <label for="editDeptId">
                所属部門ID&nbsp;<span class="required">必須</span>
                <input type="text" id="editDeptId" name="editDeptId" value="<?= $emp->getdeptId() ?>" required>
                <button type="submit">更新</button>
        </form>
    </section>
</body>

</html>