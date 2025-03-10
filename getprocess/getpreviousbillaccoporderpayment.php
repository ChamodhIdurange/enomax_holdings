<?php
require_once('../connection/db.php');

$recordID=$_POST['recordID'];

$sql="SELECT `tbl_customer`.`name`, `tbl_invoice`.`idtbl_invoice`, `tbl_invoice`.`date`, `tbl_invoice`.`total` FROM `tbl_invoice` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_invoice`.`tbl_customer_idtbl_customer` WHERE `tbl_invoice`.`idtbl_invoice` IN (SELECT `tbl_invoice_idtbl_invoice` FROM `tbl_porder_payment_has_tbl_invoice` WHERE `tbl_porder_payment_idtbl_porder_payment`='$recordID') AND `tbl_invoice`.`status`=1";
$result=$conn->query($sql);

$nettotal=0;
?>
<div class="row">
    <div class="col-12">
        <table class="table table-striped table-bordered table-sm" id="grnlisttable">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Invoice</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row=$result->fetch_assoc()){ ?>
                <tr>
                    <td><?php echo $row['date']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo 'INV-'.$row['idtbl_invoice']; ?></td>
                    <td class="text-right"><?php echo number_format($row['total'], 2); $nettotal=$nettotal+$row['total']; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-12 text-right">
        <hr>
        <h4 class="font-weight-normal">Rs. <?php echo number_format($nettotal, 2) ?></h4>
    </div>
</div>