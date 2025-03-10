<?php 
require_once('../connection/db.php');

$customerId=$_POST['customerId'];

$sql="SELECT `idtbl_invoice`, `invoiceno` FROM `tbl_invoice` WHERE `status`=1 AND `tbl_customer_idtbl_customer` = '$customerId'";
$result=$conn->query($sql);

$arraylist=array();

while($row=$result->fetch_assoc()){
    $obj=new stdClass();
    $obj->invoiceId=$row['idtbl_invoice'];
    $obj->invoiceNo=$row['invoiceno'];
    
    array_push($arraylist, $obj);
}



echo json_encode($arraylist);
?>