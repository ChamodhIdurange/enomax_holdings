<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$dayenddate = $_POST['dayenddate'];

$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $sqlcheck = "SELECT SUM(tbl_stock.qty*tbl_product.unitprice) AS stock_close_value FROM tbl_stock INNER JOIN tbl_product ON tbl_stock.tbl_product_idtbl_product=tbl_product.idtbl_product WHERE tbl_stock.status=1 AND tbl_stock.qty>0";
    $resultcheck =$conn-> query($sqlcheck); 
    $rowcheck = $resultcheck-> fetch_assoc();

    $checkstock = "SELECT SUM(`qty`) AS 'qty', `tbl_product_idtbl_product` FROM `tbl_stock` WHERE `qty` > '0' GROUP BY `tbl_product_idtbl_product`";
    $resultStock=$conn->query($checkstock);

    while ($rowStock = $resultStock-> fetch_assoc()) { 
        $productId = $rowStock['tbl_product_idtbl_product'];
        $qty = $rowStock['qty'];

        $insertgrnstock = "INSERT INTO `tbl_grn_stock` (`qty`, `status`, `insertdatetime`, `grndate`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`) VALUES ('$qty', '1', '$updatedatetime', '$dayenddate', '$userID', '$productId')";
        $result =$conn-> query($insertgrnstock); 
        
    }
    $closestock=$rowcheck['stock_close_value'];

    $insert = "INSERT INTO `tbl_stock_closing`(`date`, `closingstock`, `status`, `updatedatetime`, `tbl_user_idtbl_user`) VALUES ('$dayenddate','$closestock','1','$updatedatetime','$userID')";
    
    if($conn->query($insert)==true){header("Location:../dayend.php?action=4");}
    else{header("Location:../dayend.php?action=5");}
}
?>