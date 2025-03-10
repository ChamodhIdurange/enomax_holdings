<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:../index.php");}
require_once('../connection/db.php'); 

$userID=$_SESSION['userid'];
$record=$_GET['record'];

$sqlReturn = "SELECT `r`.`returntype`, `d`.`qty`, `d`.`tbl_product_idtbl_product` FROM `tbl_return_details` as `d` JOIN `tbl_return` as `r` ON (`r`.`idtbl_return` = `d`.`tbl_return_idtbl_return`) WHERE `d`.`tbl_return_idtbl_return` = '$record'";
$resultReturn=$conn->query($sqlReturn);


$sql="UPDATE `tbl_return` SET `recieved_status`='1',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_return`='$record'";
if($conn->query($sql)==true){

    while ($row = $resultReturn-> fetch_assoc()) { 
        $type = $row['returntype'];
        $qty = $row['qty'];
        $reducedqty = $qty;
        $prodictID = $row['tbl_product_idtbl_product'];

        if($row['returntype'] == 2){

            $getstock = "SELECT * FROM `tbl_stock` WHERE `qty` > 0 AND `tbl_product_idtbl_product` = '$prodictID' ORDER BY SUBSTRING(batchno, 4) ASC LIMIT 1";
            $result =$conn-> query($getstock); 
            $stockdata = $result-> fetch_assoc();
    
            $stockbatch = $stockdata['batchno'];
            $batchqty = $stockdata['qty'];

            while($batchqty < $reducedqty){
                $updatestock="UPDATE `tbl_stock` SET `qty`=0 WHERE `tbl_product_idtbl_product`='$prodictID' AND `batchno` = '$stockbatch'";
                $conn->query($updatestock);
    
                $reducedqty = $reducedqty - $batchqty;
    
                $regetstock = "SELECT * FROM `tbl_stock` WHERE `qty` > 0 AND `tbl_product_idtbl_product` = '$prodictID' ORDER BY SUBSTRING(batchno, 4) ASC LIMIT 1";
                $reresult =$conn-> query($regetstock); 
                $restockdata = $reresult-> fetch_assoc();
    
                $stockbatch = $restockdata['batchno'];
                $batchqty = $restockdata['qty'];
    
                if($batchqty > $reducedqty){
                    break;
                }
            }
            $updatestock="UPDATE `tbl_stock` SET `qty`=(`qty`-'$reducedqty') WHERE `tbl_product_idtbl_product`='$prodictID' AND `batchno` = '$stockbatch'";
            $conn->query($updatestock);
            // $updatestock="UPDATE `tbl_stock` SET `qty`=(`qty`-'$qty') WHERE `tbl_product_idtbl_product`='$prodictID'";
        }else{
            // header("Location:../productreturn.php?action=6");
        }
    
        
    }
    if($type == 1){
        header("Location:../customerreturn.php?action=6");
    }else if($type == 2){
        header("Location:../supplierreturn.php?action=6");
    }else{
        header("Location:../damagereturns.php?action=6");

    }

    

}
else{
    header("Location:../customerreturn.php?action=5");
}
?>