<?php
require_once('../connection/db.php');

session_start();
$customer = $_POST['customer'];
$productID = $_POST['productID'];

$sql = "SELECT 
`p`.`product_name`, 
`p`.`idtbl_product`, 
`d`.`idtbl_invoice_detail`, 
`d`.`unitprice`, 
`d`.`qty`,
`inv`.`total`,
`d`.`saleprice`, 
`d`.`tbl_invoice_idtbl_invoice`, 
`inv`.`tbl_customer_idtbl_customer` 
FROM 
`tbl_product` AS `p` 
LEFT JOIN 
`tbl_invoice_detail` AS `d` 
ON (`d`.`tbl_product_idtbl_product` = `p`.`idtbl_product`) 
LEFT JOIN 
`tbl_invoice` AS `inv` 
ON (`inv`.`idtbl_invoice` = `d`.`tbl_invoice_idtbl_invoice`) 
WHERE 
`inv`.`tbl_customer_idtbl_customer` = '$customer' AND `p`.`idtbl_product`='$productID'";
$result = $conn->query($sql);
?>
 <small id="" class="form-text text-danger">click Qty and Discount columns for add Qty & Discount</small>
<table class="table table-hover small" id="tablamount">
    <thead>
        <tr>
            <th class="d-none">#</th>
            <th>INV</th>
            <th>Unit Price</th>
            <th>Qty</th>
            <th>Discount %</th>
            <th class="d-none">name</th>
            <th></th>
        </tr>
    </thead>
    <tbody>

        <?php
        while ($rowresult = $result->fetch_assoc()) { ?>
            <tr>
                <td class="d-none"><?php echo $rowresult['idtbl_product']; ?></td>
                
                <td>INV-<?php echo $rowresult['tbl_invoice_idtbl_invoice']; ?></td>
                <td><?php echo number_format($rowresult['unitprice'], 2); ?></td>
                <td contenteditable="true"></td>
                <td contenteditable="true"></td>
                <td class="d-none"><?php echo $rowresult['product_name']; ?></td>
                <td class="d-none"><?php echo $rowresult['tbl_invoice_idtbl_invoice']; ?></td>
                <td>
                    <div class="custom-control custom-checkbox"><input type="checkbox" class="form-check-input checkinvoice" id="<?php echo $rowresult['idtbl_product']; ?>"></div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

