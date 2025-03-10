<?php 
require_once('../connection/db.php');

$totalbalnew=0;
$totalbalrefill=0;

$validfrom=$_POST['validfrom'];
$validto=$_POST['validto'];

$sqlinvoice="SELECT `tbl_customer`.`name`, `tbl_invoice`.`idtbl_invoice`, `tbl_invoice`.`date`, `tbl_invoice`.`total` FROM `tbl_customer` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`tbl_customer_idtbl_customer`=`tbl_customer`.`idtbl_customer` WHERE `tbl_customer`.`type`=1 AND `tbl_customer`.`status`=1 AND `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' AND `tbl_invoice`.`status`=1 AND `tbl_invoice`.`companydiffsend`=0";
$resultinvoice=$conn->query($sqlinvoice);
?>
<table class="table table-striped table-bordered table-sm small">
    <thead>
        <tr>
            <th>Date</th>
            <th>Invoice</th>
            <th>InvoiceID</th>
            <th>Customer</th>
            <th>Product</th>
            <th class="text-center">New</th>
            <th class="text-center">Refill & Trust</th>
            <th class="text-right">New Bill</th>
            <th class="text-right">New Actual</th>
            <th class="text-right">New Diff.</th>
            <th class="text-right">Refill Bill</th>
            <th class="text-right">Refill Actual</th>
            <th class="text-right">Refill Diff.</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while($rowinvoice=$resultinvoice->fetch_assoc()){ 
            $invoiceID=$rowinvoice['idtbl_invoice'];

            $sqlinvoicedetail="SELECT `tbl_product`.`product_name`, `tbl_product`.`idtbl_product`, `tbl_product`.`newsaleprice` AS `productnewsaleprice`, `tbl_product`.`refillsaleprice`, `tbl_invoice_detail`.`newqty`, `tbl_invoice_detail`.`refillqty`, `tbl_invoice_detail`.`trustqty`, `tbl_invoice_detail`.`returnqty`, `tbl_invoice_detail`.`newrefillprice`, `tbl_invoice_detail`.`newsaleprice` FROM `tbl_invoice_detail` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_invoice_detail`.`tbl_product_idtbl_product` WHERE `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`='$invoiceID' AND `tbl_invoice_detail`.`status`=1";
            $resultinvoicedetail=$conn->query($sqlinvoicedetail);
        ?>
        <tr>
            <td><?php echo $rowinvoice['date']; ?></td>
            <td><?php echo 'INV-'.$rowinvoice['idtbl_invoice']; ?></td>
            <td class="invnumber"><?php echo $rowinvoice['idtbl_invoice']; ?></td>
            <td colspan="10"><?php echo $rowinvoice['name']; ?></td>
        </tr>
        <?php 
        while($rowinvoicedetail=$resultinvoicedetail->fetch_assoc()){ 
            $refilltrust=$rowinvoicedetail['refillqty']+$rowinvoicedetail['trustqty'];
            $newqty=$rowinvoicedetail['newqty'];
        ?>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><?php echo $rowinvoicedetail['product_name']; ?></td>
            <td class="text-center"><?php echo $newqty; ?></td>
            <td class="text-center"><?php echo $refilltrust ?></td>
            <td class="text-right"><?php echo number_format($rowinvoicedetail['newsaleprice'],2); ?></td>
            <td class="text-right"><?php echo number_format($rowinvoicedetail['productnewsaleprice'],2); ?></td>
            <td class="text-right text-danger"><?php echo number_format(($newqty*($rowinvoicedetail['productnewsaleprice']-$rowinvoicedetail['newsaleprice'])),2); $totalbalnew=$totalbalnew+($newqty*($rowinvoicedetail['productnewsaleprice']-$rowinvoicedetail['newsaleprice'])) ?></td>
            <td class="text-right"><?php echo number_format($rowinvoicedetail['newrefillprice'],2); ?></td>
            <td class="text-right"><?php echo number_format($rowinvoicedetail['refillsaleprice'],2); ?></td>
            <td class="text-right text-danger"><?php echo number_format(($refilltrust*($rowinvoicedetail['refillsaleprice']-$rowinvoicedetail['newrefillprice'])),2); $totalbalrefill=$totalbalrefill+($refilltrust*($rowinvoicedetail['refillsaleprice']-$rowinvoicedetail['newrefillprice'])) ?></td>
        </tr>
        <?php }} ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="9">&nbsp;</th>
            <th class="text-right"><?php echo number_format($totalbalnew, 2) ?></th>
            <th colspan="2">&nbsp;</th>
            <th class="text-right"><?php echo number_format($totalbalrefill, 2) ?></th>
        </tr>
    </tfoot>
</table>
<input type="hidden" id="totalnewrecovery" value="<?php echo $totalbalnew; ?>">
<input type="hidden" id="totalrefillrecovery" value="<?php echo $totalbalrefill; ?>">