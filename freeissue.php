<?php 
include "include/header.php"; 
include "include/topnavbar.php"; 

$sql="SELECT * FROM `tbl_freeissue_type` WHERE `status` = '1'";
$result =$conn-> query($sql); 


$sqlproduct="SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status` = '1'";
$resultproduct =$conn-> query($sqlproduct); 

$sqlcustomer="SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE `status` = '1'";
$resultCustomer =$conn-> query($sqlcustomer); 

$sqldata="SELECT `ft`.`type`, `fe`.`idtbl_free_issue`, `fe`.`reason`, `fe`.`updatedatetime` FROM `tbl_free_issue` as `fe` JOIN `tbl_freeissue_type` as `ft` on (`ft`.`idtbl_freeissue_type` = `fe`.`tbl_freeissue_type_idtbl_freeissue_type`)";
$resultdata =$conn-> query($sqldata); 

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
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i data-feather="activity"></i></div>
                                    <span>Free issue Management</span>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button class="btn btn-outline-primary btn-sm " data-toggle="modal"
                                    data-target="#modelfreeissue">
                                    <i class="far fa-save"></i>&nbsp;Add new Free issue
                                </button>

                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-12">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Issue Type</th>
                                                <th>Insert Date</th>
                                                <th>Reason</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if($resultdata->num_rows > 0) {while ($row = $resultdata-> fetch_assoc()) { ?>
                                            <tr>
                                                <td><?php echo $row['idtbl_free_issue'] ?></td>
                                                <td><?php echo $row['type'] ?></td>
                                                <td><?php echo $row['updatedatetime'] ?></td>
                                                <td><?php echo $row['reason'] ?></td>
                                                <td class="text-center">
                                                    <button class="btn btn-outline-primary btn-sm btnview"
                                                        id="<?php echo $row['idtbl_free_issue'] ?>"><i
                                                            data-feather="eye"></i></button>
                                                </td>
                                            </tr>
                                            <?php }} ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Model -->
<div class="modal fade" id="modelfreeissue" tabindex="-1" aria-labelledby="modelfreeissue" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modelfreeissue">Free issue Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="freeissueform" enctype="multipart/form-data" method="post" autocomplete="off">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Issue Type*</label>
                                        <select type="text" class="form-control form-control-sm" name="type" id="type"
                                            required>
                                            <option value="">Select</option>
                                            <?php if($result->num_rows > 0) {while ($rowtypelist = $result-> fetch_assoc()) { ?>
                                            <option value="<?php echo $rowtypelist['idtbl_freeissue_type'] ?>">
                                                <?php echo $rowtypelist['type'] ?></option>

                                            <?php }} ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id = 'divinvoice'>
                                <div class="col-md-6">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Customer*</label>
                                        <select type="text" class="form-control form-control-sm" name="customerId"
                                            id="customerId">
                                            <option value="">Select</option>
                                            <?php if($resultCustomer->num_rows > 0) {while ($rowcustomer = $resultCustomer-> fetch_assoc()) { ?>
                                            <option value="<?php echo $rowcustomer['idtbl_customer'] ?>">
                                                <?php echo $rowcustomer['name'] ?></option>

                                            <?php }} ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Invoice Id*</label>
                                        <select type="text" class="form-control form-control-sm" name="invoice"
                                            id="invoice">
                                            <option>Select...</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Product*</label>
                                        <select type="text" class="form-control form-control-sm" name="product"
                                            id="product" required>
                                            <option value="">Select</option>
                                            <?php if($resultproduct->num_rows > 0) {while ($rowtypelist = $resultproduct-> fetch_assoc()) { ?>
                                            <option value="<?php echo $rowtypelist['idtbl_product'] ?>">
                                                <?php echo $rowtypelist['product_name'] ?></option>

                                            <?php }} ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Quantity*</label>
                                        <input type="text" class="form-control form-control-sm" name="qty" id="qty"
                                            required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mt-2">
                                        <button type="button" id="submitBtn"
                                            class="btn btn-outline-primary btn-sm w-50 fa-pull-right"><i
                                                class="far fa-save"></i>&nbsp;Add</button>
                                    </div>
                                </div>
                            </div>
                            <input type="submit" class="d-none" value="submit" id="btnsubmit">

                        </form>
                    </div>
                    <div class="col-md-12">
                        <hr>
                        <table class="table table-bordered table-striped table-sm nowrap" id="tablefreeissue">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group mb-1">
                                    <label class="small font-weight-bold text-dark">Remarks*</label>
                                    <textarea class="form-control form-control-sm" name="remarks" id="remarks"
                                        required></textarea>
                                </div>
                            </div>

                            <div class="col-md-12">

                                <button type="button" id="btncreatefreeissue"
                                    class="btn btn-outline-primary btn-sm w-20 fa-pull-right"><i
                                        class="far fa-save"></i>&nbsp;Create
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

</div>

<!-- Model -->
<div class="modal fade" id="freeissuedetails" tabindex="-1" role="dialog" aria-labelledby="freeissuedetails"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalquotationtitle">Free issue Details</h3>
                <h5 class="modal-title" id="modalquotationtitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="freeissuedetailsbody"></div>
            </div>

        </div>
    </div>
</div>


<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function () {
        var addcheck
        var editcheck
        var statuscheck
        var deletecheck
        $('#divinvoice').hide();

        $('#dataTable tbody').on('click', '.btnview', function () {
            var id = $(this).attr('id');
            // alert(id);
            $.ajax({
                type: "POST",
                data: {
                    recordID: id
                },
                url: 'getprocess/getissuedetails.php',
                success: function (result) {

                    $('#freeissuedetailsbody').html(result);
                    $('#freeissuedetails').modal('show');
                }
            });

        });

    });



    $("#submitBtn").click(function () {
        if (!$("#freeissueform")[0].checkValidity()) {
            $("#btnsubmit").click();
        } else {
            // var typetext = $("#type option:selected").text();
            // var typeid = $("#type option:selected").val();
            var producttext = $("#product option:selected").text();
            var productid = $("#product option:selected").val();
            var qty = parseInt($('#qty').val());

            //alert(customer);

            $('#tablefreeissue > tbody:last').append('<tr class="pointer"><td class="d-none">' + productid +
                '</td><td>' + producttext + '</td><td>' + qty + '</td></tr>');

            $('#qty').val('');
            $('#product').val('');



        }
    });

    $('#btncreatefreeissue').click(function () {
        var tbody = $("#tablefreeissue tbody");

        if (tbody.children().length > 0) {
            jsonObj = [];
            $("#tablefreeissue tbody tr").each(function () {
                item = {}
                $(this).find('td').each(function (col_idx) {
                    item["col_" + (col_idx + 1)] = $(this).text();
                });
                jsonObj.push(item);
            });

            var remarks = $('#remarks').val();
            var typeID = $('#type').val();
            var customerId = $('#customerId').val();
            var returninvoice = $('#invoice').val();

            $.ajax({
                type: "POST",
                data: {
                    tableData: jsonObj,
                    remarks: remarks,
                    returninvoice: returninvoice,
                    customerId: customerId,
                    typeID: typeID,
                },

                url: 'process/freeissueprocess.php',
                success: function (result) {
                    console.log(result)
                    var obj = JSON.parse(result);

                    $('#tablefreeissue tbody').empty();
                    $('#modelfreeissue').modal('hide')
                    $('#customerId').val('');
                    $('#invoice').empty();
                    action(obj.action);
                }
            });
        }
    });

    $('#customerId').change(function () {
        var customerId = $(this).val();
        $.ajax({
            type: "POST",
            data: {
                recordId: customerId,
            },
            url: 'getprocess/getCustomerDamageInvoices.php',
            success: function (result) {
                var obj = JSON.parse(result);
                var html1 = '';
                html1 += '<option value="">Select</option>';
                $.each(obj, function (i, item) {
                    html1 += '<option value="' + obj[i].returnid + '">';
                    html1 += 'Return: ' + obj[i].returnid;
                    html1 += '</option>';

                    $('#invoice').empty().append(html1);
                });
            }
        });

    })
    $('#type').change(function () {
        var val = $(this).val();
        if(val == 2){
            $('#divinvoice').show();
        }else{
            $('#divinvoice').hide();
        }

    })

    function action(data) { //alert(data);
        var obj = JSON.parse(data);
        $.notify({
            // options
            icon: obj.icon,
            title: obj.title,
            message: obj.message,
            url: obj.url,
            target: obj.target
        }, {
            // settings
            element: 'body',
            position: null,
            type: obj.type,
            allow_dismiss: true,
            newest_on_top: false,
            showProgressbar: false,
            placement: {
                from: "top",
                align: "center"
            },
            offset: 100,
            spacing: 10,
            z_index: 1031,
            delay: 5000,
            timer: 1000,
            url_target: '_blank',
            mouse_over: null,
            animate: {
                enter: 'animated fadeInDown',
                exit: 'animated fadeOutUp'
            },
            onShow: null,
            onShown: null,
            onClose: null,
            onClosed: null,
            icon_type: 'class',
            template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                '<span data-notify="icon"></span> ' +
                '<span data-notify="title">{1}</span> ' +
                '<span data-notify="message">{2}</span>' +
                '<div class="progress" data-notify="progressbar">' +
                '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                '</div>' +
                '<a href="{3}" target="{4}" data-notify="url"></a>' +
                '</div>'
        });
        reload();
    }
    function reload(){
        location.reload()
    }
</script>