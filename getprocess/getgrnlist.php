<?php
require_once('../connection/db.php');

$sql="SELECT `idtbl_grn`, `date`, `total`, `vatamount`, `nettotal`, `invoicenum` FROM `tbl_grn` WHERE `tbl_grn`.`status`=1 ORDER BY `idtbl_grn` DESC";
$result=$conn->query($sql);
?>
<table class="table table-striped table-bordered table-sm" id="grnlisttable">
    <thead>
        <tr>
            <th>Date</th>
            <th>GRN</th>
            <th>Invoice</th>
            <th class="text-right">Total</th>
            <th class="text-right">VAT</th>
            <th class="text-right">Net Total</th>
            <th class="text-right">&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row=$result->fetch_assoc()){ ?>
        <tr>
            <td><?php echo $row['date']; ?></td>
            <td><?php echo 'GRN-'.$row['idtbl_grn']; ?></td>
            <td><?php echo $row['invoicenum']; ?></td>
            <td class="text-right"><?php echo number_format($row['total'], 2); ?></td>
            <td class="text-right"><?php echo number_format($row['vatamount'], 2); ?></td>
            <td class="text-right"><?php echo number_format($row['nettotal'], 2); ?></td>
            <td class="text-center"><button class="btn btn-outline-dark btn-sm btnviewgrn" id="<?php echo $row['idtbl_grn']; ?>"><i class="fas fa-eye"></i></button></td>
        </tr>
        <?php } ?>
    </tbody>
</table>