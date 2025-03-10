<?php
require_once('dbConnect.php');

$currentYear = date('Y');
$currentMonth = date('m');
$updatedatetime = date('Y-m-d');

$sqlDaily = "SELECT SUM(`tbl_invoice`.`total`) as total,tbl_user.name as refName FROM `tbl_invoice` INNER JOIN `tbl_user` ON `tbl_invoice`.`ref_id`=`tbl_user`.`idtbl_user` WHERE `tbl_invoice`.`date`='$updatedatetime' GROUP BY ref_id,date";
$resDay = mysqli_query($con, $sqlDaily);
$resultDay = array();

while ($rowDay = mysqli_fetch_array($resDay)) {

    array_push($resultDay, array("ref_id" => $rowDay['refName'], "value" => $rowDay['total']));
}

$sqlMonthly = "SELECT SUM(`tbl_invoice`.`total`) as total,tbl_user.name as refName FROM `tbl_invoice` INNER JOIN `tbl_user` ON `tbl_invoice`.`ref_id`=`tbl_user`.`idtbl_user` WHERE month(`tbl_invoice`.`date`)='$currentMonth' AND year(`tbl_invoice`.`date`)='$currentYear' GROUP BY `tbl_invoice`.`ref_id`";
$resMonthly= mysqli_query($con, $sqlMonthly);
$resultMonthly = array();

while ($rowMonthly = mysqli_fetch_array($resMonthly)) {

    array_push($resultMonthly, array("ref_id" => $rowMonthly['refName'], "value" => $rowMonthly['total']));
}

$sqlYearly = "SELECT SUM(`tbl_invoice`.`total`) as total,tbl_user.name as refName FROM `tbl_invoice` INNER JOIN `tbl_user` ON `tbl_invoice`.`ref_id`=`tbl_user`.`idtbl_user` WHERE year(`tbl_invoice`.`date`)='$currentYear' GROUP BY ref_id";
$resYearly= mysqli_query($con, $sqlYearly);
$resultYearly = array();

while ($rowYearly = mysqli_fetch_array($resYearly)) {

    array_push($resultYearly, array("ref_id" => $rowYearly['refName'], "value" => $rowYearly['total']));
}

$response = array("daily" => $resultDay, "yearly" => $resultYearly, "monthly" => $resultMonthly);
print_r(json_encode($response));

?>


