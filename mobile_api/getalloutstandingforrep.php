<?php
require_once('dbConnect.php');

$empId=$_POST["empId"];


$sql = "SELECT `uc`.`address`, `uc`.`idtbl_customer`, `uc`.`name`, `u`.`invoiceno`, COALESCE(SUM(`u`.`nettotal`), 0) AS 'totalamount', COALESCE(SUM(`ue`.`payamount`), 0) AS 'totpayedamount'
        FROM `tbl_invoice` AS `u`
        LEFT JOIN `tbl_customer_order` AS `ud` ON `u`.`tbl_customer_order_idtbl_customer_order` = `ud`.`idtbl_customer_order`
        LEFT JOIN `tbl_invoice_payment_has_tbl_invoice` AS `ue` ON `ue`.`tbl_invoice_idtbl_invoice` = `u`.`idtbl_invoice`
        LEFT JOIN `tbl_customer` AS `uc` ON `uc`.`idtbl_customer` = `u`.`tbl_customer_idtbl_customer`
        WHERE `u`.`status`='1' 
        AND `u`.`paymentcomplete`='0'
        AND `ud`.`tbl_employee_idtbl_employee`='$empId'
        GROUP BY `u`.`idtbl_invoice`";
$result = mysqli_query($con, $sql);
$dataarray = array();

while ($row = mysqli_fetch_array($result)) {
    array_push($dataarray, array("customerId" => $row['idtbl_customer'], "invoiceno" => $row['invoiceno'], "customername" => $row['name'], "fulltot" => $row['totalamount'], "payedamount" => $row['totpayedamount'], "address" => $row['address']));
}
echo json_encode($dataarray);

?>