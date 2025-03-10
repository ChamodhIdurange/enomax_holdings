<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];
$updatedatetime=date('Y-m-d h:i:s');

$recordOption=$_POST['recordOptionContact'];

$ownername=$_POST['ownername'];
$number=$_POST['number'];
$relation=$_POST['relation'];
$email=$_POST['email'];
$recordID=$_POST['recordID'];

$type=$_POST['usertype'];
$record=$_POST['record'];


if($recordOption==1){
   
$insertinquiry = "INSERT INTO `tbl_contact_details`(`contact_owner`, `relation`, `number`, `updatedatetime`, `tbl_user_idtbl_user`, `status`, `type`, `person_id`, `email`) VALUES ('$ownername', '$relation','$number','$updatedatetime','$userID','1','$type', '$record', '$email')";
    if($conn->query($insertinquiry)==true){
        if($type == "Electrician"){
            header("Location:../electricianprofile.php?action=4&record=$record&type=$type");
        }else if($type == "Customer"){
            header("Location:../customerprofile.php?action=4&record=$record&type=$type");
        }else if($type == "Employee"){
            header("Location:../employeeprofile.php?action=4&record=$record&type=$type");
        }
    }else{
       
        if($type == "Electrician"){
            header("Location:../electricianprofile.php?action=5&record=$record&type=$type");
        }else if($type == "Customer"){
            header("Location:../customerprofile.php?action=5&record=$record&type=$type");
        }else if($type == "Employee"){
            header("Location:../employeeprofile.php?action=4&record=$record&type=$type");
        }

    }
}

else{
    // Update part work without any errors but it allways go to else condition
    $update="UPDATE `tbl_contact_details` SET `contact_owner`='$ownername',`relation`='$relation',`number`='$number',`updatedatetime`='$updatedatetime' ,`tbl_user_idtbl_user`='$userID', `email`='$email'   WHERE `idtbl_contact_details`='$recordID' and `type` = '$type'";
    if($conn->query($update)==true){     
        if($type == "Electrician"){
            header("Location:../electricianprofile.php?action=6&record=$record&type=$type");
        }else if($type == "Customer"){
            header("Location:../customerprofile.php?action=6&record=$record&type=$type");
        }else if($type == "Employee"){
            header("Location:../employeeprofile.php?action=4&record=$record&type=$type");
        }

    }
    if($type == "Electrician"){
        header("Location:../electricianprofile.php?action=6&record=$record&type=$type");
    }else if($type == "Customer"){
        header("Location:../customerprofile.php?action=6&record=$record&type=$type");
    }else if($type == "Employee"){
        header("Location:../employeeprofile.php?action=4&record=$record&type=$type");
    }

}

?>