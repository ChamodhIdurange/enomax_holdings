<?php 
require_once('../connection/db.php');

$validfrom=$_POST['validfrom'];
$validto=$_POST['validto'];
$advance=$_POST['advance'];

if($advance==0){
    $sqldaily="SELECT `tbl_invoice`.`idtbl_invoice`, `tbl_invoice`.`date`, `tbl_invoice`.`total`, `tbl_customer`.`name` AS `cusname`, `tbl_employee`.`name` AS `refname`, `tbl_vehicle`.`vehicleno`, `tbl_area`.`area` FROM `tbl_invoice` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_invoice`.`tbl_customer_idtbl_customer` LEFT JOIN `tbl_employee` ON `tbl_employee`.`idtbl_employee`=`tbl_invoice`.`ref_id` LEFT JOIN `tbl_vehicle` ON `tbl_vehicle`.`idtbl_vehicle`=`tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load` LEFT JOIN `tbl_area` ON `tbl_area`.`idtbl_area`=`tbl_invoice`.`tbl_area_idtbl_area` WHERE `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' AND `tbl_invoice`.`status`=1";
    $resultdaily =$conn-> query($sqldaily);
?>
<table class="table table-striped table-bordered table-sm" id="tableoutstanding">
    <thead>
        <tr>
            <th>Invoice</th>
            <th>Date</th>
            <th>Area</th>
            <th>Customer</th>
            <th>Sale Rep</th>
            <th>Vehicle</th>
            <th class="text-right">Invoice Total</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?php while($rowdaily = $resultdaily-> fetch_assoc()){ ?>
        <tr>
            <td><?php echo $rowdaily['idtbl_invoice']; ?></td>
            <td><?php echo $rowdaily['date']; ?></td>
            <td><?php echo $rowdaily['area']; ?></td>
            <td><?php echo $rowdaily['cusname']; ?></td>
            <td><?php echo $rowdaily['refname']; ?></td>
            <td><?php echo $rowdaily['vehicleno']; ?></td>
            <td class="text-right"><?php echo number_format($rowdaily['total'], 2); ?></td>
            <td class="text-center"><button class="btn btn-outline-dark btn-sm viewbtninv" id="<?php echo $rowdaily['idtbl_invoice'] ?>"><i class="fas fa-eye"></i></button></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<?php 
} else{ 
    $sqldaily="SELECT `tbl_invoice`.`idtbl_invoice`, `tbl_invoice`.`date`, `tbl_invoice`.`total`, `tbl_customer`.`name` AS `cusname`, `tbl_customer`.`idtbl_customer`, `tbl_employee`.`name` AS `refname`, `tbl_vehicle`.`vehicleno`, `tbl_area`.`area` FROM `tbl_invoice` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_invoice`.`tbl_customer_idtbl_customer` LEFT JOIN `tbl_employee` ON `tbl_employee`.`idtbl_employee`=`tbl_invoice`.`ref_id` LEFT JOIN `tbl_vehicle` ON `tbl_vehicle`.`idtbl_vehicle`=`tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load` LEFT JOIN `tbl_area` ON `tbl_area`.`idtbl_area`=`tbl_invoice`.`tbl_area_idtbl_area` WHERE `tbl_invoice`.`date`='$validfrom' AND `tbl_invoice`.`status`=1";
    $resultdaily =$conn-> query($sqldaily);    

    $accessoriesarray=array();
    $sqlaccessories="SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1 AND `tbl_product_category_idtbl_product_category`=2";
    $resultaccessories =$conn-> query($sqlaccessories);   
    while($rowaccessories = $resultaccessories-> fetch_assoc()){
        $obj = new stdClass();
        $obj->accessID=$rowaccessories['idtbl_product'];
        $obj->access=$rowaccessories['product_name'];

        array_push($accessoriesarray, $obj);
    }
?>
    <table class="table table-striped table-bordered table-sm text-center">
        <thead>
            <tr>
                <th nowrap rowspan="2" class="text-left align-top">Vehicle</th>
                <th nowrap rowspan="2" class="text-left align-top">Customer | Product</th>
                <th nowrap colspan="3">2 KG</th>
                <th nowrap colspan="3">5 KG</th>
                <th nowrap colspan="3">12.5 KG</th>
                <th nowrap colspan="3">37.5 KG</th>
                <th nowrap colspan="<?php echo count($accessoriesarray); ?>">Accessories</th>
                <th nowrap rowspan="2" class="text-right align-top">Total</th>
                <th nowrap rowspan="2" class="text-right align-top">Cash</th>
                <th nowrap colspan="2">Cheque Detail</th>
                <th nowrap rowspan="2" class="text-right align-top">Credit</th>
                <th nowrap colspan="2">Previous Credits</th>
            </tr>
            <tr>
                <th nowrap>New</th>
                <th nowrap>Refill</th>
                <th nowrap>Empty</th>
                <th nowrap>New</th>
                <th nowrap>Refill</th>
                <th nowrap>Empty</th>
                <th nowrap>New</th>
                <th nowrap>Refill</th>
                <th nowrap>Empty</th>
                <th nowrap>New</th>
                <th nowrap>Refill</th>
                <th nowrap>Empty</th>
                <?php foreach($accessoriesarray as $rowaccessoriesarray){ ?>
                <th nowrap><?php echo $rowaccessoriesarray->access ?></th>
                <?php } ?>
                <th nowrap>No</th>
                <th nowrap class="text-right">Amount</th>
                <th nowrap class="text-right">Cash</th>
                <th nowrap class="text-right">Cheque</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $ponenewqty=0;
            $ponerefillqty=0;
            $poneemptyqty=0;

            $ptwonewqty=0;
            $ptworefillqty=0;
            $ptwoemptyqty=0;

            $pthreenewqty=0;
            $pthreerefillqty=0;
            $pthreeemptyqty=0;

            $pfournewqty=0;
            $pfourrefillqty=0;
            $pfouremptyqty=0;

            $totalamount=0;
            $totalcash=0;
            $totalcheque=0;
            $totalcredit=0;
            if($resultdaily->num_rows>0){while($rowdaily = $resultdaily-> fetch_assoc()){ 
                $invoiceID=$rowdaily['idtbl_invoice'];
                $customerID=$rowdaily['idtbl_customer'];

                $sqlproductone="SELECT `newqty`, `refillqty`, `returnqty`, `trustqty` FROM `tbl_invoice_detail` WHERE `status`=1 AND `tbl_product_idtbl_product`='6' AND `tbl_invoice_idtbl_invoice`='$invoiceID'";
                $resultproductone =$conn-> query($sqlproductone);   
                $rowproductone = $resultproductone-> fetch_assoc();

                $sqlproducttwo="SELECT `newqty`, `refillqty`, `returnqty`, `trustqty` FROM `tbl_invoice_detail` WHERE `status`=1 AND `tbl_product_idtbl_product`='4' AND `tbl_invoice_idtbl_invoice`='$invoiceID'";
                $resultproducttwo =$conn-> query($sqlproducttwo);   
                $rowproducttwo = $resultproducttwo-> fetch_assoc();

                $sqlproductthree="SELECT `newqty`, `refillqty`, `returnqty`, `trustqty` FROM `tbl_invoice_detail` WHERE `status`=1 AND `tbl_product_idtbl_product`='1' AND `tbl_invoice_idtbl_invoice`='$invoiceID'";
                $resultproductthree =$conn-> query($sqlproductthree);   
                $rowproductthree = $resultproductthree-> fetch_assoc();

                $sqlproductfour="SELECT `newqty`, `refillqty`, `returnqty`, `trustqty` FROM `tbl_invoice_detail` WHERE `status`=1 AND `tbl_product_idtbl_product`='2' AND `tbl_invoice_idtbl_invoice`='$invoiceID'";
                $resultproductfour =$conn-> query($sqlproductfour);   
                $rowproductfour = $resultproductfour-> fetch_assoc();

                $sqlcash="SELECT SUM(`amount`) AS `amount` FROM `tbl_invoice_payment_detail` WHERE `status`=1 AND `method`=1 AND `tbl_invoice_payment_idtbl_invoice_payment` IN (SELECT `tbl_invoice_payment_idtbl_invoice_payment` FROM `tbl_invoice_payment_has_tbl_invoice` WHERE `tbl_invoice_idtbl_invoice`='$invoiceID')";
                $resultcash =$conn-> query($sqlcash);   
                $rowcash = $resultcash-> fetch_assoc();

                $chequelist='';
                $chequetotal=0;
                $i=1;
                $sqlcheque="SELECT `amount`, `chequeno` FROM `tbl_invoice_payment_detail` WHERE `status`=1 AND `method`=2 AND `tbl_invoice_payment_idtbl_invoice_payment` IN (SELECT `tbl_invoice_payment_idtbl_invoice_payment` FROM `tbl_invoice_payment_has_tbl_invoice` WHERE `tbl_invoice_idtbl_invoice`='$invoiceID')";
                $resultcheque =$conn-> query($sqlcheque);   
                while($rowcheque = $resultcheque-> fetch_assoc()){
                    $chequelist.=$rowcheque['chequeno'];
                    if($i<$resultcheque->num_rows){
                        $chequelist.='/';
                    }

                    $chequetotal=$chequetotal+$rowcheque['amount'];
                    $i++;
                }
            ?>
            <tr>
                <td nowrap class="text-left"><?php echo $rowdaily['vehicleno']; ?></td>
                <td nowrap class="text-left"><?php echo $rowdaily['cusname']; ?></td>
                <td nowrap><?php echo $rowproductone['newqty']; $ponenewqty=$ponenewqty+$rowproductone['newqty']; ?></td>
                <td nowrap><?php echo $rowproductone['refillqty']; $ponerefillqty=$ponerefillqty+$rowproductone['refillqty']; ?></td>
                <td nowrap><?php echo $rowproductone['refillqty']+$rowproductone['returnqty']; $poneemptyqty=$poneemptyqty+$rowproductone['refillqty']+$rowproductone['returnqty']; ?></td>
                <td nowrap><?php echo $rowproducttwo['newqty']; $ptwonewqty=$ptwonewqty+$rowproducttwo['newqty']; ?></td>
                <td nowrap><?php echo $rowproducttwo['refillqty']; $ptworefillqty=$ptworefillqty+$rowproducttwo['refillqty']; ?></td>
                <td nowrap><?php echo $rowproducttwo['refillqty']+$rowproducttwo['returnqty']; $ptwoemptyqty=$ptwoemptyqty+$rowproducttwo['refillqty']+$rowproducttwo['returnqty']; ?></td>
                <td nowrap><?php echo $rowproductthree['newqty']; $pthreenewqty=$pthreenewqty+$rowproductthree['newqty']; ?></td>
                <td nowrap><?php echo $rowproductthree['refillqty']; $pthreerefillqty=$pthreerefillqty+$rowproductthree['refillqty']; ?></td>
                <td nowrap><?php echo $rowproductthree['refillqty']+$rowproductthree['returnqty']; $pthreeemptyqty=$pthreeemptyqty+$rowproductthree['refillqty']+$rowproductthree['returnqty']; ?></td>
                <td nowrap><?php echo $rowproductfour['newqty']; $pfournewqty=$pfournewqty+$rowproductfour['newqty']; ?></td>
                <td nowrap><?php echo $rowproductfour['refillqty']; $pfourrefillqty=$pfourrefillqty+$rowproductfour['refillqty']; ?></td>
                <td nowrap><?php echo $rowproductfour['refillqty']+$rowproductfour['returnqty']; $pfouremptyqty=$pfouremptyqty+$rowproductfour['refillqty']+$rowproductfour['returnqty']; ?></td>
                <?php 
                foreach($accessoriesarray as $rowaccessoriesarray){ 
                    $accessoriesID=$rowaccessoriesarray->access;

                    $sqlaccessoriesqty="SELECT `newqty` FROM `tbl_invoice_detail` WHERE `status`=1 AND `tbl_product_idtbl_product`='$accessoriesID' AND `tbl_invoice_idtbl_invoice`='$invoiceID'";
                    $resultaccessoriesqty =$conn-> query($sqlaccessoriesqty);   
                    $rowaccessoriesqty = $resultaccessoriesqty-> fetch_assoc();    
                ?>
                <td nowrap><?php echo $rowaccessoriesqty['newqty'] ?></td>
                <?php } ?>
                <td nowrap class="text-right"><?php echo number_format($rowdaily['total'],2); $totalamount=$totalamount+$rowdaily['total']; ?></td>
                <td nowrap class="text-right"><?php echo number_format($rowcash['amount'],2); $totalcash=$totalcash+$rowcash['amount']; ?></td>
                <td nowrap><?php echo $chequelist; ?></td>
                <td nowrap class="text-right"><?php echo number_format($chequetotal,2); $totalcheque=$totalcheque+$chequetotal; ?></td>
                <td nowrap class="text-right"><?php echo number_format(($rowdaily['total']-($rowcash['amount']+$chequetotal)),2);  $totalcredit=$totalcredit+($rowdaily['total']-($rowcash['amount']+$chequetotal)); ?></td>
                <td nowrap></td>
                <td nowrap></td>
            </tr>
            <?php } ?>
            <tr>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th><?php echo $ponenewqty; ?></th>
                <th><?php echo $ponerefillqty; ?></th>
                <th><?php echo $poneemptyqty; ?></th>
                <th><?php echo $ptwonewqty; ?></th>
                <th><?php echo $ptworefillqty; ?></th>
                <th><?php echo $ptwoemptyqty; ?></th>
                <th><?php echo $pthreenewqty; ?></th>
                <th><?php echo $pthreerefillqty; ?></th>
                <th><?php echo $pthreeemptyqty; ?></th>
                <th><?php echo $pfournewqty; ?></th>
                <th><?php echo $pfourrefillqty; ?></th>
                <th><?php echo $pfouremptyqty; ?></th>
                <?php 
                foreach($accessoriesarray as $rowaccessoriesarray){ 
                    $accessoriesID=$rowaccessoriesarray->access;
                    
                    $sqlaccessoriesqty="SELECT `newqty` FROM `tbl_invoice_detail` WHERE `status`=1 AND `tbl_product_idtbl_product`='$accessoriesID' AND `tbl_invoice_idtbl_invoice`='$invoiceID'";
                    $resultaccessoriesqty =$conn-> query($sqlaccessoriesqty);   
                    $rowaccessoriesqty = $resultaccessoriesqty-> fetch_assoc();    
                    ?>
                <th nowrap>&nbsp;</th>
                <?php } ?>
                <th class="text-right"><?php echo number_format($totalamount, 2); ?></th>
                <th class="text-right"><?php echo number_format($totalcash, 2); ?></th>
                <th>&nbsp;</th>
                <th class="text-right"><?php echo number_format($totalcheque, 2); ?></th>
                <th class="text-right"><?php echo number_format($totalcredit, 2); ?></th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <?php 
                foreach($accessoriesarray as $rowaccessoriesarray){ 
                    $accessoriesID=$rowaccessoriesarray->access;
                    
                    $sqlaccessoriesqty="SELECT `newqty` FROM `tbl_invoice_detail` WHERE `status`=1 AND `tbl_product_idtbl_product`='$accessoriesID' AND `tbl_invoice_idtbl_invoice`='$invoiceID'";
                    $resultaccessoriesqty =$conn-> query($sqlaccessoriesqty);   
                    $rowaccessoriesqty = $resultaccessoriesqty-> fetch_assoc();    
                    ?>
                <td nowrap>&nbsp;</td>
                <?php } ?>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <?php
            $actuallytotal=0;
            $sqlproductlist="SELECT `idtbl_product`, `product_name`, `newsaleprice`, `refillsaleprice` FROM `tbl_product` WHERE `status`=1 AND `tbl_product_category_idtbl_product_category`=1";
            $resultproductlist =$conn-> query($sqlproductlist);   
            while($rowproductlist = $resultproductlist-> fetch_assoc()){
                $productID=$rowproductlist['idtbl_product'];

                $newqtytotal=0;
                $refillqtytotal=0;
                $trustqtytotal=0;
                $newqty=0;
                $refillqty=0;
                $trustqty=0;
                $sqlproductdetail="SELECT `newqty`, `refillqty`, `trustqty`, `newsaleprice`, `newrefillprice` FROM `tbl_invoice_detail` WHERE `status`=1 AND `tbl_product_idtbl_product`='$productID' AND `tbl_invoice_idtbl_invoice` IN (SELECT `idtbl_invoice` FROM `tbl_invoice` WHERE `date`='$validfrom' AND `status`=1)";
                $resultproductdetail =$conn-> query($sqlproductdetail);
                while($rowproductdetail = $resultproductdetail-> fetch_assoc()){
                    $newprice=$rowproductdetail['newqty']*$rowproductlist['newsaleprice'];
                    $refillprice=$rowproductdetail['refillqty']*$rowproductlist['refillsaleprice'];
                    $trustprice=$rowproductdetail['trustqty']*$rowproductlist['refillsaleprice'];

                    $newqtytotal=$newqtytotal+$newprice;
                    $refillqtytotal=$refillqtytotal+$refillprice;
                    $trustqtytotal=$trustqtytotal+$trustprice;

                    $newqty=$newqty+$rowproductdetail['newqty'];
                    $refillqty=$refillqty+$rowproductdetail['refillqty'];
                    $trustqty=$trustqty+$rowproductdetail['trustqty'];
                    
                }
                $actuallytotal=$actuallytotal+($newqtytotal+$refillqtytotal+$trustqtytotal);
            ?>
            <tr>
                <td nowrap class="text-left">&nbsp;</td>
                <td nowrap class="text-left"><?php echo $rowproductlist['product_name'].' New'; ?></td>
                <td nowrap class="text-right"><?php echo number_format($rowproductlist['newsaleprice'], 2) ?></td>
                <td nowrap>X</td>
                <td nowrap><?php echo $newqty; ?></td>
                <td nowrap class="text-right"><?php echo number_format($newqtytotal,2); ?></td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <?php 
                foreach($accessoriesarray as $rowaccessoriesarray){ 
                    $accessoriesID=$rowaccessoriesarray->access;

                    $sqlaccessoriesqty="SELECT `newqty` FROM `tbl_invoice_detail` WHERE `status`=1 AND `tbl_product_idtbl_product`='$accessoriesID' AND `tbl_invoice_idtbl_invoice`='$invoiceID'";
                    $resultaccessoriesqty =$conn-> query($sqlaccessoriesqty);   
                    $rowaccessoriesqty = $resultaccessoriesqty-> fetch_assoc();    
                ?>
                <td nowrap>&nbsp;</td>
                <?php } ?>
                <td nowrap class="text-right">&nbsp;</td>
                <td nowrap class="text-right">&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap class="text-right">&nbsp;</td>
                <td nowrap class="text-right">&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
            </tr>
            <tr>
                <td nowrap class="text-left">&nbsp;</td>
                <td nowrap class="text-left"><?php echo $rowproductlist['product_name'].' Refill'; ?></td>
                <td nowrap class="text-right"><?php echo number_format($rowproductlist['refillsaleprice'], 2) ?></td>
                <td nowrap>X</td>
                <td nowrap><?php echo $refillqty; ?></td>
                <td nowrap class="text-right"><?php echo number_format($refillqtytotal,2); ?></td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <?php 
                foreach($accessoriesarray as $rowaccessoriesarray){ 
                    $accessoriesID=$rowaccessoriesarray->access;

                    $sqlaccessoriesqty="SELECT `newqty` FROM `tbl_invoice_detail` WHERE `status`=1 AND `tbl_product_idtbl_product`='$accessoriesID' AND `tbl_invoice_idtbl_invoice`='$invoiceID'";
                    $resultaccessoriesqty =$conn-> query($sqlaccessoriesqty);   
                    $rowaccessoriesqty = $resultaccessoriesqty-> fetch_assoc();    
                ?>
                <td nowrap>&nbsp;</td>
                <?php } ?>
                <td nowrap class="text-right">&nbsp;</td>
                <td nowrap class="text-right">&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap class="text-right">&nbsp;</td>
                <td nowrap class="text-right">&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
            </tr>
            <!-- <tr>
                <td nowrap class="text-left">&nbsp;</td>
                <td nowrap class="text-left"><?php echo $rowproductlist['product_name'].' Trust'; ?></td>
                <td nowrap class="text-right"><?php echo number_format($rowproductlist['refillsaleprice'], 2) ?></td>
                <td nowrap>X</td>
                <td nowrap><?php echo $trustqty; ?></td>
                <td nowrap class="text-right"><?php echo number_format($trustqtytotal,2); ?></td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <?php 
                foreach($accessoriesarray as $rowaccessoriesarray){ 
                    $accessoriesID=$rowaccessoriesarray->access;

                    $sqlaccessoriesqty="SELECT `newqty` FROM `tbl_invoice_detail` WHERE `status`=1 AND `tbl_product_idtbl_product`='$accessoriesID' AND `tbl_invoice_idtbl_invoice`='$invoiceID'";
                    $resultaccessoriesqty =$conn-> query($sqlaccessoriesqty);   
                    $rowaccessoriesqty = $resultaccessoriesqty-> fetch_assoc();    
                ?>
                <td nowrap>&nbsp;</td>
                <?php } ?>
                <td nowrap class="text-right">&nbsp;</td>
                <td nowrap class="text-right">&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap class="text-right">&nbsp;</td>
                <td nowrap class="text-right">&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
            </tr> -->
            <?php } ?>
            <tr>
                <td nowrap class="text-left">&nbsp;</td>
                <td nowrap class="text-left">Total</td>
                <td nowrap class="text-right">&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap class="text-right"><?php echo number_format($actuallytotal,2); ?></td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <?php 
                foreach($accessoriesarray as $rowaccessoriesarray){ 
                    $accessoriesID=$rowaccessoriesarray->access;

                    $sqlaccessoriesqty="SELECT `newqty` FROM `tbl_invoice_detail` WHERE `status`=1 AND `tbl_product_idtbl_product`='$accessoriesID' AND `tbl_invoice_idtbl_invoice`='$invoiceID'";
                    $resultaccessoriesqty =$conn-> query($sqlaccessoriesqty);   
                    $rowaccessoriesqty = $resultaccessoriesqty-> fetch_assoc();    
                ?>
                <td nowrap>&nbsp;</td>
                <?php } ?>
                <td nowrap class="text-right">&nbsp;</td>
                <td nowrap class="text-right">&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap class="text-right">&nbsp;</td>
                <td nowrap class="text-right">&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
            </tr>
            <!-- <tr>
                <td nowrap class="text-left">&nbsp;</td>
                <td nowrap class="text-left">Bill Total</td>
                <td nowrap class="text-right">&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap class="text-right"><?php echo number_format(($totalcash+$totalcheque+$totalcredit),2); ?></td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <?php 
                foreach($accessoriesarray as $rowaccessoriesarray){ 
                    $accessoriesID=$rowaccessoriesarray->access;

                    $sqlaccessoriesqty="SELECT `newqty` FROM `tbl_invoice_detail` WHERE `status`=1 AND `tbl_product_idtbl_product`='$accessoriesID' AND `tbl_invoice_idtbl_invoice`='$invoiceID'";
                    $resultaccessoriesqty =$conn-> query($sqlaccessoriesqty);   
                    $rowaccessoriesqty = $resultaccessoriesqty-> fetch_assoc();    
                ?>
                <td nowrap>&nbsp;</td>
                <?php } ?>
                <td nowrap class="text-right">&nbsp;</td>
                <td nowrap class="text-right">&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap class="text-right">&nbsp;</td>
                <td nowrap class="text-right">&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
            </tr> -->
            <tr>
                <td nowrap class="text-left">&nbsp;</td>
                <td nowrap class="text-left">Promotion Discount</td>
                <td nowrap class="text-right">&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap class="text-right">(<?php echo number_format(($actuallytotal-($totalcash+$totalcheque+$totalcredit)),2); ?>)</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <?php 
                foreach($accessoriesarray as $rowaccessoriesarray){ 
                    $accessoriesID=$rowaccessoriesarray->access;

                    $sqlaccessoriesqty="SELECT `newqty` FROM `tbl_invoice_detail` WHERE `status`=1 AND `tbl_product_idtbl_product`='$accessoriesID' AND `tbl_invoice_idtbl_invoice`='$invoiceID'";
                    $resultaccessoriesqty =$conn-> query($sqlaccessoriesqty);   
                    $rowaccessoriesqty = $resultaccessoriesqty-> fetch_assoc();    
                ?>
                <td nowrap>&nbsp;</td>
                <?php } ?>
                <td nowrap class="text-right">&nbsp;</td>
                <td nowrap class="text-right">&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap class="text-right">&nbsp;</td>
                <td nowrap class="text-right">&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
            </tr>
            <tr>
                <td nowrap class="text-left">&nbsp;</td>
                <th nowrap class="text-left">Total</th>
                <td nowrap class="text-right">&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <th nowrap class="text-right"><?php echo number_format(($totalcash+$totalcheque+$totalcredit),2); ?></th>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <?php 
                foreach($accessoriesarray as $rowaccessoriesarray){ 
                    $accessoriesID=$rowaccessoriesarray->access;

                    $sqlaccessoriesqty="SELECT `newqty` FROM `tbl_invoice_detail` WHERE `status`=1 AND `tbl_product_idtbl_product`='$accessoriesID' AND `tbl_invoice_idtbl_invoice`='$invoiceID'";
                    $resultaccessoriesqty =$conn-> query($sqlaccessoriesqty);   
                    $rowaccessoriesqty = $resultaccessoriesqty-> fetch_assoc();    
                ?>
                <td nowrap>&nbsp;</td>
                <?php } ?>
                <td nowrap class="text-right">&nbsp;</td>
                <td nowrap class="text-right">&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap class="text-right">&nbsp;</td>
                <td nowrap class="text-right">&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td nowrap>&nbsp;</td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } ?>