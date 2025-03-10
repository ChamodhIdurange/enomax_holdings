<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:../index.php");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];
$record=$_POST['cusproductID'];
$type='3';

if($type==1){$value=1;}
else if($type==2){$value=2;}
else if($type==3){$value=3;}

$sql="UPDATE `tbl_customer_product` SET `status`='$value',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_customer_product`='$record'";
if($conn->query($sql)==true){
    $actionObj=new stdClass();
    $actionObj->icon='fas fa-trash-alt';
    $actionObj->title='';
    $actionObj->message='Record Delete Successfully';
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