<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];


$tableData=$_POST['tableData'];
$recordID=$_POST['recordID'];

$updatedatetime=date('Y-m-d h:i:s');
$val = 0;
foreach($tableData as $rowtabledata){
    $productID=$rowtabledata['col_1'];
    $qty=$rowtabledata['col_3'];
    $actualqty=$rowtabledata['col_4'];

    if($qty != $actualqty){
        $val = 1;
    }
    
}

if($val == 0){
    $sql="UPDATE `tbl_return` SET `qtystatus`='1',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_return`='$recordID'";
}else{
    $sql="UPDATE `tbl_return` SET `qtystatus`='2',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_return`='$recordID'";
}

if($conn->query($sql)==true){
    foreach($tableData as $rowtabledata){
        $returnID=$rowtabledata['col_1'];
        $productID=$rowtabledata['col_6'];
        $qty=$rowtabledata['col_3'];
        $actualqty=$rowtabledata['col_4'];

        $sql1="UPDATE `tbl_return_details` SET `actualqty`='$actualqty',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_return_details`='$returnID'";
        $conn->query($sql1);

        $updatestock="UPDATE `tbl_stock` SET `qty`=(`qty`+'$actualqty') WHERE `tbl_product_idtbl_product`='$productID'";
        $conn->query($updatestock);
    }
    header("Location:../productreturn.php?action=5");
       

}
?>