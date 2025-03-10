<?php
require_once('../connection/db.php');

$grnid=$_POST['grnid'];

$sqlvat = "SELECT `idtbl_vat_info`, `vat` FROM `tbl_vat_info` ORDER BY `idtbl_vat_info` DESC LIMIT 1";
$resultvat = $conn->query($sqlvat);
$rowvat = $resultvat->fetch_assoc();

$sql="SELECT `tbl_grndetail`.`qty`, `tbl_grndetail`.`unitprice`, `tbl_grndetail`.`total`, `tbl_product`.`product_name` FROM `tbl_grndetail` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_grndetail`.`tbl_product_idtbl_product` WHERE `tbl_grndetail`.`tbl_grn_idtbl_grn`='$grnid' AND `tbl_grndetail`.`status`=1 ";
$result=$conn->query($sql);
?>
<table class="table table-striped table-bordered table-dark table-sm" id="">
    <thead>
        <tr>
            <th>Product</th>
            <th class="text-center">Unit price</th>
            <th class="text-center">Qty</th>
            <th class="text-right">Total</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row=$result->fetch_assoc()){ ?>
        <tr>
            <td><?php echo $row['product_name']; ?></td>
            <td class="text-center"><?php echo number_format($row['unitprice'],2); ?></td>
            <td class="text-center"><?php echo $row['qty']; ?></td>
            <td class="text-right"><?php echo number_format($row['total'], 2); ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>