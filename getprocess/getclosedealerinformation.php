<?php
require_once('../connection/db.php');

$customerID=$_POST['customerID'];

$sqlcustomer="SELECT `name`, `phone`, `email`, `address` FROM `tbl_customer` WHERE `status`=1 AND `idtbl_customer`='$customerID'";
$resultcustomer=$conn->query($sqlcustomer);
$rowcustomer=$resultcustomer->fetch_assoc();

$sqlcustomerclose="SELECT * FROM `tbl_customer_close` WHERE `tbl_customer_idtbl_customer`='$customerID' AND `status`=1";
$resultcustomerclose=$conn->query($sqlcustomerclose);
$rowcustomerclose=$resultcustomerclose->fetch_assoc();

$dealercloseID=$rowcustomerclose['idtbl_customer_close'];

$sqlcustomerclosedetail="SELECT * FROM `tbl_customer_close_detail` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_customer_close_detail`.`tbl_product_idtbl_product` WHERE `tbl_customer_close_detail`.`status`=1 AND `tbl_customer_close_detail`.`tbl_customer_close_idtbl_customer_close`='$dealercloseID'";
$resultcustomerclosedetail=$conn->query($sqlcustomerclosedetail);
$rowcustomerclosedetail=$resultcustomerclosedetail->fetch_assoc();

?>
<div class="row">
    <div class="col-12">
        <h5 class="font-weight-light"><?php echo $rowcustomer['name'] ?></h5>
        <p><?php echo $rowcustomer['phone'] ?></p>
        <p><?php echo $rowcustomer['email'] ?></p>
        <p><?php echo $rowcustomer['address'] ?></p>
    </div>
    <div class="col-12">
        <table class="table table-striped table-bordered table-sm">
            <thead>
                <tr>
                    <th>Product</th>
                    <th class="text-center">Full Qty</th>
                    <th class="text-center">Empty Qty</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $rowcustomerclosedetail['product_name'] ?></td>
                    <td class="text-center"><?php echo $rowcustomerclosedetail['newqty'] ?></td>
                    <td class="text-center"><?php echo $rowcustomerclosedetail['refillqty'] ?></td>
                    <td class="text-right"><?php echo number_format($rowcustomerclosedetail['total'], 2) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-12 text-right">
        <h3 class="font-weight-light">Total: <?php echo number_format($rowcustomerclose['nettotal'],2) ?></h3>
    </div>
</div>