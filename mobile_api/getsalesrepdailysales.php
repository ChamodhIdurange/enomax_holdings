<?php 
require_once('../connection/db.php');
$array = array();

$empId = $_POST['empId'];
$today = date('Y-m-d');

$getdailytot = "SELECT SUM(`ud`.`nettotal`) AS 'dailyTot'
                FROM `tbl_original_customer_order` AS `ud`
                LEFT JOIN `tbl_customer_order` AS `u` ON `u`.`idtbl_customer_order` = `ud`.`tbl_customer_order_idtblcustomer_order`
                WHERE `u`.`status`='1' 
                AND DATE(`ud`.`date`) = CURDATE() 
                AND `ud`.`tbl_employee_idtbl_employee`='$empId'
                GROUP BY `ud`.`tbl_employee_idtbl_employee`";

$resultgetdailytot = $conn->query($getdailytot);
$dailytotal = 0; 

if ($row = $resultgetdailytot->fetch_assoc()) {
    $dailytotal = $row['dailyTot'];
}


// $sqlgetalltot = "SELECT SUM(`ud`.`nettotal`) AS 'fullalltot'
//         FROM `tbl_customer_order` AS `ud`
//         WHERE `ud`.`status`='1' 
//         AND MONTH(`ud`.`date`) = MONTH(CURDATE()) 
//         AND `ud`.`tbl_employee_idtbl_employee`='$empId'
//         GROUP BY `ud`.`tbl_employee_idtbl_employee`";

$sqlgetalltot = "SELECT SUM(`ud`.`nettotal`) AS 'fullalltot'
        FROM `tbl_invoice` AS `u`
        LEFT JOIN `tbl_customer_order` AS `ud` ON `u`.`tbl_customer_order_idtbl_customer_order` = `ud`.`idtbl_customer_order`
        WHERE `u`.`status`='1' 
        AND `u`.`paymentcomplete`='0'
        AND MONTH(`u`.`date`) = MONTH(CURDATE()) 
        AND `ud`.`tbl_employee_idtbl_employee`='$empId'
        GROUP BY `ud`.`tbl_employee_idtbl_employee`";

$resultgetalltot = $conn->query($sqlgetalltot);
$fullalltot = 0; 

if ($row = $resultgetalltot->fetch_assoc()) {
    $fullalltot = $row['fullalltot'];
}
$sqlpayedamount = "SELECT COALESCE(SUM(`ue`.`payamount`), 0) AS 'totpayedamount'
        FROM `tbl_invoice` AS `u`
        LEFT JOIN `tbl_customer_order` AS `ud` ON `u`.`tbl_customer_order_idtbl_customer_order` = `ud`.`idtbl_customer_order`
        LEFT JOIN `tbl_invoice_payment_has_tbl_invoice` AS `ue` ON `ue`.`tbl_invoice_idtbl_invoice` = `u`.`idtbl_invoice`
        WHERE `u`.`status`='1' 
        AND `u`.`paymentcomplete`='0'
        AND MONTH(`u`.`date`) = MONTH(CURDATE()) 
        AND `ud`.`tbl_employee_idtbl_employee`='$empId'
        GROUP BY `ud`.`tbl_employee_idtbl_employee`";

$resultpayedamount = $conn->query($sqlpayedamount);
$fullpayedamount = 0; 

if ($row = $resultpayedamount->fetch_assoc()) {
    $fullpayedamount = $row['totpayedamount'];
}
$balance = $fullalltot - $fullpayedamount;
$response = array(
    "fulltotal"  => $fullalltot,
    "dailytotal" => $dailytotal,
    "outstandingtotal" => $balance,
    "date"       => $today
);
print(json_encode($response));

?>