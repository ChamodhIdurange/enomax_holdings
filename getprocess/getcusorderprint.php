<?php
session_start();
require_once('../connection/db.php');

$orderID=$_POST['orderID'];

$sqlorder="SELECT `p`.`subtotal`, `p`.`disamount`, `p`.`nettotal`, `p`.`remark`, `c`.`customer`, `c`.`phone`, `p`.`orderdate` FROM `tbl_porder` as `p` JOIN `tbl_porder_otherinfo` AS `o` ON (`o`.`porderid` = `p`.`idtbl_porder`) JOIN `tbl_customer` AS `c` ON (`c`.`idtbl_customer` = `o`.`customerid`) WHERE `p`.`idtbl_porder`='$orderID'";
$resultorder=$conn->query($sqlorder);
$roworder=$resultorder->fetch_assoc();

$orderdate=$roworder['orderdate'];

$sqlordercount="SELECT COUNT(*) AS `count` FROM `tbl_porder` WHERE `orderdate`='$orderdate'";
$resultordercount=$conn->query($sqlordercount);
$rowordercount=$resultordercount->fetch_assoc();

$sqlorderinfo="SELECT `tbl_porder_detail`.*, `tbl_product`.`product_name` FROM `tbl_porder_detail` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_porder_detail`.`tbl_product_idtbl_product` WHERE `tbl_porder_detail`.`status`=1 AND `tbl_porder_detail`.`tbl_porder_idtbl_porder`='$orderID'";
$resultorderinfo=$conn->query($sqlorderinfo);

$sqlorderinfofree="SELECT `tbl_porder_detail`.*, `tbl_product`.`product_name` FROM `tbl_porder_detail` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_porder_detail`.`freeproductid` WHERE `tbl_porder_detail`.`status`=1 AND `tbl_porder_detail`.`tbl_porder_idtbl_porder`='$orderID' AND `tbl_porder_detail`.`freeqty`>0";
$resultorderinfofree=$conn->query($sqlorderinfofree);

// $sqlorderproductfive="SELECT `refillqty`, `returnqty`, `newqty` FROM `tbl_porder_detail` WHERE `status`=1 AND `tbl_porder_idtbl_porder`='$orderID' AND `tbl_product_idtbl_product`=5";
// $resultorderproductfive=$conn->query($sqlorderproductfive);
// $roworderproductfive=$resultorderproductfive->fetch_assoc();

// $sqlcheque="SELECT `chequeno`, `chequedate` FROM `tbl_porder_payment` WHERE `tbl_porder_idtbl_porder`='$orderID' AND `status`=1";
// $resultcheque=$conn->query($sqlcheque);
// $rowcheque=$resultcheque->fetch_assoc();

// $sqldelivery="SELECT * FROM `tbl_porder_delivery` WHERE `tbl_porder_idtbl_porder`='$orderID' AND `status`=1";
// $resultdelivery=$conn->query($sqldelivery);
// $rowdelivery=$resultdelivery->fetch_assoc();

// $lorryID=$rowdelivery['vehicleid'];
// $trailerID=$rowdelivery['trailerid'];

// $sqlvehicle="SELECT `vehicleno` FROM `tbl_vehicle` WHERE `idtbl_vehicle`='$lorryID' AND `status`=1 AND `type`=0";
// $resultvehicle=$conn->query($sqlvehicle);
// $rowvehicle=$resultvehicle->fetch_assoc();

// $sqltrailer="SELECT `vehicleno` FROM `tbl_vehicle` WHERE `idtbl_vehicle`='$trailerID' AND `status`=1 AND `type`=1";
// $resulttrailer=$conn->query($sqltrailer);
// $rowtrailer=$resulttrailer->fetch_assoc();

$arrayaccessories=array();
$sqlaccessories="SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1 AND `tbl_product_category_idtbl_product_category`=2";
$resultaccessories=$conn->query($sqlaccessories);
while($rowaccessories=$resultaccessories->fetch_assoc()){
    $objaccessories=new stdClass();
    $objaccessories->accessoriesID=$rowaccessories['idtbl_product'];
    $objaccessories->accessories=$rowaccessories['product_name'];

    array_push($arrayaccessories, $objaccessories);
}

?>
<div class="row">
    <div class="col-12 small">
        <table class="table table-borderless table-sm text-center w-100 tableprint">
            <tbody>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <h3 class="font-weight-light m-0">ENOMAX Holdings (PVT) LTD</h3>
                        <h4 class="mt-2">Purchsing Order</h4>
                    </td>
                    <td>&nbsp;</td>
                </tr>
            </tbody>            
        </table>        
    </div>
</div>
<hr class="border-dark mb-1">

<div class="row">
    <div class="col-12 small">
        <table class="w-100 tableprint">
            <tbody>
                <tr>
                    <td class="align-top">
                        <h6 class="small">Customer details</h6>
                        <b>Name:</b> <?php echo $roworder['customer'] ?><br>
                        <b>Contact:</b> <?php echo $roworder['phone'] ?>

                    </td>
                    <td>&nbsp;</td>
                </tr>
            </tbody>            
        </table>          
    </div>
</div>
<div class="row">
    <div class="col-12">
        <table class="table table-bordered table-black bg-transparent table-sm small w-100 tableprint mt-2">
            <tr>
                <td class="small">Order Date</td>
                <td class="small"><?php echo $roworder['orderdate'] ?></td>
                <td class="small">Distributor</td>
                <td class="small" colspan="3">Switch Company (Pvt) Ltd</td>
                <td class="small">Code</td>
                <td class="small">1008684</td>
            </tr>
            <tr>
                <td class="small">ASM Name</td>
                <td class="small" colspan="7"><?php echo $_SESSION['name'] ?></td>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <table class="table table-striped table-bordered table-black table-sm small bg-transparent text-center mb-0 tableprint">
            <thead>
                <tr>
                    <td class="align-top small">#</td>
                    <td class="align-top small">Prodcut Name</td>
                    <td class="align-top small text-right">Unit Price</td>
                    <td class="align-top small text-center">Prodcut Qty</td>
                    <td class="align-top small text-right">Total</td>
                </tr>
            </thead>
            <tbody>
                <?php $i=1; while($roworderinfo=$resultorderinfo->fetch_assoc()){ ?>
                <tr>
                    <td class="align-top small"><?php echo $i ?></td>
                    <td class="align-top small"><?php echo $roworderinfo['product_name'] ?></td>
                    <td class="align-top small text-right"><?php echo number_format($roworderinfo['saleprice'],2) ?></td>
                    <td class="align-top small text-center"><?php echo $roworderinfo['qty'] ?></td>
                    <td class="align-top small text-right"><?php echo number_format(($roworderinfo['qty']*$roworderinfo['saleprice']), 2) ?></td>
                </tr>
                <?php $i++;} ?>
            </tbody>
            <tfoot>
                <tr>
                    <th class="text-left" colspan="4">Subtotal</th>
                    <th class="text-right"><?php echo number_format($roworder['subtotal'], 2) ?></th>
                </tr>
                <tr>
                    <th class="text-left" colspan="4">Discount</th>
                    <th class="text-right"><?php echo number_format($roworder['disamount'], 2) ?></th>
                </tr>
                <tr>
                    <th class="text-left" colspan="4">Net Total</th>
                    <th class="text-right"><?php echo number_format($roworder['nettotal'], 2) ?></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<div class="row mt-3">
    <div class="col-6">
        <h4 class="small">Free Product Information</h4>
        <table class="table table-striped table-bordered table-black table-sm small bg-transparent text-center mb-0 tableprint">
            <thead>
                <tr>
                    <td class="align-top small">Prodcut Name</td>
                    <td class="align-top small text-center">Free Qty</td>
                </tr>
            </thead>
            <tbody>
                <?php $i=1; while($roworderinfofree=$resultorderinfofree->fetch_assoc()){ ?>
                <tr>
                    <td class="align-top small"><?php echo $roworderinfofree['product_name'] ?></td>
                    <td class="align-top small text-center"><?php echo $roworderinfofree['freeqty'] ?></td>
                </tr>
                <?php $i++;} ?>
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-12 small">
        <table class="table table-borderless table-sm text-center w-100 tableprint w-100">
            <tbody>
                <tr>
                    <td>
                        **Collection only<br>
                        **Time (For collections - approximate plant arrival time / for delivery - approximate time to arrive to distributor)
                    </td>
                </tr>
            </tbody>            
        </table>        
    </div>
</div>
<div class="row">
    <div class="col-12">
        <table class="table table-striped table-bordered table-black table-sm small bg-transparent text-center tableprint">
            <tbody>
                <tr>
                    <td><?php echo $roworder['remark'] ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-12 small">
        <table class="w-100 tableprint">
            <tbody>
                <tr>
                    <td class="align-top">
                        <h6 class="small">Cheque Information</h6>
                        Cheque no: <?php //echo $rowcheque['chequeno'] ?><br>
                        Cheque Date : <?php //echo $rowcheque['chequedate'] ?>
                    </td>
                    <td>&nbsp;</td>
                    <td class="text-center small align-bottom">
                        <hr class="border-dark m-0">
                        Confirm By: Signature of distribution manager
                    </td>
                    <td class="text-center small align-bottom">
                        <hr class="border-dark m-0">
                        Agreed by: Signature of ASM / DSE /DSO
                    </td>
                </tr>
            </tbody>            
        </table>          
    </div>
</div>
