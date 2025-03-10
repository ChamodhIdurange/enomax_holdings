<?php
require_once('../connection/db.php');

$orderID=$_POST['ponumber'];

$sqlorderdetail="SELECT * FROM `tbl_porder_detail` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_porder_detail`.`tbl_product_idtbl_product` WHERE `tbl_porder_detail`.`status`=1 AND `tbl_porder_detail`.`tbl_porder_idtbl_porder`='$orderID'";
$resultorderdetail=$conn->query($sqlorderdetail);
while ($roworderdetail = $resultorderdetail-> fetch_assoc()) {
    $totrefill=$roworderdetail['refillqty']*$roworderdetail['refillprice'];
    $totnew=$roworderdetail['newqty']*$roworderdetail['unitprice'];
    $tottrust=$roworderdetail['trustqty']*$roworderdetail['refillprice'];
    $totsafty=$roworderdetail['saftyqty']*$roworderdetail['refillprice'];

    $total=$totnew+$totrefill+$tottrust+$totsafty;
?>
<tr>
    <td><?php echo $roworderdetail['product_name'] ?></td>
    <td class="d-none"><?php echo $roworderdetail['idtbl_product'] ?></td>
    <td class="d-none"><?php echo $roworderdetail['unitprice'] ?></td>
    <td class="d-none"><?php echo $roworderdetail['refillprice'] ?></td>
    <td class="d-none">0</td>
    <td class="d-none">0</td>
    <td class="text-center"><?php echo $roworderdetail['refillqty'] ?></td>
    <td class="text-center"><?php echo $roworderdetail['newqty'] ?></td>
    <td class="text-center"><?php echo $roworderdetail['returnqty'] ?></td>
    <td class="text-center"><?php echo $roworderdetail['trustqty'] ?></td>
    <td class="text-center"><?php echo $roworderdetail['saftyqty'] ?></td>
    <td class="text-center"><?php echo $roworderdetail['saftyreturnqty'] ?></td>
    <td class="totaldispatch d-none"><?php echo $total ?></td>
    <td class="text-right"><?php echo number_format($total,2) ?></td>
</tr>
<?php } ?>