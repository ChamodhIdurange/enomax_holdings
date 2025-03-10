<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];
$updatedatetime=date('Y-m-d h:i:s');
$month = date('m');
$year = substr(date('y'), -2);;

$productID=$_POST['hiddenproductid'];
$remarks=$_POST['remarks'];
$adjustqty=$_POST['adjustqty'];
$adjustmenttype=$_POST['adjustmenttype'];
$productunitprice=$_POST['productunitprice'];
$productsaleprice=$_POST['productsaleprice'];

$sql = "SELECT MAX(idtbl_stock_adjustment) AS lastid FROM tbl_stock_adjustment";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $lastid = $row['lastid'];
    $nextid = $lastid + 1;
} else {
    $nextid = 1;
}

$batchNo = "ADJ".$year.$month.sprintf('%04s', $nextid);

if($adjustmenttype == 1){
    $insertstock="INSERT INTO `tbl_stock` (`batchqty`, `qty`, `unitprice`, `saleprice`, `update`, `status`, `batchno`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`, `insertdatetime`) VALUES ('$adjustqty', '$adjustqty', '$productunitprice', '$productsaleprice', '$updatedatetime', '1', '$batchNo', '$updatedatetime', '$userID', '$productID', '$updatedatetime')";
    if($conn->query($insertstock)==true){        

        $insertadjustment="INSERT INTO `tbl_stock_adjustment` (`adjustmenttype`, `adjustqty`, `remarks`, `status`, `batchnumbers`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`) VALUES ('$adjustmenttype', '$adjustqty', '$remarks', '1', '$batchNo', '$updatedatetime', '$userID', '$productID')";
        if($conn->query($insertadjustment)==true){  
            header("Location:../stockadjustment.php?action=4");
        }else{
            header("Location:../stockadjustment.php?action=5");
        }
    }
    else{header("Location:../stockadjustment.php?action=5");}
} else{


    $getstock = "SELECT * FROM `tbl_stock` WHERE `qty` > 0 AND `tbl_product_idtbl_product` = '$productID' ORDER BY SUBSTRING(batchno, 4) ASC LIMIT 1";
    $result = $conn->query($getstock);
    $stockdata = $result->fetch_assoc();

    $stockbatch = $stockdata['batchno'];
    $batchqty = $stockdata['qty'];

    $usedBatches = [$stockbatch];

    while($batchqty < $adjustqty){
        $updatestock="UPDATE `tbl_stock` SET `qty`=0 WHERE `tbl_product_idtbl_product`='$productID' AND `batchno` = '$stockbatch'";
        $conn->query($updatestock);
            
        $adjustqty = $adjustqty - $batchqty;
            
        $regetstock = "SELECT * FROM `tbl_stock` WHERE `qty` > 0 AND `tbl_product_idtbl_product` = '$productID' ORDER BY SUBSTRING(batchno, 4) ASC LIMIT 1";
        $reresult =$conn-> query($regetstock); 
        $restockdata = $reresult-> fetch_assoc();
            
        $stockbatch = $restockdata['batchno'];
        $batchqty = $restockdata['qty'];
            
        $usedBatches[] = $stockbatch;

        if($batchqty > $adjustqty){
            break;
        }
    }
            
    $updatestock="UPDATE `tbl_stock` SET `qty`=(`qty`-'$adjustqty') WHERE `tbl_product_idtbl_product`='$productID' AND `batchno` = '$stockbatch'";
    $conn->query($updatestock);

    $batchNumbersString = implode(", ", $usedBatches);

    $insertadjustment="INSERT INTO `tbl_stock_adjustment` (`adjustmenttype`, `adjustqty`, `remarks`, `status`, `batchnumbers`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`) VALUES ('$adjustmenttype', '$adjustqty', '$remarks', '1', '$batchNumbersString', '$updatedatetime', '$userID', '$productID')";
    
    if($conn->query($insertadjustment)==true){  
        header("Location:../stockadjustment.php?action=4");
    }else{
        header("Location:../stockadjustment.php?action=5");
    }
}


?>