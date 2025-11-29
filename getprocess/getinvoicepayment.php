<?php
require_once('../connection/db.php');

// Get tax rate
$taxQuery = "SELECT `rate` FROM `tbl_tax` LIMIT 1";
$taxResult = $conn->query($taxQuery);
$tax = 0;
if ($taxResult && $taxResult->num_rows > 0) {
    $taxRow = $taxResult->fetch_assoc();
    $tax = $taxRow['rate'];
}

if (!empty($_POST['invoiceno'])) {
    $invoiceno = $_POST['invoiceno'];
    $invoiceID = substr($invoiceno, 9);

    $sql = "SELECT DATEDIFF(CURDATE(), `tbl_invoice`.`date`) AS `date_diff`, `tbl_customer_order`.`remark`, `tbl_customer_order`.`vat`, `tbl_customer_order`.`podiscountpercentage`, `tbl_customer`.`vat_num`, `tbl_invoice`.`tbl_customer_idtbl_customer`, `tbl_employee`.`name` as `asm`, `tbl_invoice`.`idtbl_invoice`,`tbl_invoice`.`invoiceno`, `tbl_invoice`.`paymentcomplete`, `tbl_invoice`.`date`, `tbl_invoice`.`nettotal`, `tbl_invoice`.`total`, `tbl_invoice`.`discount`, `tbl_customer_order`.`idtbl_customer_order`, SUM(`tbl_invoice_payment_has_tbl_invoice`.`payamount`) AS `payamount` FROM `tbl_invoice` LEFT JOIN `tbl_invoice_payment_has_tbl_invoice` ON `tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_idtbl_invoice`=`tbl_invoice`.`idtbl_invoice` LEFT JOIN `tbl_customer_order` ON `tbl_customer_order`.`idtbl_customer_order`=`tbl_invoice`.`tbl_customer_order_idtbl_customer_order` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer` = `tbl_invoice`.`tbl_customer_idtbl_customer` LEFT JOIN `tbl_employee` ON `tbl_employee`.`idtbl_employee` = `tbl_customer_order`.`tbl_employee_idtbl_employee` WHERE `tbl_invoice`.`idtbl_invoice`='$invoiceID' AND `tbl_invoice`.`status`=1 AND `tbl_invoice`.`paymentcomplete`=0 AND `tbl_customer_order`.`delivered`=1  Group BY `tbl_invoice`.`idtbl_invoice`";
    $result = $conn->query($sql);
} else if (!empty($_POST['customerID'])) {
    $customerID = $_POST['customerID'];

    $sql = "SELECT DATEDIFF(CURDATE(), `tbl_invoice`.`date`) AS `date_diff`, `tbl_customer_order`.`remark`, `tbl_customer_order`.`vat`, `tbl_customer_order`.`podiscountpercentage`, `tbl_customer`.`vat_num`, `tbl_invoice`.`tbl_customer_idtbl_customer`, `tbl_employee`.`name` as `asm`, `tbl_invoice`.`idtbl_invoice`,`tbl_invoice`.`invoiceno`, `tbl_invoice`.`paymentcomplete`, `tbl_invoice`.`date`, `tbl_invoice`.`nettotal`, `tbl_invoice`.`total`, `tbl_invoice`.`discount`, `tbl_customer_order`.`idtbl_customer_order`, SUM(`tbl_invoice_payment_has_tbl_invoice`.`payamount`) AS `payamount` FROM `tbl_invoice` LEFT JOIN `tbl_invoice_payment_has_tbl_invoice` ON `tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_idtbl_invoice`=`tbl_invoice`.`idtbl_invoice` LEFT JOIN `tbl_customer_order` ON `tbl_customer_order`.`idtbl_customer_order`=`tbl_invoice`.`tbl_customer_order_idtbl_customer_order` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer` = `tbl_invoice`.`tbl_customer_idtbl_customer` LEFT JOIN `tbl_employee` ON `tbl_employee`.`idtbl_employee` = `tbl_customer_order`.`tbl_employee_idtbl_employee` WHERE `tbl_invoice`.`tbl_customer_idtbl_customer`='$customerID' AND `tbl_invoice`.`status`=1 AND `tbl_invoice`.`paymentcomplete`=0 AND `tbl_customer_order`.`delivered`=1 Group BY `tbl_invoice`.`idtbl_invoice`";
    $result = $conn->query($sql);
} else if (!empty($_POST['asmID'])) {
    $asmID = $_POST['asmID'];

    $sql = "SELECT DATEDIFF(CURDATE(), `tbl_invoice`.`date`) AS `date_diff`, `tbl_customer_order`.`remark`, `tbl_customer_order`.`vat`, `tbl_customer_order`.`podiscountpercentage`, `tbl_customer`.`vat_num`, `tbl_invoice`.`tbl_customer_idtbl_customer`, `tbl_employee`.`name` as `asm`, `tbl_invoice`.`idtbl_invoice`,`tbl_invoice`.`invoiceno`, `tbl_invoice`.`paymentcomplete`, `tbl_invoice`.`date`, `tbl_invoice`.`nettotal`, `tbl_invoice`.`total`, `tbl_invoice`.`discount`, `tbl_customer_order`.`idtbl_customer_order`, SUM(`tbl_invoice_payment_has_tbl_invoice`.`payamount`) AS `payamount` FROM `tbl_invoice` LEFT JOIN `tbl_invoice_payment_has_tbl_invoice` ON `tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_idtbl_invoice`=`tbl_invoice`.`idtbl_invoice` LEFT JOIN `tbl_customer_order` ON `tbl_customer_order`.`idtbl_customer_order`=`tbl_invoice`.`tbl_customer_order_idtbl_customer_order` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer` = `tbl_invoice`.`tbl_customer_idtbl_customer` LEFT JOIN `tbl_employee` ON `tbl_employee`.`idtbl_employee` = `tbl_customer_order`.`tbl_employee_idtbl_employee` WHERE `tbl_customer_order`.`tbl_employee_idtbl_employee`='$asmID' AND `tbl_invoice`.`status`=1 AND `tbl_invoice`.`paymentcomplete`=0 AND `tbl_customer_order`.`delivered`=1 Group BY `tbl_invoice`.`idtbl_invoice`";
    $result = $conn->query($sql);
}
?>
<table class="table table-striped table-bordered table-sm" id="paymentDetailTable">
    <thead>
        <tr>
            <th class="d-none">InvoiceID</th>
            <th>Invoice No</th>
            <th>ASM/CSM</th>
            <th>Date</th>
            <th class="text-right">Amount</th>
            <th class="text-right">VAT</th>
            <th class="text-right">Paid Amount</th>
            <th class="text-right">Balance</th>
            <th>Full Payment</th>
            <th>Half Payment</th>
            <th class="text-right">Payment</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()) {
            // Check if VAT customer
            $vat_num = isset($row['vat_num']) ? trim($row['vat_num']) : '';
            $isTaxCustomer = !empty($vat_num);
            $actualvat = isset($row['vat']) ? $row['vat'] : $tax;

            $final_total = 0;
            $vat_amount = 0;

            if ($isTaxCustomer) {
                // Get invoice detail for VAT calculations
                $recordID = $row['idtbl_customer_order'];
                $sqlinvoicedetail = "
                SELECT 
                    `tbl_customer_order_detail`.`qty`,
                    `tbl_customer_order_detail`.`saleprice`,
                    `tbl_customer_order_detail`.`discount`
                FROM `tbl_customer_order_detail`
                WHERE `tbl_customer_order_detail`.`tbl_customer_order_idtbl_customer_order`='$recordID' 
                  AND `tbl_customer_order_detail`.`status`=1
                ";
                $resultinvoicedetail = $conn->query($sqlinvoicedetail);

                $fulltot = 0;
                while ($rowinvoicedetail = $resultinvoicedetail->fetch_assoc()) {
                    $qtyValue = $rowinvoicedetail['qty'];
                    $base_price = $rowinvoicedetail['saleprice'] / (1 + ($actualvat / 100));
                    $base_discount = $rowinvoicedetail['discount'] / (1 + ($actualvat / 100));
                    $line_total_base = ($qtyValue * $base_price) - $base_discount;
                    $fulltot += $line_total_base;
                }

                // Calculate VAT amount and final total
                $subtotal_before_discount = $fulltot;
                $po_discount_amount = $subtotal_before_discount * ($row["podiscountpercentage"] / 100);
                $subtotal_after_po_discount = $subtotal_before_discount - $po_discount_amount;
                $vat_amount = $subtotal_before_discount * ($actualvat / 100);
                $final_total = $subtotal_after_po_discount + $vat_amount;
            } else {
                // Non-VAT customer
                $final_total = $row['nettotal'];
                $vat_amount = 0;
            }

            $balance = $final_total - $row['payamount'];
        ?>
            <tr data-final-total="<?php echo number_format($final_total, 2, '.', ''); ?>" data-is-vat-customer="<?php echo $isTaxCustomer ? '1' : '0'; ?>">
                <td class="d-none"><?php echo $row['idtbl_invoice']; ?></td>
                <td><?php echo $row['invoiceno']; ?></td>
                <td><?php echo $row['asm']; ?></td>
                <td><?php echo $row['date']; ?></td>
                <td class="text-right"><?php echo sprintf('%.2f', $row['nettotal'] - $vat_amount); ?></td>
                <td class="text-right"><?php echo sprintf('%.2f', $vat_amount); ?></td>
                <td class="text-right"><?php echo sprintf('%.2f', $row['payamount']); ?></td>
                <td class="text-right"><?php echo sprintf('%.2f', $balance); ?></td>
                <td>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input fullAmount" name="payCheck1" id="payCheck1<?php echo $row['idtbl_invoice']; ?>" value="1" <?php if ($row['payamount'] > 0) {
                                                                                                                                                                            echo 'disabled';
                                                                                                                                                                        } ?>>
                        <label class="custom-control-label small" for="payCheck1<?php echo $row['idtbl_invoice']; ?>">Full Payment</label>
                    </div>
                </td>
                <td>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input halfAmount" name="payCheck2" id="payCheck2<?php echo $row['idtbl_invoice']; ?>">
                        <label class="custom-control-label small" for="payCheck2<?php echo $row['idtbl_invoice']; ?>">Half Payment</label>
                    </div>
                </td>
                <td class='paidAmount text-right'>0.00</td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<script>
    $(document).ready(function() {
        // Full Payment checkbox handler
        $(document).on('change', '.fullAmount', function() {
            var $row = $(this).closest('tr');
            var $paidAmountCell = $row.find('.paidAmount');
            var $halfPaymentCheckbox = $row.find('.halfAmount');

            if ($(this).is(':checked')) {
                // Get the final total from data attribute
                var finalTotal = parseFloat($row.attr('data-final-total'));
                var isVatCustomer = $row.attr('data-is-vat-customer') === '1';

                // Get paid amount
                var paidAmount = parseFloat($row.find('td').eq(5).text().replace(/,/g, ''));

                // Calculate balance (final total - paid amount)
                var balance = finalTotal - paidAmount;

                // Set the payment amount to the balance
                $paidAmountCell.text(balance.toFixed(2));

                // Disable half payment checkbox
                $halfPaymentCheckbox.prop('checked', false).prop('disabled', true);
            } else {
                // Clear payment amount
                $paidAmountCell.text('0.00');

                // Enable half payment checkbox
                $halfPaymentCheckbox.prop('disabled', false);
            }
        });

        // Half Payment checkbox handler
        $(document).on('change', '.halfAmount', function() {
            var $row = $(this).closest('tr');
            var $paidAmountCell = $row.find('.paidAmount');
            var $fullPaymentCheckbox = $row.find('.fullAmount');

            if ($(this).is(':checked')) {
                // Get the final total from data attribute
                var finalTotal = parseFloat($row.attr('data-final-total'));

                // Get paid amount
                var paidAmount = parseFloat($row.find('td').eq(5).text().replace(/,/g, ''));

                // Calculate balance
                var balance = finalTotal - paidAmount;

                // Set half of the balance
                var halfAmount = balance / 2;
                $paidAmountCell.text(halfAmount.toFixed(2));

                // Disable full payment checkbox
                $fullPaymentCheckbox.prop('checked', false).prop('disabled', true);
            } else {
                // Clear payment amount
                $paidAmountCell.text('0.00');

                // Enable full payment checkbox
                $fullPaymentCheckbox.prop('disabled', false);
            }
        });
    });
</script>