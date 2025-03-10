<?php
require_once('../connection/db.php');
$sqllocation = "SELECT * FROM `tbl_locations` WHERE `status` IN (1,2)";
$resultlocation = $conn->query($sqllocation);


$sqlreorderlevel = "SELECT `p`.`idtbl_product`, `p`.`product_name`, `p`.`saleprice`, `p`.`unitprice`, `s`.`qty`, `r`.`minimum_quantity` FROM `tbl_locations` AS `l` JOIN `tbl_location_reorder` AS `r` ON (`l`.`idtbl_locations` = `r`.`tbl_locations_idtbl_locations`) JOIN `tbl_product` AS `p` ON (`p`.`idtbl_product` = `r`.`tbl_product_idtbl_product`) JOIN `tbl_stock` AS `s` ON (`s`.`tbl_product_idtbl_product` = `p`.`idtbl_product`) WHERE `l`.`status` IN (1,2) AND `tbl_locations_idtbl_locations` = '8' AND `r`.`minimum_quantity` >= `s`.`qty` AND `r`.`status` = '1'";
$resultreorderlevel = $conn->query($sqlreorderlevel)
?>

<div class="row">
    <!-- <div class=col-md-12 mb-2>
        <div class="col-md-3 form-group ">
            <label class="small font-weight-bold text-dark float-left">Location*</label>
            <select class="form-control form-control-sm" name="reorderlocation" id="reorderlocation" required>
                <option value="">Select</option>
                <?php if($resultlocation->num_rows > 0) {while ($rowlocation = $resultlocation-> fetch_assoc()) { ?>
                <option value="<?php echo $rowlocation['idtbl_location'] ?>">
                    <?php echo $rowlocation['locationname'] ?></option>
                <?php }} ?>
            </select>
        </div>

    </div> -->
    <div class="col-md-12" id="locationproductdiv">
        <table class="table table-striped table-bordered table-sm" id="poreorderdetailstable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Available stock</th>
                    <th>Reorder level</th>
                    <th>GRN Price</th>
                    <th>Qty</th>
                    <th class="d-none">Sale Price</th>

                </tr>
            </thead>
            <tbody>
            <tbody>
                <?php if($resultreorderlevel->num_rows > 0) {while ($row = $resultreorderlevel-> fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['idtbl_product'] ?></td>
                    <td><?php echo $row['product_name'] ?></td>
                    <td><?php echo $row['qty'] ?></td>
                    <td><?php echo $row['minimum_quantity'] ?></td>
                    <td><?php echo $row['unitprice'] ?></td>
                    <td><input id="newqty" type="text" name="newqty" class="form-control form-control-sm"
                            placeholder=""></td>
                    <td class="d-none"><?php echo $row['saleprice'] ?></td>

                </tr>
                <?php }} ?>
            </tbody>
            </tbody>
        </table>
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right px-5" id="btnaddtopo"><i
                    class="far fa-save"></i>&nbsp;Add to PO</button>
        </div>
    </div>
    <!-- <div class="col-md-6">
        <table class="table table-striped table-bordered table-sm" id="grndetailsstable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Location</th>
                    <th class="d-none">Location id</th>

                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div> -->

</div>

<script>
    // $('#reorderlocation').change(function () {
    //     var locationID = $(this).val()
    //     $.ajax({
    //         type: "POST",
    //         data: {
    //             locationID: locationID
    //         },
    //         url: 'getprocess/getreorderdataacopo.php',
    //         success: function (result) {
    //             // alert(result);
    //             $('#locationproductdiv').html(result)
    //         }
    //     });
    // })

    $('#btnaddtopo').click(function () {

        $("#poreorderdetailstable tbody tr").each(function () {
            var val = $(this).find("td:eq(5) input[type='text']").val()
            $(this).find('td:eq(5)').html(val).data('editing', false);

            var productid = $(this).find("td:eq(0)").html()
            var productname = $(this).find("td:eq(1)").html()
            var unitprice = $(this).find("td:eq(4)").html()
            var saleprice = $(this).find("td:eq(6)").html()
            var qty = $(this).find("td:eq(5)").text()

            console.log(productname)
            var total = parseFloat(unitprice * qty);
            var showtotal = addCommas(parseFloat(total).toFixed(2));

            $('#tableorder > tbody:last').append('<tr class="pointer"><td>' + productname +
                '</td><td class="d-none">' + productid + '</td><td class="d-none">' +
                unitprice + '</td><td class="d-none">' + saleprice +
                '</td><td class="text-center">' + qty + '</td><td class="total d-none">' +
                total + '</td><td class="text-right">' + showtotal + '</td></tr>');


        });

        $('#reordermodal').modal('hide');
        $('#modalcreateorder').modal('show')
        calsum()



    })

    function calsum() {
        var sum = 0;
        $(".total").each(function () {
            sum += parseFloat($(this).text());
        });

        var showsum = addCommas(parseFloat(sum).toFixed(2));

        $('#divtotal').html('Rs. ' + showsum);
        $('#hidetotalorder').val(sum);
        $('#product').focus();
    }
</script>