<?php 
require_once('../connection/db.php');

$first_date = date('01-m-Y');
$last_date = date("Y-m-t",strtotime($first_date));
$first_week = date("W",strtotime($first_date));
$last_week = date("W",strtotime($last_date));


$sqlcustomer="SELECT `tbl_customer`.`idtbl_customer`, `tbl_customer`.`name`, `tbl_customer_stock`.`fullqty` FROM `tbl_customer` LEFT JOIN `tbl_customer_stock` ON `tbl_customer_stock`.`tbl_customer_idtbl_customer`=`tbl_customer`.`idtbl_customer` WHERE `tbl_customer`.`status`=1 AND `tbl_customer_stock`.`status`=1";
$resultcustomer =$conn-> query($sqlcustomer);
?>
<table class="table table-striped table-bordered table-sm" id="tableoutstanding">
    <thead>
        <tr>
            <th rowspan="2">Customer</th>
            <?php for($i=$first_week;$i<=$last_week;$i++){?>
            <th class="text-center" colspan="2">Week <?php echo $i; ?></th>
            <?php } ?>
        </tr>
        <tr>
            <?php for($i=$first_week;$i<=$last_week;$i++){?>
            <th class="text-center">Buffer Limit</th>
            <th class="text-center">Maintain Limit</th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php 
        while($rowcustomer = $resultcustomer-> fetch_assoc()){ 
            $customerID=$rowcustomer['idtbl_customer'];
        ?>
        <tr>
            <td><?php echo $rowcustomer['name']; ?></td>
            <?php 
            for($i=$first_week;$i<=$last_week;$i++){
                $sqllastbillavg="SELECT SUM(`newqty`+`refillqty`+`trustqty`) AS `sumqty` FROM `tbl_invoice_detail` WHERE `status`=1 AND `tbl_invoice_idtbl_invoice` IN (SELECT `idtbl_invoice` FROM `tbl_invoice` WHERE `status`=1 AND `tbl_customer_idtbl_customer`='$customerID' AND WEEK(`date`)='$i')";
                $resultlastbillavg =$conn-> query($sqllastbillavg);
                $rowlastbillavg = $resultlastbillavg-> fetch_assoc();
            ?>
            <td class="text-center"><?php echo $rowcustomer['fullqty']; ?></td>
            <td class="text-center"><?php echo round($rowlastbillavg['sumqty']); ?></td>
            <?php } ?>
        </tr>
        <?php } ?>
    </tbody>
</table>