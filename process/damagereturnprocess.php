<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$customer = $_POST['customer'];
$returntype = $_POST['returntype'];
$product = $_POST['product'];
$qty = $_POST['qty'];
$today=date('Y-m-d');

$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $query = "INSERT INTO `tbl_damage_return`(`returntype`, `returndate`, `qty`, `comsendstatus`, `comsenddate`, `backstockstatus`, `backstockdate`, `returncusstatus`, `returncusdate`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`, `tbl_product_idtbl_product`) VALUES ('$returntype','$today','$qty','0','$today','0','$today','0','$today','1','$updatedatetime','$userID','$customer','$product')";
    if($conn->query($query)==true){header("Location:../damagereturn.php?action=4");}
    else{header("Location:../damagereturn.php?action=5");}
}
else{
    $query = "UPDATE `tbl_damage_return` SET `returntype`='$returntype',`qty`='$qty',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID',`tbl_customer_idtbl_customer`='$customer',`tbl_product_idtbl_product`='$product' WHERE `idtbl_damage_return`='$recordID'";
    if($conn->query($query)==true){header("Location:../damagereturn.php?action=6");}
    else{header("Location:../damagereturn.php?action=5");}
}
?>