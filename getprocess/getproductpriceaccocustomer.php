<?php
require_once('../connection/db.php');

$cusID=$_POST['cusID'];
$deletecheck=$_POST['deletecheck'];

$sql="SELECT `tbl_customer_product`.`saleprice`, `tbl_customer_product`.`status`, `tbl_customer_product`.`idtbl_customer_product`, `tbl_product`.`product_name` FROM `tbl_customer_product` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_customer_product`.`tbl_product_idtbl_product` WHERE `tbl_customer_product`.`tbl_customer_idtbl_customer`='$cusID' AND `tbl_customer_product`.`status`=1";
$result=$conn->query($sql);
?>
<table class="table table-striped table-bordered table-sm" id="tableproductlist">
    <thead>
        <tr>
            <th>#</th>
            <th>Product</th>
            <th class="text-right">New Sale Price</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row=$result->fetch_assoc()){ ?>
        <tr>
            <td><?php echo $row['idtbl_customer_product'] ?></td>
            <td><?php echo $row['product_name'] ?></td>
            <td class="text-right"><?php echo number_format($row['saleprice'], 2) ?></td>
            <td class="text-center"><button class="btn btn-outline-danger btn-sm btnremoveproduct <?php if($deletecheck==0){echo 'd-none';} ?>" id="<?php echo $row['idtbl_customer_product'] ?>"><i class="fas fa-trash-alt"></i></button></td>
        </tr>
        <?php } ?>
    </tbody>
</table>