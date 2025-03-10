<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location:../index.php");
}
require_once('../connection/db.php');

$userID = $_SESSION['userid'];
if (!empty($_POST['recordreturnID'])) {
    $record = $_POST['recordreturnID'];
    $returnprice = $_POST['returnprice'];
    $returnreason = $_POST['returnreason'];
} else {
    $record = $_POST['recordID'];
}
$type = $_POST['type'];
$cancelreason = $_POST['cancelreason'];
$updatedatetime = date('Y-m-d h:i:s');

if ($type == 1) {
    $sqlwarehouse1 = "UPDATE `tbl_porder` SET `paystatus`='1',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_porder`='$record'";
    if ($conn->query($sqlwarehouse1) == true) {

        $sql = "UPDATE `tbl_warehouse` SET `paystatus`='1',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `tbl_porder_idtbl_porder`='$record'";
        if ($conn->query($sql) == true) {

            $actionObj = new stdClass();
            $actionObj->icon = 'fas fa-check-circle';
            $actionObj->title = '';
            $actionObj->message = 'Payment Successfully';
            $actionObj->url = '';
            $actionObj->target = '_blank';
            $actionObj->type = 'success';

            echo $actionJSON = json_encode($actionObj);
        }
    } else {
        $actionObj = new stdClass();
        $actionObj->icon = 'fas fa-exclamation-triangle';
        $actionObj->title = '';
        $actionObj->message = 'Record Error';
        $actionObj->url = '';
        $actionObj->target = '_blank';
        $actionObj->type = 'danger';

        echo $actionJSON = json_encode($actionObj);
    }
} else if ($type == 2) {
    $sql = "UPDATE `tbl_porder` SET `shipstatus`='1',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_porder`='$record'";
    if ($conn->query($sql) == true) {

        $sqlwarehouse2 = "UPDATE `tbl_warehouse` SET `shipstatus`='1',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `tbl_porder_idtbl_porder`='$record'";
        if ($conn->query($sqlwarehouse2) == true) {


            $actionObj = new stdClass();
            $actionObj->icon = 'fas fa-check-circle';
            $actionObj->title = '';
            $actionObj->message = 'Shiped Successfully';
            $actionObj->url = '';
            $actionObj->target = '_blank';
            $actionObj->type = 'success';

            echo $actionJSON = json_encode($actionObj);
        }
    } else {
        $actionObj = new stdClass();
        $actionObj->icon = 'fas fa-exclamation-triangle';
        $actionObj->title = '';
        $actionObj->message = 'Record Error';
        $actionObj->url = '';
        $actionObj->target = '_blank';
        $actionObj->type = 'danger';

        echo $actionJSON = json_encode($actionObj);
    }
} else if ($type == 3) {

    $sql = "UPDATE `tbl_porder` SET `deliverystatus`='1',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_porder`='$record'";

    if ($conn->query($sql) == true) {

        $sqltblwarehouse3 = "UPDATE `tbl_warehouse` SET `deliverystatus`='1',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `tbl_porder_idtbl_porder`='$record'";

        if ($conn->query($sqltblwarehouse3) == true) {

            $sqlgetupdatestock = "SELECT `tbl_product_idtbl_product`, `qty`, `freeproductid`, `freeqty` FROM `tbl_warehouse_details` WHERE `tbl_porder_idtbl_porder` = '$record'";
            $resultgetupdatestock = $conn->query($sqlgetupdatestock);

            if ($resultgetupdatestock->num_rows > 0) {
                while ($row = $resultgetupdatestock->fetch_assoc()) {
                    $product = $row['tbl_product_idtbl_product'];
                    $reducedqty = $row['qty'];
                    $freeproductid = $row['freeproductid'];
                    $freeqty = $row['freeqty'];

                    $getstock = "SELECT * FROM `tbl_stock` WHERE `qty` > 0 AND `tbl_product_idtbl_product` = '$product' ORDER BY SUBSTRING(batchno, 4) ASC LIMIT 1";
                    $result = $conn->query($getstock);
                    $stockdata = $result->fetch_assoc();

                    $stockbatch = $stockdata['batchno'];
                    $batchqty = $stockdata['qty'];

                    while($batchqty < $reducedqty){
                        $updatestock="UPDATE `tbl_stock` SET `qty`=0 WHERE `tbl_product_idtbl_product`='$product' AND `batchno` = '$stockbatch'";
                        $conn->query($updatestock);
            
                        $reducedqty = $reducedqty - $batchqty;
            
                        $regetstock = "SELECT * FROM `tbl_stock` WHERE `qty` > 0 AND `tbl_product_idtbl_product` = '$product' ORDER BY SUBSTRING(batchno, 4) ASC LIMIT 1";
                        $reresult =$conn-> query($regetstock); 
                        $restockdata = $reresult-> fetch_assoc();
            
                        $stockbatch = $restockdata['batchno'];
                        $batchqty = $restockdata['qty'];
            
                        if($batchqty > $reducedqty){
                            break;
                        }
                    }
                    // echo $reducedqty;
            
                    $updatestock="UPDATE `tbl_stock` SET `qty`=(`qty`-'$reducedqty') WHERE `tbl_product_idtbl_product`='$product' AND `batchno` = '$stockbatch'";
                    $conn->query($updatestock);
                   
                    $updatestockfree="UPDATE `tbl_stock` SET `qty`=(`qty`-'$freeqty') WHERE `tbl_product_idtbl_product`='$freeproductid'";
                    $conn->query($updatestockfree);
                }
            }
            $actionObj = new stdClass();
            $actionObj->icon = 'fas fa-check-circle';
            $actionObj->title = '';
            $actionObj->message = 'Delivery Successfully';
            $actionObj->url = '';
            $actionObj->target = '_blank';
            $actionObj->type = 'success';

            echo $actionJSON = json_encode($actionObj);
        }
    } else {
        $actionObj = new stdClass();
        $actionObj->icon = 'fas fa-exclamation-triangle';
        $actionObj->title = '';
        $actionObj->message = 'Record Error';
        $actionObj->url = '';
        $actionObj->target = '_blank';
        $actionObj->type = 'danger';

        echo $actionJSON = json_encode($actionObj);
    }
} else if ($type == 4) {
    $sql = "UPDATE `tbl_porder` SET `status`='2',`cancelreason`='$cancelreason',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_porder`='$record'";
    if ($conn->query($sql) == true) {

        $sqltblwarehouse4 = "UPDATE `tbl_warehouse` SET `status`='2',`cancelreason`='$cancelreason',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `tbl_porder_idtbl_porder`='$record'";
        if ($conn->query($sqltblwarehouse4) == true) {
            header("Location:../orderlist.php?action=7");
        } else {
            header("Location:../orderlist.php?action=5");
        }
    }
} else if ($type == 5) {

    $sql = "UPDATE `tbl_porder` SET `confirmstatus`='1',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_porder`='$record'";
    if ($conn->query($sql) == true) {
        $updatedatetime = date('Y-m-d h:i:s');

        // Fetch the purchase order
        $sqlpOrder1 = "SELECT * FROM `tbl_porder` WHERE `idtbl_porder` = '$record'";
        $resultporder1 = $conn->query($sqlpOrder1);

        if ($resultporder1->num_rows > 0) {
            while ($row = $resultporder1->fetch_assoc()) {
                $idtbl_porder = $row['idtbl_porder'];
                $potype = $row['potype'];
                $ismaterialpo = $row['ismaterialpo'];
                $isassemblepo = $row['isassemblepo'];
                $orderdate = $row['orderdate'];
                $subtotal = $row['subtotal'];
                $disamount = $row['disamount'];
                $discount = $row['discount'];
                $podiscount = $row['podiscount'];
                $po_amount = $row['po_amount'];
                $nettotal = $row['nettotal'];
                $payfullhalf = $row['payfullhalf'];
                $remark = $row['remark'];
                $confirmstatus = $row['confirmstatus'];
                $dispatchissue = $row['dispatchissue'];
                $grnissuestatus = $row['grnissuestatus'];
                $paystatus = $row['paystatus'];
                $shipstatus = $row['shipstatus'];
                $deliverystatus = $row['deliverystatus'];
                $trackingno = $row['trackingno'];
                $trackingwebsite = $row['trackingwebsite'];
                $callstatus = $row['callstatus'];
                $narration = $row['narration'];
                $cancelreason = $row['cancelreason'];
                $returnstatus = $row['returnstatus'];
                $status = $row['status'];
                $tbl_user_idtbl_user = $row['tbl_user_idtbl_user'];
                $tbl_locations_idtbl_locations = $row['tbl_locations_idtbl_locations'];
            }

            $insertwarehous = "
        INSERT INTO `tbl_warehouse`
        (
            `potype`, `ismaterialpo`, `isassemblepo`, `orderdate`, `subtotal`, 
            `disamount`, `discount`, `podiscount`, `po_amount`, `nettotal`, 
            `payfullhalf`, `remark`, `confirmstatus`, `dispatchissue`, 
            `grnissuestatus`, `paystatus`, `shipstatus`, `deliverystatus`, 
            `trackingno`, `trackingwebsite`, `callstatus`, `narration`, 
            `cancelreason`, `returnstatus`, `status`, `insertdatetime`, `tbl_user_idtbl_user`, 
            `tbl_locations_idtbl_locations`, `tbl_porder_idtbl_porder`
        ) 
        VALUES 
        (
            '$potype', '$ismaterialpo', '$isassemblepo', '$orderdate', '$subtotal', 
            '$disamount', '$discount', '$podiscount', '$po_amount', '$nettotal', 
            '$payfullhalf', '$remark', '$confirmstatus', '$dispatchissue', 
            '$grnissuestatus', '$paystatus', '$shipstatus', '$deliverystatus', 
            '$trackingno', '$trackingwebsite', '$callstatus', '$narration', 
            '$cancelreason', '$returnstatus', '$status', '$updatedatetime', '$tbl_user_idtbl_user', 
            '$tbl_locations_idtbl_locations', '$idtbl_porder'
        )
    ";

            if ($conn->query($insertwarehous) === true) {
                $warehouse_id = $conn->insert_id;

                $sqlpOrder2 = "SELECT * FROM `tbl_porder_detail` WHERE `tbl_porder_idtbl_porder` = '$record'";
                $resultporder2 = $conn->query($sqlpOrder2);

                if ($resultporder2->num_rows > 0) {
                    while ($row = $resultporder2->fetch_assoc()) {
                        $idtbl_porder_detail = $row['idtbl_porder_detail'];
                        $type = $row['type'];
                        $qty = $row['qty'];
                        $freeqty = $row['freeqty'];
                        $freeproductid = $row['freeproductid'];
                        $unitprice = $row['unitprice'];
                        $saleprice = $row['saleprice'];
                        $discount = $row['discount'];
                        $discountamount = $row['discountamount'];
                        $status = $row['status'];
                        $tbl_user_idtbl_user = $row['tbl_user_idtbl_user'];
                        $tbl_porder_idtbl_porder = $row['tbl_porder_idtbl_porder'];
                        $tbl_product_idtbl_product = $row['tbl_product_idtbl_product'];
                        $tbl_material_idtbl_material = $row['tbl_material_idtbl_material'];

                        $insertwarehousdetails = "
                    INSERT INTO `tbl_warehouse_details`
                    (
                        `type`, `qty`, `freeqty`, `freeproductid`, `unitprice`, 
                        `saleprice`, `discount`, `discountamount`, `status`, `insertdatetime`, 
                        `tbl_user_idtbl_user`, `tbl_porder_idtbl_porder`, `tbl_product_idtbl_product`, `tbl_material_idtbl_material`, `idtbl_porder_detail`, 
                        `tbl_warehouse_idtbl_warehouse`
                    ) 
                    VALUES 
                    (
                        '$type', '$qty', '$freeqty', '$freeproductid', '$unitprice', 
                        '$saleprice', '$discount', '$discountamount', '$status', '$updatedatetime', 
                        '$tbl_user_idtbl_user', '$tbl_porder_idtbl_porder', '$tbl_product_idtbl_product', '$tbl_material_idtbl_material', '$idtbl_porder_detail', 
                        '$warehouse_id'
                    )
                ";

                        // Execute the query for each detail
                        if ($conn->query($insertwarehousdetails) !== true) {
                            echo "Error: " . $conn->error;
                        }
                    }
                }
            } else {
                echo "Error: " . $conn->error;
            }
        }


        // Uncomment this only if you want to make target after  confirmation of porder
        // $sqlpOrderDetails = "SELECT `p`.`orderdate`,`d`.`tbl_product_idtbl_product`,`d`.`qty`,`o`.`repid` FROM `tbl_porder` AS `p` JOIN `tbl_porder_detail` AS `d` ON (`p`.`idtbl_porder` = `d`.`tbl_porder_idtbl_porder`) JOIN `tbl_porder_otherinfo` AS `o` ON (`o`.`porderid` = `p`.`idtbl_porder`) WHERE `d`.`tbl_porder_idtbl_porder` = '$record'";
        // $resultDetails =$conn-> query($sqlpOrderDetails);
        // if($resultDetails->num_rows > 0) {while ($row = $resultDetails-> fetch_assoc()) {
        //     $date = $row['orderdate'];
        //     $date = substr($date, 0, 7);
        //     $date = $date.'-01';
        //     $productID = $row['tbl_product_idtbl_product'];
        //     $qty = $row['qty'];
        //     $repID = $row['repid'];


        //     $c = 1;


        //     if($c = 1){
        //         $sqlTarget = "SELECT `idtbl_employee_target` FROM `tbl_employee_target` WHERE `month` = '$date' AND `tbl_employee_idtbl_employee` = '$repID'";
        //         $resultTarget =$conn-> query($sqlTarget);
        //         if($resultTarget->num_rows > 0) {while ($result2 = $resultTarget-> fetch_assoc()) {
        //             $targetID = $result2['idtbl_employee_target'];
        //             echo "<script>console.log('" . $date . "' );</script>";

        //             $sqlUpdate="UPDATE `tbl_employee_target_details` SET `current_value`=(`current_value`+'$qty') ,`updatedatetime`='$date' WHERE `tbl_employee_target_idtbl_employee_target`='$targetID' AND `tbl_product_idtbl_product` = '$productID'";
        //             $resultUpdate =$conn-> query($sqlUpdate);
        //             $c = 2;


        //         }}
        //     }else{
        //         $sqlUpdate="UPDATE `tbl_employee_target_details` SET `current_value`=(`current_value`+'$qty'),`updatedatetime`='$updatedatetime' WHERE `tbl_employee_target_idtbl_employee_target`='$targetID' AND `tbl_product_idtbl_product` = '$productID'";
        //         $resultUpdate =$conn-> query($sqlUpdate);
        //     }
        // }}



        $actionObj = new stdClass();
        $actionObj->icon = 'fas fa-check';
        $actionObj->title = '';
        $actionObj->message = 'Order Accept Successfully';
        $actionObj->url = '';
        $actionObj->target = '_blank';
        $actionObj->type = 'success';

        echo $actionJSON = json_encode($actionObj);
    } else {
        $actionObj = new stdClass();
        $actionObj->icon = 'fas fa-exclamation-triangle';
        $actionObj->title = '';
        $actionObj->message = 'Record Error';
        $actionObj->url = '';
        $actionObj->target = '_blank';
        $actionObj->type = 'danger';

        echo $actionJSON = json_encode($actionObj);
    }
} else if ($type == 6) {
    $sql = "UPDATE `tbl_porder` SET `callstatus`='1',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_porder`='$record'";
    if ($conn->query($sql) == true) {
        $actionObj = new stdClass();
        $actionObj->icon = 'fas fa-check-circle';
        $actionObj->title = '';
        $actionObj->message = 'Delivery Successfully';
        $actionObj->url = '';
        $actionObj->target = '_blank';
        $actionObj->type = 'success';

        echo $actionJSON = json_encode($actionObj);
    } else {
        $actionObj = new stdClass();
        $actionObj->icon = 'fas fa-exclamation-triangle';
        $actionObj->title = '';
        $actionObj->message = 'Record Error';
        $actionObj->url = '';
        $actionObj->target = '_blank';
        $actionObj->type = 'danger';

        echo $actionJSON = json_encode($actionObj);
    }
} else if ($type == 7) {
    $sql = "UPDATE `tbl_porder` SET `status`='1',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_porder`='$record'";
    if ($conn->query($sql) == true) {
        $actionObj = new stdClass();
        $actionObj->icon = 'fas fa-check-circle';
        $actionObj->title = '';
        $actionObj->message = 'Reactive Successfully';
        $actionObj->url = '';
        $actionObj->target = '_blank';
        $actionObj->type = 'success';

        echo $actionJSON = json_encode($actionObj);
    } else {
        $actionObj = new stdClass();
        $actionObj->icon = 'fas fa-exclamation-triangle';
        $actionObj->title = '';
        $actionObj->message = 'Record Error';
        $actionObj->url = '';
        $actionObj->target = '_blank';
        $actionObj->type = 'danger';

        echo $actionJSON = json_encode($actionObj);
    }
} else if ($type == 8) {
    $sql = "UPDATE `tbl_porder` SET `returnstatus`='1',`status`='2',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_porder`='$record'";
    if ($conn->query($sql) == true) {
        header("Location:../orderlist.php?action=8");
    } else {
        header("Location:../orderlist.php?action=5");
    }
}
