<?php
require_once('../connection/db.php');

session_start();
$customerId=$_POST['customerId'];
$type=$_POST['type'];
$invoiceId=$_POST['invoiceId'];

$array = [];

if($type == 1){
    $sql="SELECT `p`.`product_name`, `p`.`idtbl_product`, `d`.`idtbl_invoice_detail`, `d`.`unitprice`, `d`.`saleprice` FROM `tbl_product` AS `p` LEFT JOIN `tbl_invoice_detail` AS `d` ON (`d`.`tbl_product_idtbl_product` = `p`.`idtbl_product`) LEFT JOIN `tbl_invoice` AS `i` ON (`i`.`idtbl_invoice` = `d`.`tbl_invoice_idtbl_invoice`) WHERE `d`.`tbl_invoice_idtbl_invoice` = '$invoiceId'";
    $result =$conn-> query($sql); 

    while ($row = $result-> fetch_assoc()) { 
        $obj=new stdClass();
        $obj->id=$row['idtbl_product'];
        $obj->name=$row['product_name'];
        $obj->invoiceid=$row['idtbl_invoice_detail'];
        $obj->unitprice=$row['unitprice'];
        $obj->saleprice=$row['saleprice'];
        array_push($array,$obj);
    }
}else if($type == 2){
    $sql="SELECT DISTINCT `i`.`idtbl_invoice` FROM `tbl_invoice_detail` AS `d` LEFT JOIN `tbl_invoice` AS `i` ON (`i`.`idtbl_invoice` = `d`.`tbl_invoice_idtbl_invoice`) WHERE `i`.`tbl_customer_idtbl_customer` = '$customerId' AND `i`.`status` = '1'";
    $result =$conn-> query($sql); 

    while ($row = $result-> fetch_assoc()) { 
        $obj=new stdClass();
        $obj->invoiceid=$row['idtbl_invoice'];
        $obj->name= 'Inv-' . $row['idtbl_invoice'];
        array_push($array,$obj);
    }
}


echo json_encode($array);
?>