<?php
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}

;

$elecname=$_POST['elecname'];
$contactone=$_POST['contactone'];
$customer=$_POST['customer'];
$area=$_POST['area'];

$regno=$_POST['regno'];
$idnumber=$_POST['idnumber'];
$whatsappno=$_POST['whatsappno'];
$dob=$_POST['dob'];
$address=$_POST['address'];

$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $query = "INSERT INTO `tbl_electrician`(`name`, `contact`, `tbl_area_idtbl_area`, `tbl_customer_idtbl_customer`, `updatedatetime`, `tbl_user_idtbl_user`,`status`,`regno`,`idnumber`,`whatsappno`,`dob`,`address`) Values ('$elecname','$contactone','$area','$customer','$updatedatetime','$userID','1', '$regno','$idnumber','$whatsappno','$dob','$address')";
    if($conn->query($query)==true){
        header("Location:../electricians.php?action=4");
    }else{
        header("Location:../electricians.php?action=5");
    }
}
else{
    $update="UPDATE `tbl_electrician` SET `name`='$elecname',`tbl_area_idtbl_area`='$area',`contact`='$contactone',`tbl_customer_idtbl_customer`='$customer' ,`regno`='$regno',`idnumber`='$idnumber',`whatsappno`='$whatsappno',`dob`='$dob',`address`='$address' WHERE `idtbl_electrician`='$recordID'";
    if($conn->query($update)==true){     
        header("Location:../electricians.php?action=6");
    }
    else{header("Location:../electricians.php?action=5");}
}
?>