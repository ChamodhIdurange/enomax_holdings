<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$name=$_POST['salesmanagername'];
$contact1=$_POST['contactone'];
$email=$_POST['email'];
$address=$_POST['address'];
$useraccount=$_POST['useraccount'];

$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $insertsupplier="INSERT INTO `tbl_sales_manager`(`salesmanagername`, `contactone`, `email`, `address`, `status`, `insertdatetime`,`tbl_user_idtbl_user`, `insertuser`) VALUES ('$name','$contact1','$email','$address','1', '$updatedatetime', '$useraccount','$userID')";
    if($conn->query($insertsupplier)==true){ 
        header("Location:../salesmanager.php?action=4");
    }   
}
else{
    $update="UPDATE `tbl_sales_manager` SET `salesmanagername`='$name',`contactone`='$contact1', `email`='$email',  `address`='$address', `updatedatetime`='$updatedatetime', `tbl_user_idtbl_user` = '$useraccount', `insertuser` = '$userID' WHERE `idtbl_sales_manager`='$recordID'";
    if($conn->query($update)==true){    
        header("Location:../salesmanager.php?action=6");
    }
    else{
        header("Location:../salesmanager.php?action=5");
    }
}
?>