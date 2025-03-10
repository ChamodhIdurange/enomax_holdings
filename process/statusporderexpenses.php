<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:../index.php");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];
$record=$_POST['expensesid'];

$sql="UPDATE `tbl_porder_has_tbl_expences_type` SET `status`='3',`tbl_user_idtbl_user`='$userID' WHERE `expensesvalueid`='$record'";
if($conn->query($sql)==true){
    $actionObj=new stdClass();
    $actionObj->icon='fas fa-trash-alt';
    $actionObj->title='';
    $actionObj->message='Record Remove Successfully';
    $actionObj->url='';
    $actionObj->target='_blank';
    $actionObj->type='danger';

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