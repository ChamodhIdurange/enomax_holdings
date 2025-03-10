<?php 
require_once('../connection/db.php');

$invID = $_POST['invID'];

// Query to fetch invoice details
$sql = "SELECT `tbl_product`.`product_name`, `tbl_invoice_detail`.`qty`, `tbl_invoice_detail`.`unitprice`, `tbl_invoice_detail`.`saleprice`, `tbl_invoice_detail`.`total` 
FROM `tbl_invoice_detail` 
LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_invoice_detail`.`tbl_product_idtbl_product`
WHERE `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`='$invID' AND `tbl_invoice_detail`.`status`=1";
$result = $conn->query($sql);

// Check if the query succeeded
if (!$result) {
    die('Error in SQL query: ' . $conn->error);
}

// Query to fetch the invoice total and customer ID
$sqlinvoice = "SELECT `total`, `tbl_customer_idtbl_customer` FROM `tbl_invoice` WHERE `idtbl_invoice`='$invID' AND `status`=1";
$resultinvoice = $conn->query($sqlinvoice);

// Check if the query succeeded
if (!$resultinvoice) {
    die('Error in SQL query: ' . $conn->error);
}

$rowinvoice = $resultinvoice->fetch_assoc();
$cusID = $rowinvoice['tbl_customer_idtbl_customer'];

// Query to fetch customer details
$sqlcustomer = "SELECT `type`, `name`, `nic`, `phone`, `email`, `address` FROM `tbl_customer` WHERE `idtbl_customer`='$cusID' AND `status`=1";
$resultcustomer = $conn->query($sqlcustomer);

// Check if the query succeeded
if (!$resultcustomer) {
    die('Error in SQL query: ' . $conn->error);
}

$rowcustomer = $resultcustomer->fetch_assoc();
?>

<div class="row">
    <div class="col">
        <?php echo $rowcustomer['name'].'<br>'.$rowcustomer['nic'].'<br>'.$rowcustomer['phone'].'<br>'.$rowcustomer['email'].'<br>'.$rowcustomer['address'] ?>
    </div>
</div>

<table class="table table-striped table-bordered table-sm" id="grnlisttable">
    <thead>
        <tr>
            <th>Product</th>
            <th class="text-center">QTY</th>
            <th class="text-center">Unit Price</th>
            <th class="text-center">Sale Price</th>
            <th class="text-center">Discount</th>
            <th class="text-right">Total</th>
        </tr>
    </thead>
    <tbody>
        <?php 
            while($row = $result->fetch_assoc()){
                $totnew = $row['qty'] * $row['saleprice'];
                $total = number_format(($totnew), 2);
        ?>
        <tr>
            <td><?php echo $row['product_name']; ?></td>
            <td class="text-center"><?php echo $row['qty']; ?></td>
            <td class="text-center"><?php echo $row['unitprice']; ?></td>
            <td class="text-center"><?php echo $row['saleprice']; ?></td>
            <td class="text-center"><?php echo isset($row['discount']) ? $row['discount'] : 'N/A'; ?></td>
            <td class="text-right"><?php echo $total; ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<div class="row">
    <div class="col text-right">
        <h4 class="font-weight-normal"><?php echo 'Rs '.number_format($rowinvoice['total'], 2) ?></h4>
    </div>
</div>
