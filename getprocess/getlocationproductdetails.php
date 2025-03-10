<?php
require_once('../connection/db.php');

$record=$_POST['recordID'];


$sqldetails="SELECT `p`.`product_name`, `r`.`minimum_quantity`, `r`.`status`, `r`.`idtbl_location_reorder` FROM `tbl_location_reorder` as `r` JOIN `tbl_product` as `p` ON (`p`.`idtbl_product` = `r`.`tbl_product_idtbl_product`) JOIN `tbl_locations` as `l` ON (`l`.`idtbl_locations` = `r`.`tbl_locations_idtbl_locations`) WHERE `r`.`status` = '1' AND `r`.`tbl_locations_idtbl_locations` = '$record'";
$resultdetails =$conn-> query($sqldetails);
?>

<div class="row">
    <P></P>
    <div class="col-md-12">
        <table class="table table-bordered table-striped table-sm nowrap" id="tablequotation">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Minimum Quantity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if($resultdetails->num_rows > 0) {while ($row = $resultdetails-> fetch_assoc()) {?>
                <tr>
                    <td><?php echo $row['idtbl_location_reorder'] ?></td>
                    <td><?php echo $row['product_name'] ?></td>
                    <td><?php echo $row['minimum_quantity'] ?></td>
                    <td class="text-right">
                        <a href="process/statusreorderlocation.php?record=<?php echo $row['idtbl_location_reorder'] ?>&type=3"
                            onclick="return confirm('Are you sure you want to remove this?');" target="_self"
                            class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i></a>

                    </td>

                </tr>

                <?php }} ?>

            </tbody>
        </table>

    </div>
</div>


<script>
    $(document).ready(function () {
        $("#qty, #discount").keyup(qcalculate);
    });



    $('#createquotation').click(function () {
        var tbody = $("#tablequotation tbody");
        var inqueryID = $('#inqueryID ').val();

        if (tbody.children().length > 0) {
            jsonObj = [];
            $("#tablequotation tbody tr").each(function () {
                item = {}
                $(this).find('td').each(function (col_idx) {
                    item["col_" + (col_idx + 1)] = $(this).text();
                });
                jsonObj.push(item);
            });


            var qdeliverydate = $('#qdeliverydate').val();
            var qorderdate = $('#qorderdate').val();
            var recordid = $('#recordid').val();
            var tot = $('#showtot2').text();

            console.log(jsonObj)

            $.ajax({
                type: "POST",
                data: {
                    qtableData: jsonObj,
                    orderdate: qorderdate,
                    deliverydate: qdeliverydate,
                    recordID: recordid,
                    tot: tot,
                    inqueryID: inqueryID
                },
                url: 'process/quotationprocess.php',
                success: function (result) { //alert(result)
                    location.reload();
                }
            });
        }
    });
</script>