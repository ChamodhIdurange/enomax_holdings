<?php
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT `d`.`idtbl_return_details`, `p`.`idtbl_product`, `p`.`product_name`, `d`.`unitprice`, `d`.`qty`, `d`.`discount`, `d`.`total` FROM `tbl_return` as `r` join `tbl_return_details` as `d` ON (`r`.`idtbl_return` = `d`.`tbl_return_idtbl_return`) JOIN `tbl_product` as `p` ON (`d`.`tbl_product_idtbl_product` = `p`.`idtbl_product`) WHERE `d`.`tbl_return_idtbl_return` = '$record'";
$result=$conn->query($sql);


?>

<div class="row">
    <input type="hidden" id = "hiddenID" name = "hiddenID" value="<?php echo $record ?>">
    <table id="returndetailstable" class="table table-striped table-bordered table-sm">
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th class="text-center">Purcahsed Quantity</th>
                <th class="text-center">Actual Quantity</th>
                <th class="text-center">Actions</th>

            </tr>
        </thead>
        <tbody>
            <?php while($row=$result->fetch_assoc()){ ?>
            <tr>
                <td><?php echo $row['idtbl_return_details'] ?></td>
                <td><?php echo $row['product_name'] ?></td>
                <td class="text-center"><?php echo $row['qty'] ?></td>
                <td id="qty<?php echo $row['qty'] ?>" class="text-center editnewqty"><?php echo $row['qty'] ?></td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input testradio" value="1" type="radio"
                            name="radio<?php echo $row['idtbl_return_details']?>"
                            id="correct<?php echo $row['idtbl_return_details']?>" checked>
                        <label class="form-check-label" for="flexRadioDefault1">
                            Quantity Correct
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input testradio" value="0" type="radio"
                            name="radio<?php echo $row['idtbl_return_details']?>"
                            id="wrong<?php echo $row['idtbl_return_details']?>">
                        <label class="form-check-label" for="flexRadioDefault2">
                            Quantity Wrong
                        </label>
                    </div>
                </td><td class = "d-none"><?php echo $row['idtbl_product'] ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <div class="form-group mt-2">
        <button type="button" id="btnAddQty" class="btn btn-outline-primary btn-sm fa-pull-right"><i class="fas fa-save"></i>&nbsp;Create Return</button>
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

    $("#returndetailstable tr .testradio").on('change', function (e) {
        var row = $(this);
        $(this).closest("tr").find('td:eq(3)').text('');
        if ($(this).val() == 1) {
            var val = $(this).closest("tr").find('td:eq(2)').html();
            $(this).closest("tr").find('td:eq(3)').text(val);
        } else {
            var textbox = $('<input class = "actutalqty" type="text" id="foo" name="foo">');
            $(this).closest("tr").find('td:eq(3)').append(textbox);;
            textremove('.actutalqty', row);
        }

    });

    function textremove(classname, row) {
        $('#returndetailstable tbody').on('keyup', classname, function (e) {
            if (e.keyCode === 13) {
                var val = $(this).val();
                $(this).closest("tr").find('td:eq(3)').text(val);
            }
        });
    }

    $('#btnAddQty').click(function () { //alert('IN');
        var tbody = $("#returndetailstable tbody");

        if (tbody.children().length > 0) {
            jsonObj = [];
            $("#returndetailstable tbody tr").each(function () {
                item = {}
                $(this).find('td').each(function (col_idx) {
                    item["col_" + (col_idx + 1)] = $(this).text();
                });
                jsonObj.push(item);
            });
            // console.log(jsonObj);
            // alert(jsonObj);
            var hiddenID = $('#hiddenID').val();

            $.ajax({
                type: "POST",
                data: {
                    tableData: jsonObj,
                    recordID: hiddenID
                },
                url: 'process/qtyCheckProcess.php',
                success: function (result) { 
                    //alert(result);

                    location.reload();
                }
            });
        }
    });
</script>