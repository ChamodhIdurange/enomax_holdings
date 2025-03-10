<?php 
require_once('../connection/db.php');

$sqlemployee2="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `status` IN (1,2) AND `tbl_user_type_idtbl_user_type` IN (8,9)";
$resultemployee2 =$conn-> query($sqlemployee2); 

$sqlnotassign="SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE `status` IN (1,2) AND `ref` = '0' ORDER BY `name` asc";
$resultnotassign =$conn-> query($sqlnotassign); 
?>
<div class="row">
    <div class="col-12">
        <div class="row">
            <form id="assignform">
                <div class="form-group mb-1 ml-3 mb-2">
                    <label class="small font-weight-bold text-dark">Ref</label>
                    <select name="modalref" id="modalref" class="form-control form-control-sm" required>
                        <option value="">Select</option>
                        <?php if($resultemployee2->num_rows > 0) {while ($rowemployee = $resultemployee2-> fetch_assoc()) { ?>
                        <option value="<?php echo $rowemployee['idtbl_employee'] ?>">
                            <?php echo $rowemployee['name'] ?></option>
                        <?php }} ?>
                    </select>
                </div>
                <button type="submit" class="d-none" id="hiddensubmit">submit</button>
            </form>

        </div>
        <div class="row">
            <div class="col-md-6">
                <table class="table table-striped table-bordered table-sm col-md-12" id="tableassignref">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $resultnotassign-> fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['idtbl_customer'] ?></td>
                            <td><?php echo $row['name'] ?></td>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input selectcheckbox" type="checkbox" value="1" id="">
                                </div>
                            </td>
                        </tr>

                        <?php } ?>

                    </tbody>
                </table>
                <div class="row">
                    <div class="col">
                        <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right" id="addButton"><i
                                class="fas fa-plus"></i>&nbsp;Assign Customers</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <table class="table table-striped table-bordered table-sm" id="tableassigncomplete">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Ref</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <div class="row">
                    <div class="col">
                        <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right"
                            id="customerassignref"><i class="fas fa-check"></i>&nbsp;Add</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        // $('#tableassignref').DataTable();
    })
    $("#addButton").click(function () {
        if (!$("#assignform")[0].checkValidity()) {
            // If the form is invalid, submit it. The form won't actually submit;
            // this will just cause the browser to display the native HTML5 error messages.
            $("#hiddensubmit").click();
        } else {
            var refid = $('#modalref').val()
            var refname = $('#modalref option:selected').text();

            var tablelist = $("#tableassignref tbody input[type=checkbox]:checked");

            if (tablelist.length > 0) {
                tablelist.each(function () {
                    var row = $(this).closest("tr");
                    let customerid = row.find('td:eq(0)').html();
                    let customername = row.find('td:eq(1)').html();

                    $('#tableassigncomplete > tbody:last').append(
                        '<tr class="pointer"><td>' + customerid +
                        '</td><td>' + customername + '</td><td>' + refname +
                        '</td><td class = "d-none">' + refid + '</td></tr>');

                    row.remove()
                });

            }

        }
    });

    $('#customerassignref').click(function () {
        var tbody = $("#tableassigncomplete tbody");

        if (tbody.children().length > 0) {
            jsonObj = [];
            $("#tableassigncomplete tbody tr").each(function () {
                item = {}
                $(this).find('td').each(function (col_idx) {
                    item["col_" + (col_idx + 1)] = $(this).text();
                });
                jsonObj.push(item);
            });

            var salesref = $('#modalref').val();
            $.ajax({
                type: "POST",
                data: {
                    tableData: jsonObj,
                    salesref: salesref,

                },
                url: 'process/salesrefassignprocess.php',
                success: function (result) { //alert(result);
                    $('#modalassignref').modal('hide');
                    action(result);
                    $('#modalref').val('');
                    $('#tableassigncomplete tbody').empty()
                }
            });
        }
    })
</script>