<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:../index.php");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];
$record=$_GET['record'];
$type=$_GET['type'];

$updatedatetime=date('Y-m-d h:i:s');

if($type==1){$value=1;}
else if($type==2){$value=2;}
else if($type==3){$value=3;}

$sqlcheck="SELECT `payment_complete` FROM `tbl_gl_payments` WHERE `id` IN (SELECT `tbl_gl_payment_id` FROM `tbl_gl_payment_details` WHERE `porderpaymentid`='$record')";
$resultcheck=$conn->query($sqlcheck);
$rowcheck=$resultcheck->fetch_assoc();

if($rowcheck['payment_complete']==0){
    $sql="UPDATE `tbl_porder_payment` SET `status`='$value',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_porder_payment`='$record'";
    if($conn->query($sql)==true){
        $updatepaymentdetail="UPDATE `tbl_porder_payment_detail` SET `status`='$value',`tbl_user_idtbl_user`='$userID' WHERE `tbl_porder_payment_idtbl_porder_payment`='$record'";
        $conn->query($updatepaymentdetail);

        $updateglpaymentinfo="UPDATE `tbl_gl_payment_details` SET `payment_cancel`='1', `updated_by`='$userID', `updated_at`='$updatedatetime' WHERE `porderpaymentid`='$record'";
        $conn->query($updateglpaymentinfo);

        header("Location:../paymenttoaccount.php?action=$type");
    }
    else{header("Location:../paymenttoaccount.php?action=5");}
}
else{header("Location:../paymenttoaccount.php?action=5");}
?>