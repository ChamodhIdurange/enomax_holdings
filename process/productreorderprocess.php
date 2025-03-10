<?php
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}

;


$location=$_POST['location'];
$product=$_POST['product'];
$minimumqty=$_POST['minimumqty'];


$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $query = "INSERT INTO `tbl_location_reorder`(`tbl_product_idtbl_product`, `tbl_locations_idtbl_locations`, `minimum_quantity`, `tbl_user_idtbl_user`, `status`) Values ('$product','$location','$minimumqty','$userID','1')";
    if($conn->query($query)==true){
        header("Location:../productreorder.php?action=4");
        // print_r("success");
    }else{
        header("Location:../productreorder.php?action=5");
        // print_r("error");

    }
}
// else{
//     $update="UPDATE `tbl_locations` SET `province`='$province',`district`='$district',`city`='$city',`locationname`='$locationname',`address`='$address',`contact1`='$contact1',`contact2`='$contact2',`contactperson`='$contactperson',`email`='$email',`headperson`='$headperson',`updatedatetime`='$updatedatetime' ,`tbl_user_idtbl_user`='$userID' WHERE `idtbl_locations`='$recordID'";
//     if($conn->query($update)==true){     
//         header("Location:../locations.php?action=6");
//     }
//     else{header("Location:../locations.php?action=5");}
// }
?>