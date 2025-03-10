<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$usertype=addslashes($_POST['usertype']);
$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $insert="INSERT INTO `tbl_user_type`(`type`, `status`, `updatedatetime`) VALUES ('$usertype','1','$updatedatetime')";
    if($conn->query($insert)==true){        
        header("Location:../usertype.php?action=4");
    }
    else{header("Location:../usertype.php?action=5");}
}
else{
    $update="UPDATE `tbl_user_type` SET `type`='$usertype',`updatedatetime`='$updatedatetime' WHERE `idtbl_user_type`='$recordID'";
    if($conn->query($update)==true){     
        header("Location:../usertype.php?action=6");
    }
    else{header("Location:../usertype.php?action=5");}
}
?>