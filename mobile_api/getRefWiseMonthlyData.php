<?php
require_once('dbConnect.php');

$currentYear = date('Y');
$currentMonth = date('m');

$sqlRef = "SELECT DISTINCT tbl_invoice.ref_id,tbl_user.name as refName FROM `tbl_invoice` INNER JOIN tbl_user ON tbl_invoice.ref_id=tbl_user.idtbl_user";
$resRef = mysqli_query($con, $sqlRef);
$result = array();

while ($rowRef = mysqli_fetch_array($resRef)) {

    $refId=$rowRef['ref_id'];
    $sql = "SELECT sum(tbl_invoice.total) as total,tbl_user.name as refName,month(tbl_invoice.date) as releventMonth from tbl_invoice INNER JOIN tbl_user ON tbl_invoice.ref_id=tbl_user.idtbl_user WHERE year(tbl_invoice.date)='$currentYear' AND tbl_invoice.ref_id='$refId' group by month(tbl_invoice.date)";
    $res = mysqli_query($con, $sql);
    $resultValue = array();

    while ($row = mysqli_fetch_array($res)) {

        array_push($resultValue, array("refName" => $row['refName'], "value" => $row['total'],"month" => $row['releventMonth']));
    }


    array_push($result, array("refName" => $rowRef['refName'], "data" =>$resultValue));
}

print(json_encode($result));
mysqli_close($con);
