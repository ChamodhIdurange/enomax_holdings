<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$name=$_POST['suppliername'];
$contact1=$_POST['contactone'];
$contact2=$_POST['contacttwo'];
$email=$_POST['email'];
$address=$_POST['address'];


$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){

    $insertsupplier="INSERT INTO `tbl_supplier`(`suppliername`, `contactone`, `contacttwo`, `email`, `address`, `status`, `insertdatetime`,`tbl_user_idtbl_user`) VALUES ('$name','$contact1','$contact2','$email','$address','2', '$updatedatetime', '$userID')";
    if($conn->query($insertsupplier)==true){ 
        header("Location:../supplier.php?action=4");
    }   

    
}
else{
   
    $update="UPDATE `tbl_supplier` SET `suppliername`='$name',`contactone`='$contact1',`contacttwo`='$contact2', `email`='$email',  `address`='$address', `updatedatetime`='$updatedatetime', `tbl_user_idtbl_user` = '$userID' WHERE `idtbl_supplier`='$recordID'";

    if($conn->query($update)==true){    
        header("Location:../supplier.php?action=6");
    }
    else{header("Location:../supplier.php?action=5");}
}
?>