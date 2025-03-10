<?php
session_start();
require_once('../connection/db.php');
$today=date('Y-m-d');
$recordID=$_POST['recordID'];

$fulltot = 0;
$discount = 0;
$totaloutstanding = 0;
$totalpayment = 0;

$sqlinvoiceinfo="SELECT `tbl_invoice`.`idtbl_invoice`, `tbl_invoice`.`date`, `tbl_invoice`.`total`, `tbl_invoice`.`paymentcomplete`, `tbl_customer`.`name`, `tbl_customer`.`address`, `tbl_employee`.`name` AS `saleref`, `tbl_area`.`area`, `tbl_user`.`name` as `username`, `tbl_invoice`.`tbl_customer_idtbl_customer`  FROM `tbl_invoice` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_invoice`.`tbl_customer_idtbl_customer` LEFT JOIN `tbl_employee` ON `tbl_employee`.`idtbl_employee`=`tbl_invoice`.`ref_id` LEFT JOIN `tbl_area` ON `tbl_area`.`idtbl_area`=`tbl_invoice`.`tbl_area_idtbl_area` LEFT JOIN `tbl_user` ON `tbl_user`.`idtbl_user`=`tbl_invoice`.`tbl_user_idtbl_user`WHERE `tbl_invoice`.`status`=1 AND `tbl_invoice`.`idtbl_invoice`='$recordID'";
$resultinvoiceinfo =$conn-> query($sqlinvoiceinfo); 
$rowinvoiceinfo = $resultinvoiceinfo-> fetch_assoc();

$customerID = $rowinvoiceinfo['tbl_customer_idtbl_customer'];

// $sqlinvoiceoutstanding="SELECT `tbl_invoice`.`idtbl_invoice`, `tbl_invoice`.`date`, `tbl_invoice`.`total`, `tbl_invoice`.`paymentcomplete`, `tbl_customer`.`name`, `tbl_customer`.`address`, `tbl_employee`.`name` AS `saleref`, `tbl_area`.`area`, `tbl_user`.`name` as `username`  FROM `tbl_invoice` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_invoice`.`tbl_customer_idtbl_customer` LEFT JOIN `tbl_employee` ON `tbl_employee`.`idtbl_employee`=`tbl_invoice`.`ref_id` LEFT JOIN `tbl_area` ON `tbl_area`.`idtbl_area`=`tbl_invoice`.`tbl_area_idtbl_area` LEFT JOIN `tbl_user` ON `tbl_user`.`idtbl_user`=`tbl_invoice`.`tbl_user_idtbl_user`WHERE `tbl_invoice`.`status`=1 AND  `tbl_invoice`.`payment_created`=0 AND `tbl_invoice`.`tbl_customer_idtbl_customer`='$customerID'";
// $resultinvoiceoutstanding =$conn-> query($sqlinvoiceoutstanding); 

$sqlinvoicedetail="SELECT `tbl_product`.`product_name`, `tbl_invoice_detail`.`qty`, `tbl_invoice_detail`.`freeqty`, `tbl_invoice_detail`.`saleprice` FROM `tbl_invoice_detail` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_invoice_detail`.`tbl_product_idtbl_product` WHERE `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`='$recordID' AND `tbl_invoice_detail`.`status`=1";
$resultinvoicedetail=$conn->query($sqlinvoicedetail);

$sqlinvoiceoutstanding="SELECT `tbl_invoice`.`idtbl_invoice`, `tbl_invoice`.`total`, `tbl_product`.`product_name`, `tbl_invoice_detail`.`qty`, `tbl_invoice_detail`.`freeqty`, `tbl_invoice_detail`.`saleprice` FROM `tbl_invoice_detail` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_invoice_detail`.`tbl_product_idtbl_product` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` WHERE `tbl_invoice_detail`.`status`=1 AND `tbl_invoice`.`tbl_customer_idtbl_customer`='$customerID' AND  `tbl_invoice`.`idtbl_invoice` != '$recordID' AND `tbl_invoice`.`status` = '1'  GROUP BY `tbl_invoice`.`idtbl_invoice`";
$resultinvoiceoutstanding=$conn->query($sqlinvoiceoutstanding);

$sqlinvoicedetailfree="SELECT `tbl_product`.`product_name`, `tbl_invoice_detail`.`freeqty` FROM `tbl_invoice_detail` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_invoice_detail`.`freeproductid` WHERE `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`='$recordID' AND `tbl_invoice_detail`.`status`=1 AND `tbl_invoice_detail`.`freeqty`>0";
$resultinvoicedetailfree=$conn->query($sqlinvoicedetailfree);

?>
<div class="row">
    <div class="col-12">
        <table class="w-100 tableprint">
            <tbody>
                <tr>
                    <td>&nbsp;</td>
                    <td class="text-right"><img src="images/logo.png" width="80" height="80" class="img-fluid"></td>
                    <td colspan="4" class="text-center small align-middle">
                        <h2 class="font-weight-light m-0">ENOMAX Holdings (PVT) LTD</h2>
                        No.46, Garden City<br>
                        Minuwangoda Road, Ja-ela.<br>
                        Tel: 011-3468568<br>
                    </td>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-12 text-center mt-3">
        <h5>CREDIT INVOICE</h5>
    </div>
</div>
<div class="row">
    <table width="100%" class="tableprint">
        <tr>
            <td>
                <div class=" mt-3">
                    <div class="col-12">Bill to: <?php echo $rowinvoiceinfo['name'] ?></div>
                    <div class="col-12">Address: <?php echo $rowinvoiceinfo['address'] ?></div>
                </div>
            </td>
            <td>
                <div class="text-right">Date: <?php echo $today ?></div>
                <div class="text-right">Invoice No: INV_<?php echo $rowinvoiceinfo['idtbl_invoice'] ?></div>
                <div class="text-right">Salesmen: <?php echo $rowinvoiceinfo['saleref'] ?></div>
                <div class="text-right">User: <?php echo $rowinvoiceinfo['username'] ?></div>
            </td>
        </tr>

    </table>
</div>

<div class="row mt-3">
    <table width="100%" class="tableprint">
        <tr>
            <td class = "p-1">
                <div>
                    <table
                        class="table table-striped table-bordered table-black bg-transparent table-sm w-100 tableprint text-center">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th class="text-right">Sale Price</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                    while($rowinvoicedetail=$resultinvoicedetail->fetch_assoc()){
                        $totnew=$rowinvoicedetail['qty']*$rowinvoicedetail['saleprice'];
                        $total=number_format(($totnew), 2);
                        $fulltot = $fulltot + $totnew;
                ?>
                            <tr>
                                <td><?php echo $rowinvoicedetail['product_name']; ?></td>
                                <td class="text-center"><?php echo $rowinvoicedetail['qty']; ?></td>
                                <td class="text-right"><?php echo number_format($rowinvoicedetail['saleprice'],2); ?>
                                </td>
                                <td class="text-right"><?php echo $total; ?></td>
                            </tr>
                            <?php }
                $discount = $fulltot - $rowinvoiceinfo['total'];
                $newbilltotal = $rowinvoiceinfo['total']; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-left">Sub Total</th>
                                <th class="text-right">Rs.<?php echo number_format($fulltot, 2)  ?></th>
                            </tr>
                            <tr>
                                <th colspan="3" class="text-left">Discount</th>
                                <th class="text-right">Rs.<?php echo number_format($discount, 2) ?></th>
                            </tr>
                            <tr>
                                <th colspan="3" class="text-left">Net Total</th>
                                <th class="text-right">Rs.<?php echo number_format($rowinvoiceinfo['total'], 2) ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </td>
            <td style = "vertical-align: top" class = "p-1">
                <div>
                    <?php if($resultinvoiceoutstanding->num_rows > 0){ ?>
                    <table
                        class="table table-striped table-bordered table-black bg-transparent table-sm w-100 tableprint text-center">
                        <thead>
                            <tr>
                                <th>Invoice ID</th>
                                <th>Outstanding amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                while($rowinvoiceoutstanding=$resultinvoiceoutstanding->fetch_assoc()){
                                    $totaloutstanding= $rowinvoiceoutstanding['total'];
                                    $totalpayment = $totaloutstanding + $newbilltotal;
                                    ?>
                            <tr>
                                <td>INV_<?php echo $rowinvoiceoutstanding['idtbl_invoice']; ?></td>
                                <td class="text-right"><?php echo number_format($rowinvoiceoutstanding['total'],2); ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="text-left">Outstanding amount</th>
                                <th class="text-right"><?php echo number_format($totaloutstanding, 2) ?></th>
                            </tr>
                            <tr>
                                <th class="text-left">New Amount</th>
                                <th class="text-right"><?php echo number_format($newbilltotal, 2) ?></th>
                            </tr>
                            <tr>
                                <th class="text-left">Total payment</th>
                                <th class="text-right text-danger">Rs.<?php echo number_format($totalpayment, 2) ?></th>
                            </tr>


        </tr>
        </tfoot>
    </table>
    <?php }else{ ?>
    <h4 class="text-danger mt-5">No outstanding payments available</h4>
    <?php }?>
</div>
</td>
</tr>
</table>



</div>
<div class="row mt-3">
    <div class="col-6">
        <h4 class="small">Free Issue Information</h4>
        <table
            class="table table-striped table-bordered table-black bg-transparent table-sm w-100 tableprint text-center">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    while($rowinvoicedetailfree=$resultinvoicedetailfree->fetch_assoc()){
                ?>
                <tr>
                    <td><?php echo $rowinvoicedetailfree['product_name']; ?></td>
                    <td class="text-center"><?php echo $rowinvoicedetailfree['freeqty']; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="col-6"></div>
</div>
<div class="row mt-3">
    <div class="col-12">
        <h6>Payment Mode</h6>
        <table class="table table-striped table-bordered table-black bg-transparent table-sm w-100 tableprint border-0">
            <thead>
                <tr>
                    <th>Cash</th>
                    <th>&nbsp;</th>
                    <th>Credit</th>
                    <th>&nbsp;</th>
                    <th>Cheque</th>
                    <th>&nbsp;</th>
                </tr>
                <tr>
                    <th class="border-0">&nbsp;</th>
                    <th class="border-0">&nbsp;</th>
                    <th class="border-0">&nbsp;</th>
                    <th class="border-0">&nbsp;</th>
                    <th>No</th>
                    <th>&nbsp;</th>
                </tr>
                <tr>
                    <th class="border-0">&nbsp;</th>
                    <th class="border-0">&nbsp;</th>
                    <th class="border-0">&nbsp;</th>
                    <th class="border-0">&nbsp;</th>
                    <th>Bank</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<div class="row">

    <table width="100%" class="tableprint">
        <tr>
            <td class = "p-1" width="30%">
                <div class="mt-2 border border-dark p-1">
                    <table class="tableprint">
                        <tr>
                            <th width="30%">CUSTOMER</th>
                            <th width="70%">:...................................</th>
                        </tr>
                        <tr>
                            <th width="30%">RECIPIENT</th>
                            <th width="70%">:...................................</th>
                        </tr>
                        <tr>
                            <th width="30%">NIC</th>
                            <th width="70%">:...................................</th>
                        </tr>
                        <tr>
                            <th width="30%">VEHICLE NO</th>
                            <th width="70%">:...................................</th>
                        </tr>
                        <tr>
                            <th width="30%">SIGNATURE</th>
                            <th width="70%">:...................................</th>
                        </tr>

                    </table>
                </div>
            </td>
            <td class = "p-1" width="70%">
                <span>Note: Goods received in good condition and correct quantity, No return and exchange. payments on
                    delivery.</span>
                <div class="row">
                    <div class="col-4 text-center">.................................................<br>PREPARED BY
                    </div>
                    <div class="col-4 text-center">.................................................<br>RECEIVED BY
                    </div>
                    <div class="col-4 text-center">.................................................<br>CHECKED BY</div>
                </div>

            </td>
        </tr>
    </table>

    <!-- <div class = "row">
            <div class = "col-md-4">
            <h6>CUSTOMER </h6>
            </div>
            <div class = "col-md-6 text-right">
            <h6>:................................................</h6>
            </div>
        </div>
        <div class = "row">
            <div class = "col-md-4">
            <h6>RECIPIENT </h6>
            </div>
            <div class = "col-md-6 text-right">
            <h6>:................................................</h6>
            </div>
        </div>
        <div class = "row">
            <div class = "col-md-4">
            <h6>NIC </h6>
            </div>
            <div class = "col-md-6 text-right">
            <h6>:................................................</h6>
            </div>
        </div>
        <div class = "row">
            <div class = "col-md-4">
            <h6>VEHICLE NO </h6>
            </div>
            <div class = "col-md-6 text-right">
            <h6>:................................................</h6>
            </div>
        </div>
        <div class = "row">
            <div class = "col-md-4">
            <h6>SIGNATURE </h6>
            </div>
            <div class = "col-md-6 text-right">
            <h6>:................................................</h6>
            </div>
        </div> -->
    <!-- <

    </div>
    <div class = "col-md-8 ">
    <span>Note: Goods received in good condition and correct quantity, No return and exchange. payments on delivery.</span>
    <div class="row mt-4">
    <div class="col-4 text-center">.................................................<br>PREPARED BY</div>
    <div class="col-4 text-center">.................................................<br>RECEIVED BY</div>
    <div class="col-4 text-center">.................................................<br>CHECKED BY</div>
</div>
    </div>
</div>