<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$name=$_POST['name'];

$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){

    $insertsizematrix="INSERT INTO `tbl_size_categories`(`name`, `status`, `insertdatetime`,`tbl_user_idtbl_user`) VALUES ('$name', '1', '$updatedatetime', '$userID')";
    if($conn->query($insertsizematrix)==true){ 
        header("Location:../sizecategories.php?action=4");
    }else{
        header("Location:../sizecategories.php?action=5");
    }   
    
}
else{
   
    $update="UPDATE `tbl_size_categories` SET `name`='$name', `updatedatetime`='$updatedatetime', `tbl_user_idtbl_user` = '$userID' WHERE `idtbl_size_categories`='$recordID'";

    if($conn->query($update)==true){    
        header("Location:../sizecategories.php?action=6");
    }
    else{header("Location:../sizecategories.php?action=5");}
}
?>