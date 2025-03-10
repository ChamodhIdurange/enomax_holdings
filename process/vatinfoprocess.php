<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$fromdate=addslashes($_POST['fromdate']);
$vat=addslashes($_POST['vat']);
$nbt=addslashes($_POST['nbt']);
$s_vat=addslashes($_POST['s_vat']);
$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $insert="INSERT INTO `tbl_vat_info`(`date_from`, `vat`, `nbt`, `s_vat`, `status`, `insertdatetime`, `tbl_user_idtbl_user`) VALUES ('$fromdate','$vat','$nbt','$s_vat','1','$updatedatetime','$userID')";
    if($conn->query($insert)==true){        
        header("Location:../vatinfo.php?action=4");
    }
    else{header("Location:../vatinfo.php?action=5");}
}
else{
    $update="UPDATE `tbl_vat_info` SET `date_from`='$fromdate', `vat`='$vat', `nbt`='$nbt', `s_vat`='$s_vat',`updatedatetime`='$updatedatetime',`updateuser`='$userID' WHERE `idtbl_vat_info`='$recordID'";
    if($conn->query($update)==true){     
        header("Location:../vatinfo.php?action=6");
    }
    else{header("Location:../vatinfo.php?action=5");}
}
?>