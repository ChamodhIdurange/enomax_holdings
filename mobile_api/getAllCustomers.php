<?php
require_once('dbConnect.php');
$arrayinvoice = array();

$sql = "SELECT `idtbl_customer`,`name`, `nic`, `phone`, `email`, `address`, `vat_num`, `s_vat`, `numofvisitdays`, `creditlimit`, `credittype`, `creditperiod`, `emergencydate`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_area_idtbl_area` FROM `tbl_customer` WHERE `status`=1";
$res = mysqli_query($con, $sql);
$result = array();
while ($row = mysqli_fetch_array($res)) {

    $cusId = $row['idtbl_customer'];
    $lastVisitDate = "00/00/000";
    $sqlLastInv = "SELECT ifnull(date(`updatedatetime`),'') as lastDate FROM `tbl_porder_otherinfo` WHERE `customerid`='$cusId' ORDER BY `porderid` DESC LIMIT 1";
    $resLastInv = mysqli_query($con, $sqlLastInv);
    $rowLastInv = mysqli_fetch_array($resLastInv);
    if ($rowLastInv) {
        $lastVisitDate = $rowLastInv['lastDate'];
    }

    $sqlOutstanding = "SELECT `tbl_invoice`.`idtbl_invoice`, `tbl_invoice`.`date`, `tbl_invoice`.`total`, `tbl_invoice_payment_has_tbl_invoice`.`payamount` FROM `tbl_invoice` LEFT JOIN `tbl_invoice_payment_has_tbl_invoice` ON `tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_idtbl_invoice`=`tbl_invoice`.`idtbl_invoice` WHERE `tbl_invoice`.`tbl_customer_idtbl_customer`='$cusId' AND `tbl_invoice`.`status`=1 AND `tbl_invoice`.`paymentcomplete`=0 ";
    $resOutstanding = mysqli_query($con, $sqlOutstanding);
    $resultOutstanding = array();

    $outstanding = 0;
    $totalAmount = 0;
    $payAmount = 0;

    while ($rowOutstanding = mysqli_fetch_array($resOutstanding)) {

        $totalAmount = $totalAmount + $rowOutstanding['total'];
        $payAmount = $payAmount + $rowOutstanding['payamount'];
    }
    $outstanding = $totalAmount - $payAmount;

    $sqlLastInvNo = "SELECT `idtbl_invoice` FROM `tbl_invoice` WHERE `tbl_customer_idtbl_customer`='$cusId' ORDER BY `idtbl_invoice` DESC LIMIT 1";
    $resLastInvNo = mysqli_query($con, $sqlLastInvNo);
    $rowLastInvNo = mysqli_fetch_array($resLastInvNo);
    $lastInvNo="0";
    if ($rowLastInvNo) {
        $lastInvNo = $rowLastInvNo['idtbl_invoice'];
    }

    array_push($result, array("id" => $row['idtbl_customer'], "shop_name" => $row['name'], "mobile" => $row['phone'],  "address" => $row['address'], "creditlimit" => $row['creditlimit'], "outStanding" => "$outstanding", "visitStatus" => "1", "LastVisitDate" => "$lastVisitDate", "lastInvNo" => "$lastInvNo"));
}

print(json_encode($result));
mysqli_close($con);
