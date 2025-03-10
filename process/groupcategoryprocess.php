<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$category=addslashes($_POST['category']);
$maincategory=addslashes($_POST['maincategory']);

$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $insert="INSERT INTO `tbl_group_category`(`category`, `status`, `updatedatetime`, `tbl_user_idtbl_user`,`tbl_product_category_idtbl_product_category`) VALUES ('$category','1','$updatedatetime','$userID','$maincategory')";
    if($conn->query($insert)==true){        
        header("Location:../groupcategory.php?action=4");
    }
    else{header("Location:../groupcategory.php?action=5");}
}
else{
    $update="UPDATE `tbl_group_category` SET `category`='$category',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID', `tbl_product_category_idtbl_product_category` = '$maincategory'  WHERE `idtbl_group_category`='$recordID'";
    if($conn->query($update)==true){     
        header("Location:../groupcategory.php?action=6");
    }
    else{header("Location:../groupcategory.php?action=5");}
}
?>