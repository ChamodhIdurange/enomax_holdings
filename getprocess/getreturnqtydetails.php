<?php
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql = "SELECT `d`.`actualqty`, `d`.`idtbl_return_details`,`p`.`product_name`, `d`.`unitprice`, `d`.`qty`, `d`.`discount`, `d`.`total`, `d`.`tbl_invoice_idtblinvoice` FROM `tbl_return` as `r` join `tbl_return_details` as `d` ON (`r`.`idtbl_return` = `d`.`tbl_return_idtbl_return`) JOIN `tbl_product` as `p` ON (`d`.`tbl_product_idtbl_product` = `p`.`idtbl_product`) WHERE `d`.`tbl_return_idtbl_return` = '$record'";
$result = $conn->query($sql);

$sqlReturn = "SELECT `idtbl_return`, `tbl_customer`.`customer` AS `cusname`, `tbl_employee`.`name`, `tbl_return`.`returndate` FROM `tbl_return` LEFT JOIN `tbl_customer` ON (`tbl_customer`.`idtbl_customer` = `tbl_return`.`tbl_customer_idtbl_customer`) LEFT JOIN `tbl_employee` ON (`tbl_employee`.`idtbl_employee` = `tbl_return`.`tbl_employee_idtbl_employee`) WHERE `idtbl_return` = '$record'";
$resultReturn = $conn->query($sqlReturn);
$rowReturn = $resultReturn->fetch_assoc();
$cusname =  $rowReturn['cusname'];
$name =  $rowReturn['name'];
$idtbl_return =  $rowReturn['idtbl_return'];
$returndate =  $rowReturn['returndate'];

$sumqty = 0;
$total = 0;
$sumtotal = 0;
$nettotal = 0;
$disamount = 0;
$totaldiscount = 0;

?>

<div class="row">
    <div class="col-12 small">
        <table class="table table-borderless table-sm text-center w-100 tableprint">
            <tbody>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <h3 class="font-weight-light m-0">ENOMAX Holdings (PVT) LTD</h3>
                        <h4 class="mt-2">Credit Note</h4>
                    </td>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-4">CTN/0<?php echo $idtbl_return ?> <br> ASM : <?php echo $name ?></div>
    <div class="col-3"></div>
    <div class="col-5 text-right">Return Date : <?php echo $returndate ?> <br> Customer Name : <?php echo $cusname ?></div>
</div>
<hr class="border-dark">
<div class="row">
    <div class="col-12 small">
        <table id="returndetailstable" class="table table-striped table-bordered table-sm">
            <thead>
                <tr>
                    
                    <th>INV No</th>
                    <th>Product</th>
                    <th class="text-right">Sale Price</th>
                    <th class="text-center">Qty</th>
                    <th class="text-center">Discount Amount</th>
                    <th class="text-center">Total</th>

                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) {
                    $sumqty += $row['qty'];
                    $total = $row['unitprice'] * $row['qty'];
                    $disamount = $total * $row['discount'] / 100;
                    $totaldiscount += $disamount;
                    $sumtotal += $total;
                    $nettotal = $sumtotal - $totaldiscount;
                ?>
                    <tr>
                        <td class="d-none"><?php echo $row['idtbl_return_details'] ?></td>
                        <td><?php echo $row['tbl_invoice_idtblinvoice'] ?></td>
                        <td><?php echo $row['product_name'] ?></td>
                        <td class="text-right">Rs.<?php echo number_format($row['unitprice'], 2); ?></td>
                        <td class="text-center"><?php echo $row['qty'] ?></td>
                        <td class="text-center">Rs.<?php echo number_format($disamount, 2) ?></td>
                        <td class="text-right">Rs.<?php echo number_format($total, 2); ?></td>
                    </tr>
                <?php } ?>
                
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-9 text-right">
        <h5 class="font-weight-600">Subtotal</h5>
    </div>
    <div class="col-3 text-right">
        <h5 class="font-weight-600" id="divtotal">Rs.<?php echo number_format($sumtotal, 2); ?></h5>
    </div>
    <div class="col-9 text-right">
        <h5 class="font-weight-600">Discount</h5>
    </div>
    <div class="col-3 text-right">
        <h5 class="font-weight-600" id="discountedprice">Rs. <?php echo number_format($totaldiscount, 2); ?></h5>
    </div>
    <div class="col-9 text-right">
        <h1 class="font-weight-600">Nettotal</h1>
    </div>
    <div class="col-3 text-right">
        <h1 class="font-weight-600" id="divtotalview">Rs. <?php echo number_format($nettotal, 2); ?></h1>
    </div>
</div>
<script>
    $('#qty').change(function () {
        // alert("asd")
        var qty = $("#qty").val();
        var unitprice = $("#unitprice").val();

        var sum = qty * unitprice;
        $("#total").val(sum);
    });
    $('#returndetailstable tbody').on('click', '.btnEdit', function () {
        var r = confirm("Are you sure, You want to Edit this ? ");
        if (r == true) {
            var id = $(this).attr('id');
            $.ajax({
                type: "POST",
                data: {
                    recordID: id
                },
                url: 'getprocess/getspecificreturndetails.php',
                success: function (result) {
                    //alert(result);
                    var obj = JSON.parse(result);
                    $('#hiddenid').val(obj.id);
                    $('#unitprice').val(obj.unitprice);
                    $('#qty').val(obj.qty);
                    $('#hiddendiscount').val(obj.discount);
                    $('#discount').val(obj.discount);
                    $('#total').val(obj.total);
                    $('#hiddentotal').val(obj.total);
                    $('#mainID').val(obj.mainID);


                }
            });
        }
    });
</script>