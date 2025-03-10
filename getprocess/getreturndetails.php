<?php
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT `d`.`actualqty`, `d`.`idtbl_return_details`,`p`.`product_name`, `d`.`unitprice`, `d`.`qty`, `d`.`discount`, `d`.`total` FROM `tbl_return` as `r` join `tbl_return_details` as `d` ON (`r`.`idtbl_return` = `d`.`tbl_return_idtbl_return`) JOIN `tbl_product` as `p` ON (`d`.`tbl_product_idtbl_product` = `p`.`idtbl_product`) WHERE `d`.`tbl_return_idtbl_return` = '$record'";
$result=$conn->query($sql);

$sqlReturn = "SELECT `recieved_status`, `returntype` FROM `tbl_return` WHERE `idtbl_return` = '$record'";
$resultReturn=$conn->query($sqlReturn);
$rowReturn = $resultReturn-> fetch_assoc();
$recievedStatus =  $rowReturn['recieved_status'];
$type =  $rowReturn['returntype'];

if($recievedStatus == 0){
?>


<div class="row">
    <div class="col-md-12">
        <form action="process/updatereturns.php" method="post" autocomplete="off">

            <div class="row">

                <div class="col-md-6">

                    <div class="form-group mb-1">
                        <label class="small font-weight-bold text-dark">Quantity</label>
                        <input id="qty" type="text" name="qty" class="form-control form-control-sm" placeholder="">
                    </div>
                </div>
                <div class="col-md-6 d-none">

                    <div class="form-group mb-1">
                        <label class="small font-weight-bold text-dark">
                            Discount(%)</label>
                        <input id="discount" type="number" name="discount" class="form-control form-control-sm"
                            placeholder="">
                    </div>
                </div>
                <div class="col-md-6">

                    <div class="form-group mb-1">
                        <label class="small font-weight-bold text-dark">Total</label>
                        <input id="total" type="number" name="total" class="form-control form-control-sm" placeholder=""
                            readonly>
                    </div>
                </div>
            </div>
            <input type="hidden" name="unitprice" id="unitprice">
            <input type="hidden" name="hiddenid" id="hiddenid">
            <input type="hidden" name="recordOption" id="recordOption" value="2">
            <input type="hidden" name="hiddentotal" id="hiddentotal" value="">
            <input type="hidden" name="hiddendiscount" id="hiddendiscount" value="">
            <input type="hidden" name="mainID" id="mainID" value="">
            <input type="hidden" name="returntype" id="returntype" value="<?php echo $type ?>">
            <div class="form-group mt-3">
                <button type="submit" id="submitBtn" class="btn btn-outline-primary btn-sm w-50 fa-pull-right"><i
                        class="far fa-save"></i>&nbsp;Update</button>
            </div>
            <br>
            <br>
        </form>
    </div>
</div>
<?php }?>

<div class="row">
    <table id="returndetailstable" class="table table-striped table-bordered table-sm">
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th class="text-right">Sale Price</th>
                <th class="text-center">Qty</th>
                <th class="text-center">Actual Qty</th>
                <th class="text-center">Discount</th>
                <th class="text-center">Total</th>
                <?php if($recievedStatus == 0){ ?> <th class="text-center">Actions</th>
                <?php }?>
            </tr>
        </thead>
        <tbody>
            <?php while($row=$result->fetch_assoc()){ ?>
            <tr>
                <td><?php echo $row['idtbl_return_details'] ?></td>
                <td class="d-none"></td>
                <td><?php echo $row['product_name'] ?></td>
                <td class="text-right">Rs.<?php echo number_format($row['unitprice'], 2) ?></td>
                <td class="text-center"><?php echo $row['qty'] ?></td>
                <?php if($row['actualqty'] == -2 ){?>
                <td class="text-center">Not Defined</td>
                <?php }else{?>
                <td class="text-center"><?php echo $row['actualqty'] ?></td>
                <?php } ?>
                <td class="text-center"><?php echo $row['discount'] ?>%</td>
                <td class="text-right">Rs.<?php echo number_format($row['total'], 2) ?></td>
                <?php if($recievedStatus == 0){ ?><td class="text-center"><button
                        class="btn btn-outline-primary btn-sm btnEdit"
                        id="<?php echo $row['idtbl_return_details'] ?>"><i class="fa fa-pen"></i></button></td><?php }?>
            </tr>
            <?php } ?>
        </tbody>
    </table>
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