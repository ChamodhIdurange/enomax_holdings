<?php
require_once('../connection/db.php');

$previous_week = strtotime("-1 week +1 day");

$start_week = strtotime("last sunday midnight",$previous_week);
$end_week = strtotime("next saturday",$start_week);

$start_week = date("Y-m-d",$start_week);
$end_week = date("Y-m-d",$end_week);

// echo $start_week.' '.$end_week ;

$sqlinvoice="SELECT `tbl_customer`.`name`, `tbl_invoice`.`date`, `tbl_invoice`.`total`, `tbl_invoice`.`idtbl_invoice` FROM `tbl_invoice` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_invoice`.`tbl_customer_idtbl_customer` WHERE `tbl_invoice`.`tbl_customer_idtbl_customer` IN (SELECT `idtbl_customer` FROM `tbl_customer` WHERE `type`=3 AND `status`=1) AND `tbl_invoice`.`status`=1 AND `tbl_invoice`.`chequesend`=0 ";
$resultinvoice=$conn->query($sqlinvoice);

$fulltotal=0;
?>
<table class="table table-striped table-bordered table-sm small" id="lastweektable">
    <thead>
        <tr>
            <th>Customer</th>
            <th>Date</th>
            <th>InvID</th>
            <th>Invoice</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php while($rowinvoice=$resultinvoice->fetch_assoc()){ $fulltotal=$fulltotal+$rowinvoice['total']; ?>
        <tr>
            <td><?php echo $rowinvoice['name'] ?></td>
            <td><?php echo $rowinvoice['date'] ?></td>
            <td><?php echo $rowinvoice['idtbl_invoice'] ?></td>
            <td><?php echo 'INV-'.$rowinvoice['idtbl_invoice'] ?></td>
            <td><?php echo number_format($rowinvoice['total'], 2) ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<div class="row">
    <div class="col-12 text-right">
        <h2 class="font-weight-normal"><?php echo 'Rs. '.number_format($fulltotal,2); ?></h2>
        <input type="hidden" name="previousbilltotal" id="previousbilltotal" value="<?php echo $fulltotal; ?>">
        <hr>
    </div>
</div>
