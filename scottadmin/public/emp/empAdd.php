<?php

/**
 * PH34 課題2 マスタテーブル管理  *
 * @author Ayumu ISHIDA
 *
 * ファイル名=empAdd.php
 * フォルダ=/ph34/scottadmin/public/emp/ 
 */
require_once($_SERVER["DOCUMENT_ROOT"] . "/ph34/scottadmin/classes/Conf.php"); //サーバー変数
require_once($_SERVER["DOCUMENT_ROOT"] . "/ph34/scottadmin/classes/entity/Emp.php"); //エンティティ

//---------------------------[入力値取得処理]----------------------------//
$addemNo = $_POST["addEmNo"]; //従業員番号
$addDpName = $_POST["addEmName"]; //従業員名
$addemJob = $_POST["addEmJob"]; //役所
$addemMgr = $_POST["addEmMgr"]; //上司番号
$addemHiredate = $_POST["addEmHiredate"]; //雇用日
$addemSal = $_POST["addEmSal"]; //給料
$adddeptId = $_POST["addDeptId"]; //所属部門ID

//----[入力前後空白削除処理]---//
$addDpName = trim($addDpName); //従業員名
$addemJob = trim($addemJob); //役所

//-----------------------[empインスタンス保管処理]-------------------//
$emp = new Emp();
$emp->setemNo($addemNo); //従業員番号
$emp->setemName($addDpName); //従業員名
$emp->setemJob($addemJob); //役所
$emp->setemMgr($addemMgr); //上司番号
$emp->setemHiredate($addemHiredate); //雇用日
$emp->setemSal($addemSal); //給料
$emp->setdeptId($adddeptId); //所属部門ID

$validationMsgs = []; //検証メッセージ挿入素材
try {
    //-------------------------------[DB処理]-------------------------//
    //----[接続処理]----//
    $db = new PDO(Conf::DB_DNS, Conf::DB_USERNAME, Conf::DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    //----[SQL文作成]----//
    $sqlSelect = "SELECT COUNT(*) count FROM  emps WHERE em_no = :em_no";
    $sqlInsert = "INSERT INTO emps (em_no, em_name, em_job, em_mgr, em_hiredate, em_sal, dept_id)
    VALUES (:em_no, :em_name, :em_job, :em_mgr, :em_hiredate, :em_sal, :dept_id)";

    //----[sql文実行処理]----//
    $stmt = $db->prepare($sqlSelect);
    $stmt->bindValue(":em_no", $emp->getemNo(), PDO::PARAM_INT);
    $result = $stmt->execute();

    //----[従業員番号被り判定処理]----//
    $count = 1;
    if ($result && $row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $count = $row["count"];
    }
    if ($count > 0) {
        $validationMsgs[] = "その従業員番号はすでに使われています。別のものを指定してください。";
    }

    //----[インサート処理]----//
    if (empty($validationMsgs)) { //バリデーションメッセージが空だったらtru//入ってたら
        $stmt = $db->prepare($sqlInsert);
        $stmt->bindValue(":em_no", $emp->getemNo(), PDO::PARAM_INT);
        $stmt->bindValue(":em_name", $emp->getemName(), PDO::PARAM_STR);
        $stmt->bindValue(":em_job", $emp->getemJob(), PDO::PARAM_STR);
        $stmt->bindValue("em_mgr", $emp->getemMgr(), PDO::PARAM_INT);
        $stmt->bindValue("em_hiredate", $emp->getemHiredate(), PDO::PARAM_STR);
        $stmt->bindValue("em_sal", $emp->getemSal(), PDO::PARAM_INT);
        $stmt->bindValue("dept_id", $emp->getdeptId(), PDO::PARAM_INT);
        $result = $stmt->execute();

        //----[登録データ取得]----//
        if ($result) {
            $epId = $db->lastInsertId();
        } else {
            $_SESSION["errorMsg"] =
                "情報登録に失敗しました。もう一度はじめからやり直してください。";
        }
    } else { //バリデーションに文字が入ってたら
        $_SESSION["emp"] = serialize($emp); //入力した値が入ってくる//serializeはインスタンスかした値を代入可能関数 
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
    header("Location: /ph34/scottadmin/public/emp/goEmpAdd.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="author" content="Shinzo SAITO">
  <title>従業員情報追加完了 | ScottAdmin Sample</title>
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
      <dt>ID(自動生成)</dt>
      <dd><?= $epId ?></dd>
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