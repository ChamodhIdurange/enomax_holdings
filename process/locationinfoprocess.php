
<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];
$updatedatetime=date('Y-m-d h:i:s');

$recordOption=$_POST['recordOptionLocation'];

$area=$_POST['area'];
$address=$_POST['address'];
$locationowner=$_POST['locationowner'];
$locationcontact=$_POST['locationcontact'];

$recordID=$_POST['recordIDLocation'];
$customerID=$_POST['customerID'];


if($recordOption==1){
   
$insertLocation = "INSERT INTO `tbl_customer_location`(`tbl_area_idtbl_area`, `address`, `ownername`, `ownercontact`, `updatedatetime`, `tbl_user_idtbl_user`, `status`, `tbl_customer_idtbl_customer`) VALUES ('$area', '$address','$locationowner', '$locationcontact', '$updatedatetime','$userID','1', '$customerID')";
    if($conn->query($insertLocation)==true){
    
         header("Location:../customerprofile.php?action=4&record=$customerID");
 
    }else{
        
  
            header("Location:../customerprofile.php?action=5&record=$customerID");


    }
}
else{
    $update="UPDATE `tbl_customer_location` SET `tbl_area_idtbl_area`='$area',`address`='$address',`ownername`='$locationowner', `ownercontact`='$locationcontact',`updatedatetime`='$updatedatetime' ,`tbl_user_idtbl_user`='$userID'  WHERE `idtbl_customer_location`='$recordID'";
    if($conn->query($update)==true){     
    
            header("Location:../customerprofile.php?action=6&record=$customerID");
     
    }
    if($type == "Electrician"){
    
            header("Location:../customerprofile.php?action=6&record=$customerID");
    }

}

?>
