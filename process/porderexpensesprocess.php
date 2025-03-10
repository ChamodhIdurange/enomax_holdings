<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$expentype=$_POST['expentype'];
$expenvalue=$_POST['expenvalue'];
$orderID=$_POST['orderID'];

$updatedatetime=date('Y-m-d h:i:s');

$insretorder="INSERT INTO `tbl_porder_has_tbl_expences_type`(`tbl_porder_idtbl_porder`, `tbl_expences_type_idtbl_expences_type`, `expencevalue`, `status`, `updatedatetime`, `tbl_user_idtbl_user`) VALUES ('$orderID','$expentype','$expenvalue','1','$updatedatetime','$userID')";
if($conn->query($insretorder)==true){
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