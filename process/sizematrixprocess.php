<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$name=$_POST['name'];
$sequence=$_POST['sequence'];
$category=$_POST['category'];

$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){

    $insertsizematrix="INSERT INTO `tbl_sizes`(`name`, `sequence`, `status`, `insertdatetime`,`tbl_user_idtbl_user`, `tbl_size_categories_idtbl_size_categories`) VALUES ('$name','$sequence', '1', '$updatedatetime', '$userID', '$category')";
    if($conn->query($insertsizematrix)==true){ 
        header("Location:../sizematrix.php?action=4");
    }else{
        header("Location:../sizematrix.php?action=5");
    }   
    
}
else{
   
    $update="UPDATE `tbl_sizes` SET `name`='$name',`sequence`='$sequence', `updatedatetime`='$updatedatetime', `tbl_user_idtbl_user` = '$userID', `tbl_size_categories_idtbl_size_categories` = '$category' WHERE `idtbl_sizes`='$recordID'";

    if($conn->query($update)==true){    
        header("Location:../sizematrix.php?action=6");
    }
    else{header("Location:../sizematrix.php?action=5");}
}
?>