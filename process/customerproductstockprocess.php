<?php
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$productID=$_POST['productID'];
$fullqty=$_POST['fullqty'];
$emptyqty=$_POST['emptyqty'];
$hidecusid=$_POST['hidecusid'];

$updatedatetime=date('Y-m-d h:i:s');

$query = "INSERT INTO `tbl_customer_stock`(`fullqty`, `emptyqty`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`, `tbl_product_idtbl_product`) VALUES ('$fullqty','$emptyqty','1','$updatedatetime','$userID','$hidecusid','$productID')";
if($conn->query($query)==true){
    $actionObj=new stdClass();
    $actionObj->icon='fas fa-check-circle';
    $actionObj->title='';
    $actionObj->message='Add Successfully';
    $actionObj->url='';
    $actionObj->target='_blank';
    $actionObj->type='success';

    echo $actionJSON=json_encode($actionObj);
}
else{
    $actionObj=new stdClass();
    $actionObj->icon='fas fa-exclamation-triangle';
    $actionObj->title='';
    $actionObj->message='Record Error';
    $actionObj->url='';
    $actionObj->target='_blank';
    $actionObj->type='danger';

    echo $actionJSON=json_encode($actionObj);
}

?>