<?php 
require_once('../connection/db.php');
$newarray = array();

$salesManagerId = $_POST['salesManagerId'];
$today = date('Y-m-d');

$getEmployees = "SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `tbl_sales_manager_idtbl_sales_manager` = '$salesManagerId'";
$resultEmployees = $conn->query($getEmployees);

if ($resultEmployees->num_rows > 0) {
    while ($rowEmployee = $resultEmployees->fetch_assoc()) {
        $empId = $rowEmployee['idtbl_employee'];
        $empName = $rowEmployee['name'];

        $getdailytot = "SELECT SUM(`u`.`nettotal`) AS 'dailyTot'
                FROM `tbl_invoice` AS `u`
                LEFT JOIN `tbl_customer_order` AS `ud` ON `u`.`tbl_customer_order_idtbl_customer_order` = `ud`.`idtbl_customer_order`
                WHERE `u`.`status`='1' 
                AND `u`.`paymentcomplete`='0'
                AND DATE(`u`.`date`) = CURDATE() 
                AND `ud`.`tbl_employee_idtbl_employee`='$empId'
                GROUP BY `ud`.`tbl_employee_idtbl_employee`";
        $resultgetdailytot = $conn->query($getdailytot);
        $dailytotal = 0; 

        if ($row = $resultgetdailytot->fetch_assoc()) {
            $dailytotal = $row['dailyTot'];
        }

        $sqlgetalltot = "SELECT SUM(`u`.`nettotal`) AS 'fullalltot'
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

        if($fullalltot == null){
            $fullalltot = 0;
        }
        if($dailytotal == null){
            $dailytotal = 0;
        }
        if($balance == null){
            $balance = 0;
        }
        $response = array(
            "empId"  => $empId,
            "empName"  => $empName,
            "fulltotal"  => $fullalltot,
            "dailytotal" => $dailytotal,
            "outstandingtotal" => $balance,
            "date"       => $today
        );

        $newarray[] = $response;
    }
} else {
    $newarray[] = array("error" => "No employees found");
}

echo json_encode($newarray);

?>
