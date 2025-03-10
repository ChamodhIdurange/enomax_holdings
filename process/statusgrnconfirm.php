<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];
$updatedatetime=date('Y-m-d h:i:s');

$month = date('m');
$year = substr(date('y'), -2);;
$record=$_GET['record'];

$sqlassempledpo = "SELECT `p`.`isassemblepo` FROM `tbl_porder_grn` AS `pg` JOIN `tbl_porder` AS `p` ON (`pg`.`tbl_porder_idtbl_porder` = `p`.`idtbl_porder`) WHERE `pg`.`tbl_grn_idtbl_grn` = '$record'";
$assembleresult =$conn-> query($sqlassempledpo); 
$rowassemble = $assembleresult-> fetch_assoc();

$sql="SELECT * FROM `tbl_grndetail` WHERE `tbl_grn_idtbl_grn` = '$record'";
$result =$conn-> query($sql); 

$batchNo = "BTH".$year.$month.sprintf('%04s', $record);

$sql="UPDATE `tbl_grn` SET `confirm_status`='1',`updatedatetime`='$updatedatetime' WHERE `idtbl_grn`='$record'";
if($conn->query($sql)==true){
    if($result->num_rows > 0) {while ($row = $result-> fetch_assoc()){
        $qty = $row['qty'];
        $unitprice = $row['unitprice'];
        $saleprice = $row['saleprice'];
        echo $unitprice;
        if($row['tbl_product_idtbl_product'] != null){
            $productID = $row['tbl_product_idtbl_product'];
            // $updatestock="UPDATE `tbl_stock` SET `qty`=(`qty`+'$qty') WHERE `tbl_product_idtbl_product`='$productID'";
            // if($rowassemble['isassemblepo'] == 1){
            //    $updatestock="UPDATE `tbl_assembled_stock` SET `qty`=(`qty`-'$qty') WHERE `tbl_product_idtbl_product`='$productID'";
            //    $conn->query($updatestock);
            // }
            $insertstock="INSERT INTO `tbl_stock` (`batchqty`, `qty`, `unitprice`, `saleprice`, `update`, `status`, `batchno`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`, `insertdatetime`) VALUES ('$qty', '$qty', '$unitprice', '$saleprice', '$updatedatetime', '1', '$batchNo', '$updatedatetime', '$userID', '$productID', '$updatedatetime')";
        }else{
            $materialId = $row['tbl_material_idtbl_material'];
            $insertstock="INSERT INTO `tbl_material_stock` (`batchqty`, `qty`,  `unitprice`, `saleprice`, `status`, `batchno`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_material_idtbl_material`, `insertdatetime`) VALUES ('$qty', '$qty', '$unitprice', '$saleprice', '1', '$batchNo', '$updatedatetime', '$userID', '$materialId', '$updatedatetime')";
        }
        
        $conn->query($insertstock);
    }}
    echo $insertstock;
    // header("Location:../grn.php?action=6");
}
else{header("Location:../grn.php?action=5");}
?>