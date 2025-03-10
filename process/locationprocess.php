<?php
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}

;

$province=$_POST['province'];
$district=$_POST['district'];
$city=$_POST['city'];
$locationname=$_POST['locationname'];
$address=$_POST['address'];
$contact1=$_POST['contact1'];
$contact2=$_POST['contact2'];
$contactperson=$_POST['contactperson'];
$email=$_POST['email'];
$headperson=$_POST['headperson'];
$accountnumber=$_POST['accountnumber'];
$accountowner=$_POST['accountowner'];
$bank=$_POST['bank'];

$updatedatetime=date('Y-m-d h:i:s');
print_r($recordOption);
print_r($locationname);

if($recordOption==1){
    $query = "INSERT INTO `tbl_locations`(`province`, `district`, `city`, `locationname`, `address`, `contact1`,`contact2`,`contactperson`,`email`,`headperson`,`status`,`updatedatetime`,`tbl_user_idtbl_user`, `tbl_bank_idtbl_bank`, `accountowner`, `accountnumber`) Values ('$province','$district','$city','$locationname','$address','$contact1','$contact2','$contactperson','$email','$headperson','1','$updatedatetime','$userID','$bank','$accountowner','$accountnumber')";
    if($conn->query($query)==true){
        header("Location:../locations.php?action=4");
        // print_r("success");
    }else{
        header("Location:../locations.php?action=5");
        // print_r("error");

    }
}
else{
    $update="UPDATE `tbl_locations` SET `province`='$province',`district`='$district',`city`='$city',`locationname`='$locationname',`address`='$address',`contact1`='$contact1',`contact2`='$contact2',`contactperson`='$contactperson',`email`='$email',`headperson`='$headperson',`updatedatetime`='$updatedatetime' ,`tbl_user_idtbl_user`='$userID',`tbl_bank_idtbl_bank`='$bank',`accountowner`='$accountowner',`accountnumber`='$accountnumber' WHERE `idtbl_locations`='$recordID'";
    if($conn->query($update)==true){     
        header("Location:../locations.php?action=6");
    }
    else{header("Location:../locations.php?action=5");}
}
?>