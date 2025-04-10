<?php
include "include/header.php";

$sqlcommonnames = "SELECT DISTINCT `common_name` FROM `tbl_product` WHERE `status`=1";
$resultcommonnames = $conn->query($sqlcommonnames);

$sqlproduct = "SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1";
$resultproduct = $conn->query($sqlproduct);

$sqlbank = "SELECT `idtbl_bank`, `bankname` FROM `tbl_bank` WHERE `status`=1 AND `idtbl_bank`>1";
$resultbank = $conn->query($sqlbank);

$sqlreplist = "SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `tbl_user_type_idtbl_user_type`=7 AND `status`=1";
$resultreplist = $conn->query($sqlreplist);

$sqlsalemanagerlist = "SELECT `idtbl_sales_manager`, `salesmanagername` FROM `tbl_sales_manager` WHERE `status`=1";
$resultmanagerlist = $conn->query($sqlsalemanagerlist);

$sqlcustomerlist = "SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE `status`=1";
$resultcustomerlist = $conn->query($sqlcustomerlist);

$sqllocationlist = "SELECT `idtbl_locations`, `locationname` FROM `tbl_locations` WHERE `status`=1";
$resultlocationlist = $conn->query($sqllocationlist);

$sqlarealist = "SELECT `idtbl_area`, `area` FROM `tbl_area` WHERE `status`=1 ORDER BY `area`";
$resultarealist = $conn->query($sqlarealist);

$sqlhelperlist = "SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `tbl_user_type_idtbl_user_type`=7 AND `status`=1";
$resulthelperlist = $conn->query($sqlhelperlist);

$sqlcustomer = "SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE `status`=1 ORDER BY `name` ASC";
$resultcustomer = $conn->query($sqlcustomer);

include "include/topnavbar.php";
?>
<style>
    .tableprint {
        table-layout: fixed;
    }

    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <?php include "include/menubar.php"; ?>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="page-header page-header-light bg-white shadow">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-11">
                            <div class="page-header-content py-3">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i data-feather="archive"></i></div>
                                    <span>Customer Cancelled POrder</span>
                                </h1>
                            </div>
                        </div>
                        <div class="col-1">
                            <div class="text-right mt-5">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12">
                                <hr>
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Order No</th>
                                                <th>Rep Name</th>
                                                <th>Remarks</th>
                                                <th>Customer</th>
                                                <th class="text-right">Subtotal</th>
                                                <th class="text-right">Discount</th>
                                                <th class="text-right">Nettotal</th>
                                                <th class="text-center">Confirm</th>
                                                <th class="text-center">Dispatch</th>
                                                <th class="text-center">Deliver</th>
                                                <th class="text-right">Actions</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>

</div>
<!-- Modal order view -->
<div class="modal fade" id="modalorderview" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h6 class="modal-title" id="viewmodaltitle"></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <input type="hidden" id="hiddenpoid">
            <input type="hidden" id="acceptanceType">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <label>Customer name: <span id="dcusname"></span></label>
                    </div>
                    <div class="col-md-6">
                        <label>Customer Contact: <span id="dcuscontact">sss</span></label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group mb-1">
                            <label class="small font-weight-bold text-dark">Product*</label>
                            <select class="form-control form-control-sm select2" style="width: 100%;"
                                name="modaleditproduct" id="modaleditproduct" required>
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group mb-1">
                            <label class="small font-weight-bold text-dark">Sale price*</label>
                            <input type="text" class="form-control form-control-sm" id="modaleditsaleprice" name="modaleditsaleprice"
                                required>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group mb-1">
                            <label class="small font-weight-bold text-dark">Qty*</label>
                            <input type="text" class="form-control form-control-sm" id="modaleditqty" name="modaleditqty"
                                required>
                               
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group mb-1">
                            <label class="small font-weight-bold text-dark">Discount (%)*</label>
                            <input type="text" class="form-control form-control-sm" id="modaleditdiscountpercentage" name="modaleditdiscountpercentage"
                                value = "0" required>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group mb-1">
                            <label class="small font-weight-bold text-dark">Discount*</label>
                            <input type="text" class="form-control form-control-sm" id="modaleditdiscountamount" name="modaleditdiscountamount"
                                value = "0" required>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group mb-1">
                            <label class="small font-weight-bold text-dark">Total*</label>
                            <input type="text" class="form-control form-control-sm" id="modaleditnettotal" name="modaleditnettotal"
                                required>
                               
                        </div>
                    </div>
                    <input type="hidden" id="modaleditproductcode" id="modaleditproductcode">
                    <input type="hidden" id="modaleditproductunitprice" id="modaleditproductunitprice">
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <button class="btn btn-secondary btn-sm fa-pull-right" id="btnAddNewProduct"><i
                        class="fa fa-save"></i>&nbsp;Add New Product</button>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <div class="form-check">
                            <input class="form-check-input" id="statusValue" type="checkbox">
                            <label class="form-check-label" for="statusValue">Change Status</label>
                        </div>
                    </div>
                </div>
                <div class="row mt-3" id="errordivaddnew">

                </div>
                <table class="table table-striped table-bordered table-sm small mt-4" id="tableorderview">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Product Code</th>
                            <th class="d-none">ProductID</th>
                            <th class="d-none">PoDetailID</th>
                            <th class="text-center">Qty</th>
                            <th class="text-center">Discount (%)</th>
                            <th class="text-right"> Discount</th>
                            <th class="text-right">Total</th>
                            <th class="text-right">Unit Price</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <div class="row">
                    <div class="col-md-3">
                        <label>PO Discount (%)</label>
                        <input class="form-control form-control-sm" type="number" id="editpodiscount"
                            name="editpodiscount" required>
                    </div>
                    <div class="col-md-3">
                        <label>PO Discount</label>
                        <input class="form-control form-control-sm" type="number" id="editpodiscountamount"
                            name="editpodiscountamount" value="0" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-9 text-right">
                        <h5 class="font-weight-600">Item Count</h5>
                    </div>
                    <div class="col-3 text-right">
                        <h5 class="font-weight-600" id="divitemcountview">0</h5>
                    </div>
                    <div class="col-9 text-right">
                        <h5 class="font-weight-600">Subtotal</h5>
                    </div>
                    <div class="col-3 text-right">
                        <h5 class="font-weight-600" id="divsubtotalview">Subtotal: Rs. 0.00</h5>
                    </div>
                    <div class="col-9 text-right">
                        <h5 class="font-weight-600">Discount</h5>
                    </div>
                    <div class="col-3 text-right">
                        <h5 class="font-weight-600" id="divdiscountview">Rs. 0.00</h5>
                    </div>
                    <div class="col-9 text-right">
                        <h5 class="font-weight-600">PO Discount</h5>
                    </div>
                    <div class="col-3 text-right">
                        <h5 class="font-weight-600" id="divdiscountPOview">Rs. 0.00</h5>
                    </div>
                    <div class="col-9 text-right">
                        <h1 class="font-weight-600">Nettotal</h1>
                    </div>
                    <div class="col-3 text-right">
                        <h1 class="font-weight-600" id="divtotalview">Rs. 0.00</h1>
                    </div>
                    <div class="col-12">
                        <h6 class="title-style"><span>Remark Information</span></h6>
                    </div>
                    <div class="col-12">
                        <textarea class="form-control form-control-sm" type="text" id="remarkview" name="remarkview"></textarea>

                    </div>
                </div>
                <button class="btn btn-primary btn-sm fa-pull-right" id="btnUpdate"><i
                        class="fa fa-save"></i>&nbsp;Update</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modaleditpo" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h6 class="modal-title" id="viewmodaltitle">Update PO Details</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <input type="hidden" id="hiddencustomerpoid">
            <div class="modal-body">
                <form id="editcusporderform" autocomplete="off">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="small font-weight-bold text-dark">Order Date*</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control dpd2a" placeholder="" name="editorderdate"
                                    id="editorderdate" required>
                                <div class="input-group-append">
                                    <span class="btn btn-light border-gray-500"><i class="far fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="small font-weight-bold text-dark">Customer*</label>
                            <select class="form-control form-control-sm" style="width: 100%;" name="editcustomer"
                                id="editcustomer">
                                <option value="0">All</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="small font-weight-bold text-dark">Sales Rep*</label>
                            <select class="form-control form-control-sm" name="editsalesrep" id="editsalesrep" required>
                                <option value="">Select</option>
                                <?php if ($resultreplist->num_rows > 0) {
                                     while ($rowreplist = $resultreplist->fetch_assoc()) { ?>
                                <option value="<?php echo $rowreplist['idtbl_employee'] ?>">
                                    <?php echo $rowreplist['name'] ?></option>
                                <?php } } ?>
                            </select>
                        </div>
                    </div>
                    <button ctype="button" class="btn btn-primary btn-sm fa-pull-right mt-3" id="btncuspoupdate"><i
                            class="fa fa-save"></i>&nbsp;Update</button>
                    <input type="submit" class="d-none" id="hiddeneditsubmit">
                </form>
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
<!-- Modal Cancel Reason -->
<div class="modal fade" id="modalcancel" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Cancel Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="process/cancelstatus.php" method="post">
                    <div class="form-group mb-1">
                        <label class="small font-weight-bold text-dark">Cancel Reason*</label>
                        <textarea type="text" class="form-control form-control-sm" name="cancelreason" id="cancelreason"
                            required rows="5"></textarea>
                    </div>
                    <div class="form-group mt-3">
                        <button type="submit" id="submitBtn" class="btn btn-outline-danger btn-sm px-4 fa-pull-right"
                            <?php if ($addcheck == 0) {echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Cancel
                            Order</button>
                    </div>
                    <input type="hidden" name="recordID" id="recordID" value="">
                    <input type="hidden" name="type" id="type" value="4">
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="printreport" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">View Porder PDF</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="embed-responsive embed-responsive-16by9" id="frame">
                            <iframe class="embed-responsive-item" frameborder="0"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="printreportInvoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">View Invoice PDF</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="embed-responsive embed-responsive-16by9" id="frame">
                            <iframe class="embed-responsive-item" frameborder="0"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="printreportInvoiceoriginal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">View Original Invoice PDF</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="embed-responsive embed-responsive-16by9" id="frame">
                            <iframe class="embed-responsive-item" frameborder="0"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "include/footerscripts.php"; ?>
<script>
    var prodCount = 0;
    $(document).ready(function () {
        var addcheck
        var editcheck
        var statuscheck
        var deletecheck

        $('body').tooltip({
            selector: '[data-toggle="tooltip"]'
        });
        

        $('[data-toggle="tooltip"]').tooltip({
            trigger: 'hover'
        });

        var addcheck = '<?php echo $addcheck; ?>';
        var editcheck = '<?php echo $editcheck; ?>';
        var statuscheck = '<?php echo $statuscheck; ?>';
        var deletecheck = '<?php echo $deletecheck; ?>';
        var usertype = '<?php echo $_SESSION['type']; ?>';

        $('#dataTable').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "scripts/customercancelledporderlist.php",
                type: "POST", // you can use GET
            },
            "order": [
                [0, "desc"]
            ],
            "columns": [{
                    "data": "idtbl_customer_order"
                },
                {
                    "data": "date"
                },
                {
                    "data": "cuspono"
                },
                {
                    "data": "repname"
                },
                {
                    "data": "remark",
                    "render": function (data, type, full) {
                        if (data.length > 30) {
                            return data.substring(0, 30) + "..."; 
                        }
                        return data; 
                    }
                },
                {
                    "data": "cusname"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function (data, type, full) {
                        return parseFloat(full['total']).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function (data, type, full) {
                        return parseFloat(full['discount']).toFixed(2);
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function (data, type, full) {
                        return parseFloat(full['nettotal']).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-center',
                    "data": null,
                    "render": function (data, type, full) {
                        var html = '';
                        if (full['confirm'] == 1) {
                            html += '<i class="fas fa-check text-success"></i>&nbsp;Confirm';
                        } else if (full['confirm'] == 2) {
                            html += '<i class="fas fa-times text-danger"></i>&nbsp;Cancelled';
                        } else {
                            html +=
                                '<i class="fas fa-times text-danger"></i>&nbsp;Not Confirmed';
                        }
                        return html;
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-center',
                    "data": null,
                    "render": function (data, type, full) {
                        var html = '';
                        if (full['dispatchissue'] == 1) {
                            html += '<i class="fas fa-check text-success"></i>&nbsp;Dispatched';
                        } else {
                            html +=
                                '<i class="fas fa-times text-warning"></i>&nbsp;Not Dispatched';
                        }
                        return html;
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-center',
                    "data": null,
                    "render": function (data, type, full) {
                        var html = '';
                        if (full['delivered'] == 1) {
                            html += '<i class="fas fa-check text-success"></i>&nbsp;Delivered';
                        } else {
                            html +=
                                '<i class="fas fa-times text-warning"></i>&nbsp;Not Delivered';
                        }
                        return html;
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function (data, type, full) {
                        var button = '';

                        button +=
                            '<button class="btn btn-outline-info btn-sm btnOriginal mr-1 " data-toggle="tooltip" data-placement="bottom" title="View Original PO Details" id="' +
                            full['idtbl_customer_order'] + '" name="' + full['confirm'] +
                            '"><i class="fas fa-eye"></i></button>';

                        button +=
                            '<button class="btn btn-outline-';
                        if (full['is_printed'] == 0) {
                            button += 'secondary';
                        } else {
                            button += 'success';
                        }

                        button +=
                            ' btn-sm btnPrint mr-1 " data-toggle="tooltip" data-placement="bottom" title="Print PO Details" id="' +
                            full['idtbl_customer_order'] + '" name="' + full['confirm'] +
                            '"><i class="fas fa-file-invoice-dollar"></i></button>';

                        button +=
                            '<button class="btn btn-outline-secondary btn-sm btnInvoicePrint mr-1" data-placement="bottom" title="Invoice Print" id="' +
                            full['idtbl_customer_order'] +
                            '"><i class="fas fa-eye"></i></button>';

                        return button;
                    }
                }
            ]
        });

        $('#modaleditproduct').change(function(){
            var productID = $('#modaleditproduct').val();
            var product = $("#modaleditproduct option:selected").text();

            $.ajax({
                type: "POST",
                data: {
                    recordID: productID
                },
                url: 'getprocess/getproduct.php',
                success: function (result) { //console.log(result);
                    var obj = JSON.parse(result);

                    $('#modaleditproductcode').val(obj.productcode);
                    $('#modaleditsaleprice').val(obj.saleprice);
                    $('#modaleditproductunitprice').val(obj.unitprice);
                    $('#modaleditqty').val(0);
                }
            });
        })



        $('#dataTable tbody').on('click', '.btnInvoicePrint', function () {
            var id = $(this).attr('id');
            // alert(id);
            $('#frame').html('');
            $('#frame').html('<iframe class="embed-responsive-item" frameborder="0"></iframe>');
            $('#printreportInvoice iframe').contents().find('body').html(
                "<img src='images/spinner.gif' class='img-fluid' style='margin-top:200px;margin-left:500px;' />"
            );

            var src = 'pdfprocess/porderinvoicepdf.php?id=' + id;
            //            alert(src);
            var width = $(this).attr('data-width') ||
                640; // larghezza dell'iframe se non impostato usa 640
            var height = $(this).attr('data-height') ||
                360; // altezza dell'iframe se non impostato usa 360

            var allowfullscreen = $(this).attr(
                'data-video-fullscreen'
                ); // impostiamo sul bottone l'attributo allowfullscreen se è un video per permettere di passare alla modalità tutto schermo

            // stampiamo i nostri dati nell'iframe
            $("#printreportInvoice iframe").attr({
                'src': src,
                'height': height,
                'width': width,
                'allowfullscreen': ''
            });
            $('#printreportInvoice').modal({
                keyboard: false,
                backdrop: 'static'
            });
        });

        $('#dataTable tbody').on('click', '.btnOriginal', function () {
            var id = $(this).attr('id');
            // alert(id);
            $('#frame').html('');
            $('#frame').html('<iframe class="embed-responsive-item" frameborder="0"></iframe>');
            $('#printreportInvoiceoriginal iframe').contents().find('body').html(
                "<img src='images/spinner.gif' class='img-fluid' style='margin-top:200px;margin-left:500px;' />"
            );

            var src = 'pdfprocess/porderoriginalpdf.php?id=' + id;
            //            alert(src);
            var width = $(this).attr('data-width') ||
                640; // larghezza dell'iframe se non impostato usa 640
            var height = $(this).attr('data-height') ||
                360; // altezza dell'iframe se non impostato usa 360

            var allowfullscreen = $(this).attr(
                'data-video-fullscreen'
                ); // impostiamo sul bottone l'attributo allowfullscreen se è un video per permettere di passare alla modalità tutto schermo

            // stampiamo i nostri dati nell'iframe
            $("#printreportInvoiceoriginal iframe").attr({
                'src': src,
                'height': height,
                'width': width,
                'allowfullscreen': ''
            });
            $('#printreportInvoiceoriginal').modal({
                keyboard: false,
                backdrop: 'static'
            });
        });

        $('#dataTable tbody').on('click', '.btnPrint', function () {
            var id = $(this).attr('id');
            $('#frame').html('');
            $('#frame').html('<iframe class="embed-responsive-item" frameborder="0"></iframe>');
            $('#printreport iframe').contents().find('body').html(
                "<img src='images/spinner.gif' class='img-fluid' style='margin-top:200px;margin-left:500px;' />"
            );

            var src = 'pdfprocess/porderpdf.php?id=' + id;
            var width = $(this).attr('data-width') || 640; // width of the iframe, default is 640
            var height = $(this).attr('data-height') || 360; // height of the iframe, default is 360

            var allowfullscreen = $(this).attr(
                'data-video-fullscreen'
                ); // set allowfullscreen attribute if it's a video to allow fullscreen mode

            // Set iframe attributes
            $("#printreport iframe").attr({
                'src': src,
                'height': height,
                'width': width,
                'allowfullscreen': ''
            });

            // Show the modal
            $('#printreport').modal({
                keyboard: false,
                backdrop: 'static'
            });

            // Refresh DataTable after the modal is closed
            $('#printreport').on('hidden.bs.modal', function () {
                $('#dataTable').DataTable().ajax.reload();
            });
        });



        $('.dpd1a').datepicker({
            uiLibrary: 'bootstrap4',
            autoclose: 'true',
            todayHighlight: true,
            startDate: 'today',
            format: 'yyyy-mm-dd'
        });

        $('.dpd2a').datepicker({
            uiLibrary: 'bootstrap4',
            autoclose: 'true',
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        });

        // Order view part
        $('#dataTable tbody').on('click', '.btnview', function () {
            var id = $(this).attr('id');
            var confirmstatus = $(this).attr('name');
            $('#hiddenpoid').val(id);
            $.ajax({
                type: "POST",
                data: {
                    orderID: id
                },
                url: 'getprocess/getcusorderlistaccoorderid.php',
                success: function (result) { //console.log(result);
                    var obj = JSON.parse(result);
                    var count=0;

                    $('#divsubtotalview').html(obj.subtotal);
                    $('#divdiscountview').html(obj.disamount);
                    $('#divdiscountPOview').html(obj.po_amount);
                    $('#divtotalview').html(obj.nettotalshow);
                    $('#remarkview').val(obj.remark);
                    $('#dcusname').html(obj.cusname);
                    $('#dcuscontact').html(obj.cuscontact);
                    $('#viewmodaltitle').html('Order No: PO-' + id);
                    $('#editpodiscount').val(obj.podiscountpercentage);

                    var confirmstatus = obj.confirm;
                    var dispatchstatus = obj.dispatchissue;
                    var deliverstatus = obj.delivered;

                    var objfirst = obj.tablelist;
                    $.each(objfirst, function (i, item) {

                        var showqty = 0;
                        if (confirmstatus == null) {
                            showqty = objfirst[i].orderqty;
                        } else if (confirmstatus == 1 && dispatchstatus == null) {
                            showqty = objfirst[i].confirmqty;
                        } else if (confirmstatus == 1 && dispatchstatus == 1 &&
                            deliverstatus == null) {
                            showqty = objfirst[i].dispatchqty;
                        } else {
                            showqty = objfirst[i].qty;
                        }

                        $('#tableorderview > tbody:last').append('<tr><td>' +
                            objfirst[i].productname +
                            '</td><td>' +
                            objfirst[i].productcode +
                            '</td><td class="d-none">' + objfirst[i].productid +
                            '</td><td class="d-none">' + objfirst[i]
                            .podetailid +
                            '</td><td class="text-center editnewqty">' +
                            showqty +
                            '</td><td class="text-center editlinediscountpernetage">' +
                            objfirst[i].discountpresent +
                            '</td><td class="text-center editlinediscount">' +
                            objfirst[i].discount +
                            '</td><td class="text-right total">' + objfirst[i]
                            .total +
                            '</td><td class="text-right colunitprice">' +
                            objfirst[i]
                            .unitprice +
                            '</td><td class="text-right"><button class="btn btn-outline-danger btn-sm btnDeleteOrderProduct mr-1" data-placement="bottom" title="Invoice Print" id="' +
                            objfirst[i]
                            .podetailid +
                            '" disabled><i class="fas fa-trash"></i></button></td><td class="d-none">' +
                            objfirst[i]
                            .status +
                            '</td><td class="d-none totwithoutdiscount">' +
                            objfirst[i]
                            .totwithoutdiscount +
                            '</td><td class="d-none">0</td></tr>');

                        var newRow = $('#tableorderview > tbody:last tr:last');

                        if (objfirst[i].status == 3) {
                            newRow.css('background-color', '#ffcccc');
                            newRow.find('.btnDeleteOrderProduct').removeClass()
                                .addClass('btn btn-outline-success btn-sm');
                        }else{
                            count++;
                        }
                    });
                    $('#divitemcountview').html(count);

                    $('#btnUpdate').prop('disabled', true);
                    $('#modalorderview').modal('show');
                }
            });
        });


        function tabletotal1() {
            let sum = 0, totallinediscount = 0, count = 0;
            let podiscountPercent = parseFloat($('#editpodiscount').val()) || 0;

            let totRows = document.querySelectorAll(".totwithoutdiscount");
            let discRows = document.querySelectorAll(".editlinediscount");

            // Convert NodeLists to arrays and process them together
            let allRows = [...totRows, ...discRows];

            allRows.forEach(cell => {
                let row = cell.closest('tr');
                let status = row.cells[10]?.textContent.trim(); // Directly access 11th <td> (index 10)

                if (status === "3") return; // Skip rows where status == 3

                let value = parseFloat(cell.textContent.replace(/,/g, "")) || 0;

                if (cell.classList.contains("totwithoutdiscount")) {
                    sum += value;
                    count++;
                } else if (cell.classList.contains("editlinediscount")) {
                    totallinediscount += value;
                }
            });

            let poDiscount = ((sum - totallinediscount) * podiscountPercent) / 100;
            let netTot = sum - (totallinediscount + poDiscount);

            // Batch update UI to prevent layout thrashing
            let updates = {
                "#divitemcountview": count,
                "#divsubtotalview": addCommas(sum.toFixed(2)),
                "#divtotalview": addCommas(netTot.toFixed(2)),
                "#divdiscountview": addCommas(totallinediscount.toFixed(2)),
                "#divdiscountPOview": addCommas(poDiscount.toFixed(2))
            };

            Object.keys(updates).forEach(id => document.querySelector(id).textContent = updates[id]);
        }

        $('#modalorderview').on('hidden.bs.modal', function (e) {
            $('#tableorderview > tbody').html('');
        });
        // Order print part
        $('#dataTable tbody').on('click', '.btnprint', function () {
            var id = $(this).attr('id');
            $.ajax({
                type: "POST",
                data: {
                    orderID: id
                },
                url: 'getprocess/getcusorderprint.php',
                success: function (result) {
                    $('#viewdispatchprint').html(result);
                    $('#modalorderprint').modal('show');
                }
            });
        });
        document.getElementById('btnorderprint').addEventListener("click", print);

 
        $('#modalcreateorder').on('hidden.bs.modal', function () {
            $('#orderdate').val('');
            $('#product').val('');
            $('#repname').val('');
            $('#area').val('');
            $('#customer').val('');
            $('#unitprice').val('');
            $('#saleprice').val('');
            $('#newqty').val('0');
            $('#freeqty').val('0');
            $('#freeproductname').val('');
            $('#freeproductid').val('0');
            $('#remark').val('');
            $('#divtotal').html('Rs. 0.00');
            $('#hidetotalorder').val('0');
            $('#tableorder > tbody').html('');
        })

    });

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
    }

    function category(repId, value) {
        // alert(repId);
        // alert(value);
        $.ajax({
            type: "POST",
            data: {
                repId: repId
            },
            url: 'getprocess/getareasaccoemployee.php',
            success: function (result) { //alert(result);
                var objfirst = JSON.parse(result);

                var html = '';
                html += '<option value="">Select</option>';
                $.each(objfirst, function (i, item) {
                    //alert(objfirst[i].id);
                    html += '<option value="' + objfirst[i].id + '">';
                    html += objfirst[i].name;
                    html += '</option>';
                });

                $('#area').empty().append(html);

                if (value != '') {
                    $('#area').val(value);
                }
            }
        });
    }

    function selectcustomer(repId, areaID, value) {
        //  alert(repId);
        // alert(areaID);
        // alert(value);
        $.ajax({
            type: "POST",
            data: {
                areaID: areaID,
                repId: repId
            },
            url: 'getprocess/getcustomerlistaccoarea.php',
            success: function (result) { //alert(result);
                var objfirst = JSON.parse(result);

                var html = '';
                html += '<option value="">Select</option>';
                $.each(objfirst, function (i, item) {
                    //alert(objfirst[i].id);
                    html += '<option value="' + objfirst[i].id + '">';
                    html += objfirst[i].name + ' (' + objfirst[i].address + ')';
                    html += '</option>';
                });

                $('#customer').empty().append(html);
                if (value != '') {
                    $('#customer').val(value);
                }
            }
        });
    }

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

    function print() {
        printJS({
            printable: 'viewdispatchprint',
            type: 'html',
            style: '@page { size: portrait; margin:0.25cm; }',
            targetStyles: ['*']
        })
    }

    function tabletotal() {
        var sum = 0;
        $(".totaldispatch").each(function () {
            sum += parseFloat($(this).text());
        });

        var showsum = addCommas(parseFloat(sum).toFixed(2));

        $('#divtotaldispatch').html('Rs. ' + showsum);
        $('#hidetotalorderdispatch').val(sum);
    }

    function calculateNewAddedProductTot() {
        var saleprice = $('#modaleditsaleprice').val();
        var qty = $('#modaleditqty').val();
        var discountprecentage = $('#modaleditdiscountpercentage').val();

        var totPrice = qty * saleprice;
        var discountAmount = (totPrice * discountprecentage) / 100;
        var netTotal = totPrice - discountAmount;

        $('#modaleditnettotal').val(parseFloat(netTotal).toFixed(2));

    }

    function calculateTotals(row) {
        var sum = 0;
        var disvalue = 0;
        $(".total").each(function () {
            sum += parseFloat($(this).text());
        });
        $(".linediscount").each(function () {
            disvalue += parseFloat($(this).text());
        });

        var discountpo = parseFloat($('#discountpo').val());

        var nettotal = sum - disvalue;
        var disvaluepo = (nettotal * discountpo) / 100;
        var nettotal = nettotal - disvaluepo;

        var showsum = addCommas(parseFloat(sum).toFixed(2));
        var showdis = addCommas(parseFloat(disvalue).toFixed(2));
        var showdispo = addCommas(parseFloat(disvaluepo).toFixed(2));
        var shownettotal = addCommas(parseFloat(nettotal).toFixed(2));

        $('#divsubtotal').html('Rs. ' + showsum);
        $('#hidetotalorder').val(sum);
        $('#divdiscount').html('Rs. ' + showdis);
        $('#hidediscount').val(disvalue);

        $('#hidediscountPO').val(disvaluepo);
        $('#divtotal').html('Rs. ' + shownettotal);
        $('#divdiscountPO').html('Rs. ' + showdispo);
        $('#hidenettotalorder').val(nettotal);
    }

    function order_confirm() {
        return confirm("Are you sure you want to Confirm this order?");
    }

    function delete_confirm() {
        return confirm("Are you sure you want to remove this?");
    }

    function datepickercloneload() {
        $('.dpd1a').datepicker({
            uiLibrary: 'bootstrap4',
            autoclose: 'true',
            todayHighlight: true,
            startDate: 'today',
            format: 'yyyy-mm-dd'
        });
    }
</script>
<?php include "include/footer.php"; ?>