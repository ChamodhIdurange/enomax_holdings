<?php 
require_once('../connection/db.php');

$validfrom=$_POST['validfrom'];
$validto=$_POST['validto'];

$sqlinvoicelist="SELECT `tbl_invoice`.`idtbl_invoice`, `tbl_invoice`.`date`, `tbl_invoice`.`total`, `tbl_invoice`.`paymentcomplete`, `tbl_customer`.`name` FROM `tbl_invoice` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_invoice`.`tbl_customer_idtbl_customer` WHERE `tbl_invoice`.`status`=1 AND `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto'";
$resultinvoicelist =$conn-> query($sqlinvoicelist);

?>
<table class="table table-sm table-striped table-bordered w-100" id="tablesearchview">
    <thead>
        <tr>
            <th>Invoice No</th>
            <th>Invoice Date</th>
            <th>Customer</th>
            <th class="text-right">Credit</th>
            <th class="text-right">Cash</th>
            <th class="text-right">Cheque</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        while($rowinvoicelist = $resultinvoicelist-> fetch_assoc()){ 
            $cashtotal=0;
            $chequetotal=0;
            $chequelist='';

            $invoiceID=$rowinvoicelist['idtbl_invoice'];
            $sqlpayinfo="SELECT `method`, `amount`, `chequeno` FROM `tbl_invoice_payment_detail` WHERE `status`=1 AND `tbl_invoice_payment_idtbl_invoice_payment` IN (SELECT `tbl_invoice_payment_idtbl_invoice_payment` FROM `tbl_invoice_payment_has_tbl_invoice` WHERE `tbl_invoice_idtbl_invoice`='$invoiceID')";
            $resultpayinfo =$conn-> query($sqlpayinfo);
            while($rowpayinfo = $resultpayinfo-> fetch_assoc()){ 
                if($rowpayinfo['method']==1){
                    $cashtotal=$cashtotal+$rowpayinfo['amount'];
                }
                else{
                    $chequetotal=$chequetotal+$rowpayinfo['amount'];
                    if($chequelist!=''){
                        $chequelist.='/';
                    }
                    $chequelist.=$rowpayinfo['chequeno'];
                }
            }

            $sqlinvpayamount="SELECT SUM(`payamount`) AS `payamount` FROM `tbl_invoice_payment_has_tbl_invoice` WHERE `tbl_invoice_idtbl_invoice`='$invoiceID'";
            $resultinvpayamount =$conn-> query($sqlinvpayamount);
            $rowinvpayamount = $resultinvpayamount-> fetch_assoc();
        ?>
        <tr class="<?php if($rowinvoicelist['paymentcomplete']==1){echo 'table-success';} ?>">
            <td><?php echo 'INV-'.$rowinvoicelist['idtbl_invoice'] ?><i class="far fa-question-circle fa-pull-right pointer mt-1 text-primary invoiceinfobtn" id="<?php echo $invoiceID; ?>"></i></td>
            <td><?php echo $rowinvoicelist['date'] ?></td>
            <td><?php echo $rowinvoicelist['name'] ?></td>
            <td class="text-right"><?php if($rowinvoicelist['paymentcomplete']==0){echo number_format(($rowinvoicelist['total']-$rowinvpayamount['payamount']),2);}else{echo number_format('0',2);} ?></td>
            <td class="text-right"><?php echo number_format($cashtotal,2) ?></td>
        <td class="text-right"><?php echo number_format($chequetotal,2) ?><?php if($chequetotal>0){ ?><button class="btn btn-sm btn-link fa-pull-right" data-container="body" data-placement="bottom" data-toggle="popover" data-trigger="hover" title="Cheque Info" data-content="<?php echo $chequelist; ?>"><i class="far fa-question-circle"></i></button><?php } ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>