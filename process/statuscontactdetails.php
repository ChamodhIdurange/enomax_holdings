<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];
$updatedatetime=date('Y-m-d h:i:s');

$record=$_GET['record'];

$eledID=$_GET['eledID'];
$type=$_GET['type'];



$sql="UPDATE `tbl_contact_details` SET `status`='3',`updatedatetime`='$updatedatetime' WHERE `idtbl_contact_details`='$record' and `type` = '$type'";
if($conn->query($sql)==true){
    if($type == "Electrician"){
        header("Location:../electricianprofile.php?action=3&record=$eledID");
    }else if($type == "Customer"){
        header("Location:../customerprofile.php?action=3&record=$eledID");

    }else if($type == "Employee"){
        header("Location:../employeeprofile.php?action=3&record=$eledID");

    }

   
}
else{
    if($type == "Electrician"){
        header("Location:../electricianprofile.php?action=6&record=$eledID");
    }else if($type == "Customer"){
        header("Location:../customerprofile.php?action=6&record=$eledID");

    }else if($type == "Employee"){
        header("Location:../employeeprofile.php?action=6&record=$eledID");

    }

}
?>