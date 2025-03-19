<?php
session_start();
require_once('../connection/db.php');

$grnId=$_POST['grnId'];

$sqlorder="SELECT `idtbl_grn`, `total`, `nettotal`, `batchno`, `date` FROM `tbl_grn` WHERE `idtbl_grn`='$grnId'";
$resultorder=$conn->query($sqlorder);
$roworder=$resultorder->fetch_assoc();

$date=$roworder['date'];



$sqlorderinfo="SELECT `tbl_grndetail`.*, `tbl_product`.`product_name`, `tbl_product`.`product_code` FROM `tbl_grndetail` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_grndetail`.`tbl_product_idtbl_product` WHERE `tbl_grndetail`.`status`=1 AND `tbl_grndetail`.`tbl_grn_idtbl_grn`='$grnId'";
$resultorderinfo=$conn->query($sqlorderinfo);

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
    <div class="col-8 small">
        <table class="table table-borderless table-sm text-left w-100 tableprint">
            <tbody>
                <tr>
                    <td>
                        <h3 class="font-weight-light m-0">ENOMAX HOLDINGS (PVT) LTD</h3>
                        <h4 class="mt-2">No.46, Garden City, Minuwangoda Road, Ja-ela.</h4>
                        <h4>Tel: 011 3468568</h4>
                    </td>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-4 small">
        <table class="table table-borderless table-sm text-left w-100 tableprint">
            <tbody>
                <tr>
                    <td class="small">Order Date</td>
                    <td class="small"><?php echo $roworder['date'] ?></td>
                </tr>
                <tr>
                    <td class="small">ASM Name</td>
                    <td class="small"><?php echo $_SESSION['name'] ?></td>
                </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <table
            class="table table-striped table-bordered table-black table-sm small bg-transparent text-center mb-0 tableprint">
            <thead>
                <tr>
                    <td class="align-top small">#</td>
                    <td class="align-top small">Prodcut Name</td>
                    <td class="align-top small">Prodcut Code</td>
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
                    <td class="align-top small"><?php echo $roworderinfo['product_code'] ?></td>
                    <td class="align-top small text-right"><?php echo number_format($roworderinfo['unitprice'],2) ?>
                    </td>
                    <td class="align-top small text-center"><?php echo $roworderinfo['qty'] ?></td>
                    <td class="align-top small text-right">
                        <?php echo number_format(($roworderinfo['qty']*$roworderinfo['unitprice']), 2) ?></td>
                </tr>
                <?php $i++;} ?>
            </tbody>
            <tfoot>
                <tr>
                    <th class="text-left" colspan="5">Net Total</th>
                    <th class="text-right"><?php echo number_format($roworder['nettotal'], 2) ?></th>
                </tr>
            </tfoot>
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
                        **Time (For collections - approximate plant arrival time / for delivery - approximate time to
                        arrive to distributor)
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <table
            class="table table-striped table-bordered table-black table-sm small bg-transparent text-center tableprint">
            <tbody>
                <tr>
                    <td><?php echo $roworder['batchno'] ?></td>
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
