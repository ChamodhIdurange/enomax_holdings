<?php
include "include/header.php";

$sqlreturncustomer = "SELECT `u`.`idtbl_return`, `u`.`returndate`, `u`.`total`, `ua`.`name`, `u`.`acceptance_status`, `u`.`damaged_reason` FROM `tbl_return` as `u` LEFT JOIN `tbl_customer` AS `ua` ON (`ua`.`idtbl_customer` = `u`.`tbl_customer_idtbl_customer`)  WHERE `u`.`acceptance_status` IN (0,1)";
$resultreturncustomer = $conn->query($sqlreturncustomer);

include "include/topnavbar.php";
?>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <?php include "include/menubar.php"; ?>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="page-header page-header-light bg-white shadow">
                <div class="container-fluid">
                    <div class="page-header-content py-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="corner-down-left"></i></div>
                            <span>All Return</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Customer name</th>
                                                <th>Date</th>
                                                <th>Remark</th>
                                                <th>Total</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($resultreturncustomer->num_rows > 0) {
                                                    while ($row = $resultreturncustomer->fetch_assoc()) { ?>
                                            <tr>
                                                <td><?php echo $row['idtbl_return'] ?></td>
                                                <td><?php echo $row['name'] ?></td>
                                                <td><?php echo $row['returndate'] ?></td>
                                                <td><?php echo $row['damaged_reason'] ?></td>
                                                <td class="text-right">Rs.<?php echo number_format($row['total'], 2); ?>
                                                </td>
                                                <td>
                                                    <button class="btn btn-primary btn-sm rounded btnView"
                                                        id="<?php echo $row['idtbl_return']; ?>"
                                                        name="<?php echo $row['acceptance_status']; ?>"><i
                                                            class="fas fa-eye"></i></button>
                                                    <?php if($row['acceptance_status'] == 0) { ?>
                                                    <button class="btn btn-outline-secondary btn-sm btnEdit"
                                                        id="<?php echo $row['idtbl_return']; ?>"
                                                        data-returndate="<?php echo $row['returndate']; ?>"><i
                                                            class="fas fa-pen"></i></button>
                                                    <?php }else ?>
                                                    <?php if($row['acceptance_status'] == 0) { ?>
                                                    <button
                                                        data-url="process/statusacceptreturn.php?record=<?php echo $row['idtbl_return'] ?>&type=2"
                                                        data-actiontype="8"
                                                        class="btn btn-outline-warning btn-sm btntableaction"><i
                                                            data-feather="x-square"></i></button>
                                                    <?php }else{ ?>
                                                    <button class="btn btn-outline-success btn-sm"><i
                                                            data-feather="check"></i></button>
                                                    <?php }?>
                                                </td>
                                            </tr>
                                            <?php }
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </main>
        <!-- Modal return details -->
        <div class="modal fade" id="modalreturndetails" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header p-2">
                        <h5 class="modal-title" id="viewmodaltitle"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div id="viewdetail"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal order print -->
        <div class="modal fade" id="modalorderprint" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header p-2">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="viewdispatchprint"></div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-danger btn-sm fa-pull-right" id="btnorderprint"><i
                                class="fas fa-print"></i>&nbsp;Print Order</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit Return -->
        <div class="modal fade" id="modaleditreturn" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal">
                <div class="modal-content">
                    <div class="modal-header p-2">
                        <h6 class="modal-title" id="viewmodaltitle">Update Return Details</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editreturnform" autocomplete="off">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="small font-weight-bold text-dark">Return Date*</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control dpd2a" placeholder=""
                                            name="editreturndate" id="editreturndate" required>
                                        <div class="input-group-append">
                                            <span class="btn btn-light border-gray-500"><i
                                                    class="far fa-calendar"></i></span>
                                        </div>
                                    </div>
                                    <input type="text" class="d-none" id="hiddenreturnid" name="hiddenreturnid">
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm fa-pull-right mt-3"
                                id="btnreturnupdate"><i class="fa fa-save"></i>&nbsp;Update</button>
                            <input type="submit" class="d-none" id="hiddeneditsubmit">
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php include "include/footerbar.php"; ?>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function () {
        document.getElementById('btnorderprint').addEventListener("click", print);
        $('#dataTable').DataTable({});
        $('#dataTable tbody').on('click', '.btnEdit', function () {
            var id = $(this).attr('id');
            var returndate = $(this).data('returndate');

            $('#modaleditreturn').modal('show');
            $('#hiddenreturnid').val(id);
            $('#editreturndate').val(returndate);
        });
    })

    $('#returntype').change(function () {
        var type = $(this).val();

        if (type == 1) {
            $('#customerdiv').removeClass('d-none');
            $('#customer').prop('required', true);

            $('#supplierdiv').addClass('d-none');
            $('#supplier').prop('required', false);
        } else if (type == 2) {
            $('#customerdiv').addClass('d-none');
            $('#customer').prop('required', false);

            $('#supplierdiv').removeClass('d-none');
            $('#supplier').prop('required', true);
        } else {
            $('#customerdiv').addClass('d-none');
            $('#customer').prop('required', false);
            $('#supplierdiv').addClass('d-none');
            $('#supplier').prop('required', false);
        }
    });

    $("#btnreturnupdate").click(function () {
        if (!$("#editreturnform")[0].checkValidity()) {
            $("#hiddeneditsubmit").click();
        } else {
            var returndate = $('#editreturndate').val();
            var returnId = $('#hiddenreturnid').val();

            $.ajax({
                type: "POST",
                data: {
                    returndate: returndate,
                    returnId: returnId
                },
                url: 'process/updatecustomerreturnprocess.php',
                success: function (result) { // alert(result)
                    var obj = JSON.parse(result);
                    if (obj.status == 1) {
                        actionreload(obj.action);
                    } else {
                        action(obj.action);
                    }
                }
            });
        }
    });

    $('#dataTable tbody').on('click', '.btnView', function () {
        var id = $(this).attr('id');
        var acceptancestatus = $(this).attr('name');
        // alert("asd")
        $.ajax({
            type: "POST",
            data: {
                recordID: id
            },
            url: 'getprocess/getreturndetails.php',
            success: function (result) {
                // alert(result)
                $('#viewmodaltitle').html('Return No ' + id)
                $('#viewdetail').html(result);
                $('#modalreturndetails').modal('show');
                if (acceptancestatus == 1) {
                    $('#submitBtn').attr('disabled', true);
                } else {
                    $('#submitBtn').attr('disabled', false);
                }

            }
        });
    });
    function print() {
        printJS({
            printable: 'viewdispatchprint',
            type: 'html',
            style: '@page { size: portrait; margin:0.25cm; }',
            targetStyles: ['*']
        })
    }
    $('.dpd2a').datepicker({
        uiLibrary: 'bootstrap4',
        autoclose: 'true',
        todayHighlight: true,
        format: 'yyyy-mm-dd'
    });



    function addCommas(nStr) {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }

    function accept_confirm() {
        return confirm("Are you sure you want to Accept this?");
    }

    function deactive_confirm() {
        return confirm("Are you sure you want to deactive this?");
    }

    function active_confirm() {
        return confirm("Are you sure you want to active this?");
    }

    function delete_confirm() {
        return confirm("Are you sure you want to remove this?");
    }

    function company_confirm() {
        return confirm("Are you sure this product send to company?");
    }

    function warehouse_confirm() {
        return confirm("Are you sure this product back to warehouse?");
    }

    function customer_confirm() {
        return confirm("Are you sure this product breturn back to customer?");
    }

    function credit_confirm() {
        return confirm("Are you sure you want to create credit note?");
    }
</script>
<?php include "include/footer.php"; ?>