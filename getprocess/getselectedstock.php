<?php 
session_start();
require_once('../connection/db.php');

$fromdate = $_POST['fromdate'];
$today = date("Y-m-d");

$sqlstock = "SELECT `p`.`product_code`, `p`.`saleprice`, `sp`.`category` as `subcat`, `gp`.`category` as `groupcat`, `pc`.`category` as `maincat`, `p`.`product_name`, COALESCE(SUM(`s`.`qty`), 0)AS `qty`, `m`.`name`, (SELECT COALESCE(SUM(`h`.`qty`), 0) FROM `tbl_customer_order_hold_stock` AS `h` WHERE `h`.`status`='1' AND `h`.`invoiceissue`='0' AND `h`.`tbl_product_idtbl_product`=`p`.`idtbl_product`) AS 'holdqty' 
             FROM `tbl_stock` as `s` 
             LEFT JOIN `tbl_product` as `p` ON (`p`.`idtbl_product`=`s`.`tbl_product_idtbl_product`) 
             LEFT JOIN `tbl_sizes` AS `m` ON (`m`.`idtbl_sizes` = `p`.`tbl_sizes_idtbl_sizes`) 
             LEFT JOIN `tbl_product_category` AS `pc` ON (`p`.`tbl_product_category_idtbl_product_category` = `pc`.`idtbl_product_category`) 
             LEFT JOIN `tbl_sub_product_category` AS `sp` ON (`p`.`tbl_sub_product_category_idtbl_sub_product_category` = `sp`.`idtbl_sub_product_category`) 
             LEFT JOIN `tbl_group_category` AS `gp` ON (`p`.`tbl_group_category_idtbl_group_category` = `gp`.`idtbl_group_category`) 
             WHERE `s`.`status`=1 AND `p`.`status`=1 
             GROUP BY `s`.`tbl_product_idtbl_product` 
             ORDER BY `m`.`sequence`, `m`.`tbl_size_categories_idtbl_size_categories` ASC";

$resultstock = $conn->query($sqlstock);

if ($resultstock->num_rows > 0) {
    echo '<table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Code</th>
                    <th>Size</th>
                    <th>Retail price</th>
                    <th class="text-center">Available Stock</th>
                    <th class="text-center">Hold Stock</th>
                    <th>Total price</th>
                </tr>
            </thead>
            <tbody>';
    while ($rowstock = $resultstock->fetch_assoc()) {
        $total = $rowstock['saleprice'] * $rowstock['qty'];
        echo '<tr>
                <td>' . $rowstock['product_name'] . '</td>
                <td>' . $rowstock['product_code'] . '</td>
                <td>' . $rowstock['name'] . '</td>
                <td class="text-right">Rs.' . number_format($rowstock['saleprice'], 2, '.', ',') . '</td>
                <td class="text-center">' . $rowstock['qty'] . '</td>
                <td class="text-center">' . $rowstock['holdqty'] . '</td>
                <td class="text-right">Rs.' . number_format($total, 2, '.', ',') . '</td>
            </tr>';
    }
    echo '</tbody></table>';
} else {
    echo '<div class="alert alert-info" role="alert">No records found.</div>';
}
?>


