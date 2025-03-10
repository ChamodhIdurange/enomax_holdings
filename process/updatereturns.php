<?php session_start();

if( !isset($_SESSION['userid'])) {
    header ("Location:index.php");
}

require_once('../connection/db.php'); //die('bc');
$userID=$_SESSION['userid'];

$updatedatetime=date('Y-m-d h:i:s');

$unitprice=$_POST['unitprice'];
$record=$_POST['hiddenid'];
$discount=$_POST['discount'];
$qty=$_POST['qty'];
$prevtot=$_POST['hiddentotal'];
$hiddendiscount=$_POST['hiddendiscount'];
$total=$_POST['total'];
$mainID=$_POST['mainID'];
$returntype=$_POST['returntype'];

$tot = $total - ($total * ($discount/100));
 
if($prevtot !=  $total || $hiddendiscount != $discount ){
    $query="UPDATE `tbl_return_details` SET `qty`='$qty',`discount`='$discount', `total`='$tot',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_return_details`='$record'";
        
        if($conn->query($query)==true) {
            $query1="UPDATE `tbl_return` SET `total`=(`total` - '$prevtot' + '$tot') WHERE `idtbl_return`='$mainID'";
            
            if($conn->query($query1)==true) {
                if($returntype == 1){
                    header("Location:../customerreturn.php?action=6");
                }else if($returntype == 2){
                    header("Location:../supplierreturn.php?action=6");
                }else{
                    header("Location:../damagereturns.php?action=6");
                }
            }else{
                if($returntype == 1){
                    header("Location:../customerreturn.php?action=5");
                }else if($returntype == 2){
                    header("Location:../supplierreturn.php?action=5");
                }else{
                    header("Location:../damagereturns.php?action=5");
                }

            }
        }else{

        }

}else{
    if($returntype == 1){
        header("Location:../customerreturn.php");
    }else if($returntype == 2){
        header("Location:../supplierreturn.php");
    }else{
        header("Location:../damagereturns.php");
    }
}
?>

