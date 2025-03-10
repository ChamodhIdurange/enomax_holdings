<?php 
require_once('../connection/db.php');

$salesmanagerid = $_POST['salesmanagerid'];

$sql="SELECT * FROM `tbl_employee` WHERE `status`='1' AND `tbl_user_type_idtbl_user_type`='7' AND `tbl_sales_manager_idtbl_sales_manager`='$salesmanagerid'";
$result=$conn->query($sql);
$array = array();


while ($row = mysqli_fetch_array($result)) {
    $empid = $row['idtbl_employee'];
    $outstandingamount = 0;
    $payedamount = 0;
    $outstandingcount = 0;

    $sqloutstanding="SELECT SUM(`u`.`nettotal`) AS 'total', SUM(`u`.`idtbl_invoice`) AS 'invoice_count', SUM(`uf`.`payamount`) AS 'payed_amount'
        FROM `tbl_invoice` AS `u`
        LEFT JOIN `tbl_customer` AS `uc` ON `u`.`tbl_customer_idtbl_customer` = `uc`.`idtbl_customer`
        LEFT JOIN `tbl_customer_order` AS `ud` ON `u`.`tbl_customer_order_idtbl_customer_order` = `ud`.`idtbl_customer_order`
        LEFT JOIN `tbl_employee` AS `ue` ON `ud`.`tbl_employee_idtbl_employee` = `ue`.`idtbl_employee`
        LEFT JOIN `tbl_invoice_payment_has_tbl_invoice` AS `uf` ON `u`.`idtbl_invoice` = `uf`.`tbl_invoice_idtbl_invoice`
        WHERE `u`.`status`=1 
        AND `u`.`paymentcomplete`=0 AND `ue`.`idtbl_employee`='$empid'
        GROUP BY `ue`.`idtbl_employee`";
        
        $resultoutstanding=$conn->query($sqloutstanding);

        while ($rowout = mysqli_fetch_array($resultoutstanding)) {
            $outstandingamount =  $row['total'];
            $payedamount =  $row['payed_amount'];
            $outstandingcount =  $row['invoice_count'];


        }

    array_push($array, array( "id" => $row['idtbl_employee'], "name" => $row['name'], "phone" => $row['phone'], "address" => $row['address'], "outstandingcount" => $outstandingcount, "totaloutstanding" => $outstandingamount, "totalpayed" => $payedamount));
}

print(json_encode($array));

?>