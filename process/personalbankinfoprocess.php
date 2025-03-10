
<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];
$updatedatetime=date('Y-m-d h:i:s');

$recordOption=$_POST['recordOptionBank'];

$bank=$_POST['bank'];
$branchname=$_POST['branchname'];
$accountnumber=$_POST['accountnumber'];
$recordID=$_POST['recordIDBank'];
$accountname=$_POST['accountname'];


$type=$_POST['usertypebank'];
$record=$_POST['recordbank'];


if($recordOption==1){
   
$insertinquiry = "INSERT INTO `tbl_bank_account_details`(`tbl_bank_idtbl_bank`, `branchname`, `accountnumber`, `updatedatetime`, `tbl_user_idtbl_user`, `status`, `type`, `person_id`, `account_name`) VALUES ('$bank', '$branchname','$accountnumber','$updatedatetime','$userID','1','$type', '$record', '$accountname')";
    if($conn->query($insertinquiry)==true){
        if($type == "Electrician"){
            header("Location:../electricianprofile.php?action=4&record=$record&type=$type");
        }else if($type == "Customer"){
            header("Location:../customerprofile.php?action=4&record=$record&type=$type");
        }
        else if($type == "Employee"){
            header("Location:../employeeprofile.php?action=4&record=$record&type=$type");
        }

    }else{
        
        if($type == "Electrician"){
            header("Location:../electricianprofile.php?action=5&record=$record&type=$type");
        }else if($type == "Customer"){
            header("Location:../customerprofile.php?action=5&record=$record&type=$type");
        }else if($type == "Employee"){
            header("Location:../employeeprofile.php?action=5&record=$record&type=$type");
        }


    }
}
else{
    $update="UPDATE `tbl_bank_account_details` SET `tbl_bank_idtbl_bank`='$bank',`branchname`='$branchname',`accountnumber`='$accountnumber',`updatedatetime`='$updatedatetime' ,`tbl_user_idtbl_user`='$userID' ,`account_name`='$accountname' WHERE `idtbl_bank_account_details`='$recordID' and `type` = '$type'";
    if($conn->query($update)==true){     
        if($type == "Electrician"){
            header("Location:../electricianprofile.php?action=6&record=$record&type=$type");
        }else if($type == "Customer"){
            header("Location:../customerprofile.php?action=6&record=$record&type=$type");
        }else if($type == "Employee"){
            header("Location:../employeeprofile.php?action=6&record=$record&type=$type");
        }

    }


}

?>
