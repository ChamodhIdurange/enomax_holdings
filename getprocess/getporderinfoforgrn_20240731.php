<?php
require_once('../connection/db.php');

$orderID = $_POST['ponumber'];

// Retrieve the latest VAT rate
$sqlvat = "SELECT idtbl_vat_info, vat FROM tbl_vat_info ORDER BY idtbl_vat_info DESC LIMIT 1";
$resultvat = $conn->query($sqlvat);
$rowvat = $resultvat->fetch_assoc();
$vatRate = $rowvat['vat'] / 100; // Convert VAT percentage to decimal

// Retrieve order details
$sqlorderdetail = "SELECT tbl_porder_detail.unitprice, tbl_porder_detail.qty, tbl_product.product_name, tbl_product.idtbl_product 
                   FROM tbl_porder_detail 
                   LEFT JOIN tbl_product ON tbl_product.idtbl_product = tbl_porder_detail.tbl_product_idtbl_product 
                   WHERE tbl_porder_detail.status = 1 AND tbl_porder_detail.tbl_porder_idtbl_porder = '$orderID'";
$resultorderdetail = $conn->query($sqlorderdetail);

$totalWithoutVAT = 0;

while ($roworderdetail = $resultorderdetail->fetch_assoc()) {
    $totrefill = $roworderdetail['qty'] * $roworderdetail['unitprice'];
    $totalWithoutVAT += $totrefill;
    ?>
    <tr>
        <td><?php echo $roworderdetail['product_name'] ?></td>
        <td class="d-none"><?php echo $roworderdetail['idtbl_product'] ?></td>
        <td class="d-none"><?php echo $roworderdetail['unitprice'] ?></td>
        <td class="text-right"><?php echo number_format($roworderdetail['unitprice'], 2) ?></td>
        <td class="text-center editnewqty"><?php echo $roworderdetail['qty'] ?></td>
        <td class="total d-none"><?php echo $totrefill ?></td>
        <td class="text-right"><?php echo number_format($totrefill, 2) ?></td>
    </tr>
    <?php
}
$vatAmount = $totalWithoutVAT * $vatRate;
$totalWithVAT = $totalWithoutVAT + $vatAmount;
?>
<script>
    // Embed PHP values into JavaScript variables
    var vatRate = <?php echo json_encode($vatRate); ?>;
    var totalWithoutVAT = <?php echo json_encode($totalWithoutVAT); ?>;
    var vatAmount = <?php echo json_encode($vatAmount); ?>;
    var totalWithVAT = <?php echo json_encode($totalWithVAT); ?>;

    // Update the HTML elements with calculated values
    document.getElementById('showPricewithoutvat').innerHTML = 'Rs. ' + totalWithoutVAT.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    document.getElementById('showtaxAmount').innerHTML = 'Rs. ' + vatAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    document.getElementById('showPrice').innerHTML = 'Rs. ' + totalWithVAT.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    document.getElementById('txtShowPricewithoutvat').value = totalWithoutVAT;
    document.getElementById('txtShowtaxAmount').value = vatAmount;
    document.getElementById('txtShowPrice').value = totalWithVAT;
</script>
<input type="hidden" id="vatRate" value="<?php echo $vatRate; ?>">
<?php
?>