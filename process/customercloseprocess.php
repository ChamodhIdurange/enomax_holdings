<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$customer=$_POST['customer'];
$total=$_POST['total'];
$tableData=$_POST['tableData'];
$today=date('Y-m-d');

$updatedatetime=date('Y-m-d h:i:s');

$insretdealerclose="INSERT INTO `tbl_customer_close`(`closedate`, `nettotal`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`) VALUES ('$today','$total','1','$updatedatetime','$userID','$customer')";
if($conn->query($insretdealerclose)==true){
    $dealercloseID=$conn->insert_id;

    foreach($tableData as $rowtabledata){
        $product=$rowtabledata['col_2'];
        $newprice=$rowtabledata['col_3'];
        $emptyprice=$rowtabledata['col_4'];
        $newqty=$rowtabledata['col_5'];
        $emptyqty=$rowtabledata['col_6'];
        $totalprice=$rowtabledata['col_7'];

        $insertdealerclosedetail="INSERT INTO `tbl_customer_close_detail`(`newprice`, `refillprice`, `newqty`, `refillqty`, `total`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`, `tbl_customer_close_idtbl_customer_close`) VALUES ('$newprice','$emptyprice','$newqty','$emptyqty','$totalprice','1','$updatedatetime','$userID','$product','$dealercloseID')";
        $conn->query($insertdealerclosedetail);
    }

    $cusclose="UPDATE `tbl_customer` SET `status`='5',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_customer`='$customer'";
    $conn->query($cusclose);

    $actionObj=new stdClass();
    $actionObj->icon='fas fa-check-circle';
    $actionObj->title='';
    $actionObj->message='Dealer Close Successfully';
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