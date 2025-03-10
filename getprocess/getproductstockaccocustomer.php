<?php
require_once('../connection/db.php');

$cusID=$_POST['cusID'];
$deletecheck=$_POST['deletecheck'];

$sql="SELECT `tbl_customer_stock`.`fullqty`, `tbl_customer_stock`.`emptyqty`, `tbl_customer_stock`.`status`, `tbl_customer_stock`.`idtbl_customer_stock`, `tbl_product`.`product_name` FROM `tbl_customer_stock` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_customer_stock`.`tbl_product_idtbl_product` WHERE `tbl_customer_stock`.`tbl_customer_idtbl_customer`='$cusID' AND `tbl_customer_stock`.`status`=1";
$result=$conn->query($sql);
?>
<table class="table table-striped table-bordered table-sm" id="tablestockproductlist">
    <thead>
        <tr>
            <th>#</th>
            <th>Product</th>
            <th class="text-center">Full Qty</th>
            <!-- <th class="text-center">Empty Qty</th> -->
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row=$result->fetch_assoc()){ ?>
        <tr>
            <td><?php echo $row['idtbl_customer_stock'] ?></td>
            <td><?php echo $row['product_name'] ?></td>
            <td class="text-center"><?php echo $row['fullqty'] ?></td>
            <!-- <td class="text-center"><?php //echo $row['emptyqty'] ?></td> -->
            <td class="text-center"><button class="btn btn-outline-danger btn-sm btnremovestockproduct <?php if($deletecheck==0){echo 'd-none';} ?>" id="<?php echo $row['idtbl_customer_stock'] ?>"><i class="fas fa-trash-alt"></i></button></td>
        </tr>
        <?php } ?>
    </tbody>
</table>