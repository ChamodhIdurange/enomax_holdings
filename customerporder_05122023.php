<?php 
include "include/header.php";  

$sql="SELECT * FROM `tbl_porder` WHERE `confirmstatus` IN (1,0,2)";
$result =$conn-> query($sql); 

$sqlproduct="SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1";
$resultproduct =$conn-> query($sqlproduct); 

$sqlbank="SELECT `idtbl_bank`, `bankname` FROM `tbl_bank` WHERE `status`=1 AND `idtbl_bank`>1";
$resultbank =$conn-> query($sqlbank); 

$sqlreplist="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `tbl_user_type_idtbl_user_type`=7 AND `status`=1";
$resultreplist =$conn-> query($sqlreplist);

$sqlcustomerlist="SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE `status`=1";
$resultcustomerlist =$conn-> query($sqlcustomerlist);

$sqlarealist="SELECT `idtbl_area`, `area` FROM `tbl_area` WHERE `status`=1 ORDER BY `area`";
$resultarealist =$conn-> query($sqlarealist);

$sqlhelperlist="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `tbl_user_type_idtbl_user_type`=5 AND `status`=1";
$resulthelperlist =$conn-> query($sqlhelperlist);

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
                    <div class="page-header-content py-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="archive"></i></div>
                            <span>Customer POrder</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col">
                                        <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right"
                                            id="btnordercreate"><i class="fas fa-plus"></i>&nbsp;Create Purchsing
                                            Order</button>
                                    </div>
                                </div>
                                <hr>
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Order No</th>
                                                <th>Rep Name</th>
                                                <th>Area</th>
                                                <th>Customer</th>
                                                <th class="text-right">Subtotal</th>
                                                <th class="text-right">Discount</th>
                                                <th class="text-right">Nettotal</th>
                                                <th class="text-center">Confirm</th>
                                                <!-- <th class="text-center">Ship</th>
                                                <th class="text-center">Delivery</th> -->
                                                <th class="text-center">Tracking No</th>
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
<!-- Modal Create Order -->
<div class="modal fade" id="modalcreateorder" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header p-2">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3">
                        <form id="createorderform" autocomplete="off">
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold text-dark">Order Date*</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control dpd1a" placeholder="" name="orderdate"
                                        id="orderdate" required>
                                    <div class="input-group-append">
                                        <span class="btn btn-light border-gray-500"><i
                                                class="far fa-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold text-dark">Area*</label>
                                <select class="form-control form-control-sm" name="area" id="area" required>
                                    <option value="">Select</option>
                                    <?php if($resultarealist->num_rows > 0) {while ($rowarealist = $resultarealist-> fetch_assoc()) { ?>
                                    <option value="<?php echo $rowarealist['idtbl_area'] ?>">
                                        <?php echo $rowarealist['area'] ?></option>
                                    <?php }} ?>
                                </select>
                            </div>

                            <div class="form-group mb-2">
                                <label class="small font-weight-bold text-dark">Rep Name*</label>
                                <select class="form-control form-control-sm" name="repname" id="repname" required>
                                    <option value="">Select</option>
                                </select>
                            </div>

                            <div class="form-group mb-2" id="directcustomerdiv" hidden>
                                <label class="small font-weight-bold text-dark">Customers*</label>
                                <input type="text" placeholder="Enter customer name"
                                    class="form-control form-control-sm" name="directcustomer" id="directcustomer"
                                    required></input>
                            </div>

                            <div class="form-group mb-2" id="customerdiv">
                                <label class="small font-weight-bold text-dark">Customer*</label>
                                <select class="form-control form-control-sm" name="customer" id="customer" required>
                                    <option value="">Select</option>
                                </select>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Customer address*</label>
                                    <input type="text" id="customeraddress" name="customeraddress"
                                        class="form-control form-control-sm" readonly required>
                                </div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Customer contact*</label>
                                    <input type="text" id="customercontact" name="customercontact"
                                        class="form-control form-control-sm" readonly required>
                                </div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Discount %</label>
                                    <input type="number" id="discountpresentage" name="discountpresentage"
                                        class="form-control form-control-sm" value="25">
                                </div>
                            </div>
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold text-dark">Product*</label>
                                <select class="form-control form-control-sm" name="product" id="product" required>
                                    <option value="">Select</option>
                                    <?php if($resultproduct->num_rows > 0) {while ($rowproduct = $resultproduct-> fetch_assoc()) { ?>
                                    <option value="<?php echo $rowproduct['idtbl_product'] ?>">
                                        <?php echo $rowproduct['product_name'] ?></option>
                                    <?php }} ?>
                                </select>
                            </div>
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold text-dark">Supplier</label>
                                <input type="text" id="fieldsupplier" name="fieldsupplier"
                                    class="form-control form-control-sm" value="" readonly>
                            </div>
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold text-dark">Size</label>
                                <input type="text" id="fieldsize" name="fieldsize"
                                    class="form-control form-control-sm" value="" readonly>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Qty*</label>
                                    <input type="text" id="newqty" name="newqty" class="form-control form-control-sm"
                                        value="0" required>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Free Qty</label>
                                    <input type="text" id="freeqty" name="freeqty" class="form-control form-control-sm"
                                        value="0">
                                </div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Free Product</label>
                                    <input type="text" id="freeproductname" name="freeproductname"
                                        class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Sale Price</label>
                                    <input type="text" id="saleprice" name="saleprice"
                                        class="form-control form-control-sm" value="0" readonly>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <button type="button" id="formsubmit"
                                    class="btn btn-outline-primary btn-sm fa-pull-right"
                                    <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-plus"></i>&nbsp;Add
                                    Product</button>
                                <input name="submitBtn" type="submit" value="Save" id="submitBtn" class="d-none">
                            </div>
                            <input type="hidden" name="unitprice" id="unitprice" value="">
                            <input type="hidden" name="freeproductid" id="freeproductid" value="">
                        </form>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-9 col-xl-9">
                        <table class="table table-striped table-bordered table-sm small" id="tableorder">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th class="d-none">ProductID</th>
                                    <th class="d-none">Unitprice</th>
                                    <th class="d-none">Saleprice</th>
                                    <th class="text-center">Qty</th>
                                    <th class="">Free Product</th>
                                    <th class="d-none">Freeproductid</th>
                                    <th class="text-center">Free Qty</th>
                                    <th class="text-center">Total Qty</th>
                                    <th class="text-right">Sale Price</th>
                                    <th class="d-none">HideTotal</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <div class="row">
                            <div class="col-9 text-right">
                                <h6 class="">Subtotal</h6>
                            </div>
                            <div class="col text-right">
                                <h6 class="" id="divsubtotal">Rs. 0.00</h6>
                            </div>
                            <input type="hidden" id="hidetotalorder" value="0">
                        </div>
                        <div class="row">
                            <div class="col-9 text-right">
                                <h6 class="">Discount</h6>
                            </div>
                            <div class="col text-right">
                                <h6 class="" id="divdiscount">Rs. 0.00</h6>
                            </div>
                            <input type="hidden" id="hidediscount" value="0">
                        </div>
                        <div class="row">
                            <div class="col-9 text-right">
                                <h1 class="">Nettotal</h1>
                            </div>
                            <div class="col text-right">
                                <h1 class="" id="divtotal">Rs. 0.00</h1>
                            </div>
                            <input type="hidden" id="hidenettotalorder" value="0">
                        </div>
                        <hr>
                        <div class="form-group">
                            <label class="small font-weight-bold text-dark">Payment option</label><br>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="paymentoption1" name="paymentoption"
                                    class="custom-control-input" value="0" checked>
                                <label class="custom-control-label" for="paymentoption1">Cash on Delivery</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="paymentoption2" name="paymentoption"
                                    class="custom-control-input" value="1">
                                <label class="custom-control-label" for="paymentoption2">Credit</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="small font-weight-bold text-dark">Remark</label>
                            <textarea name="remark" id="remark" class="form-control form-control-sm"></textarea>
                        </div>
                        <div class="form-group mt-2">
                            <button type="button" id="btncreateorder"
                                class="btn btn-outline-primary btn-sm fa-pull-right"
                                <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-save"></i>&nbsp;Create
                                Order</button>
                        </div>
                        <div class="form-group mt-3 text-danger small">
                            <span class="badge badge-danger mr-2">&nbsp;&nbsp;</span> Stock quantity warning
                        </div>
                        <div id="errordiv">

                        </div>

                    </div>
                </div>
            </div>
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
            <div class="modal-body">
                <div class = "row">
                    <div class = "col-md-6">
                        <label>Customer name : <span id = "dcusname"></span></label>
                    </div>
                    <div class = "col-md-6">
                        <label>Customer Contact :  <span id = "dcuscontact">sss</span></label>
                    </div>
                </div>
                <table class="table table-striped table-bordered table-sm small mt-2" id="tableorderview">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th class="d-none">ProductID</th>
                            <th class="text-center"> Qty</th>
                            <th class=""> Free Product</th>
                            <th class="d-none"> Freeproductid</th>
                            <th class="text-center"> Free Qty</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

                <div class="row">
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
                        <h1 class="font-weight-600">Nettotal</h1>
                    </div>
                    <div class="col-3 text-right">
                        <h1 class="font-weight-600" id="divtotalview">Rs. 0.00</h1>
                    </div>
                    <div class="col-12">
                        <h6 class="title-style"><span>Remark Information</span></h6>
                    </div>
                    <div class="col-12">
                        <div id="remarkview"></div>
                    </div>
                </div>
                <button class="btn btn-primary btn-sm fa-pull-right" id="btnUpdate"><i
                        class="fa fa-save"></i>&nbsp;Update</button>
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
<!-- Modal Warning -->
<div class="modal fade" id="warningModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body bg-danger text-white text-center">
                <div id="warningdesc"></div>
            </div>
            <div class="modal-footer bg-danger rounded-0">
                <button type="button" class="btn btn-outline-light btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Day End Warning -->
<div class="modal fade" id="warningDayEndModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body bg-danger text-white text-center">
                <div id="viewmessage"></div>
            </div>
            <div class="modal-footer bg-danger rounded-0">
                <a href="dayend.php" class="btn btn-outline-light btn-sm">Go To Day End</a>
            </div>
        </div>
    </div>
</div>
<!-- Modal Track -->
<div class="modal fade" id="modaltrack" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <form action="process/trackingprocess.php" method="post" autocomplete="off">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="">Tracking Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formtracking">
                        <div class="form-group mb-1">
                            <label class="small font-weight-bold text-dark">Tracking Code*</label>
                            <input type="text" class="form-control form-control-sm" name="trackingcode"
                                id="trackingcode">
                        </div>
                        <div class="form-group mb-1">
                            <label class="small font-weight-bold text-dark">Url</label>
                            <input type="text" class="form-control form-control-sm" name="trackingurl" id="trackingurl"
                                required value="">
                        </div>
                        <div class="form-group mt-3">
                            <button type="submit" id="submitBtn"
                                class="btn btn-outline-primary btn-sm w-50 fa-pull-right"
                                <?php if($addcheck==0){echo 'disabled';} ?>><i
                                    class="far fa-save"></i>&nbsp;Add</button>
                            <!-- <input type="submit" id="hidesubmit" class="d-none"> -->
                        </div>
                        <input type="hidden" name="orderid" id="orderid" value="">
                    </form>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Modal Porder Edit -->
<div class="modal fade" id="modalporderedit" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Edit Order Date</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="process/editcusorderdate.php" method="post" id="formeditorder">
                    <div class="form-group mb-1">
                        <label class="small font-weight-bold text-dark">Order date*</label>
                        <input type="date" class="form-control form-control-sm" name="orderdate" id="orderdate"
                            required>
                        <input type="text" class="form-control form-control-sm d-none" name="editorderid"
                            id="editorderid">
                    </div>
                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-outline-primary btn-sm w-50 fa-pull-right"
                            <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Update</button>
                    </div>
                </form>
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
                <form action="process/statusorder.php" method="post">
                    <div class="form-group mb-1">
                        <label class="small font-weight-bold text-dark">Cancel Reason*</label>
                        <textarea type="text" class="form-control form-control-sm" name="cancelreason" id="cancelreason"
                            required rows="5"></textarea>
                    </div>
                    <div class="form-group mt-3">
                        <button type="submit" id="submitBtn" class="btn btn-outline-danger btn-sm px-4 fa-pull-right"
                            <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Cancel
                            Order</button>
                    </div>
                    <input type="hidden" name="recordID" id="recordID" value="">
                    <input type="hidden" name="type" id="type" value="4">
                </form>
            </div>
        </div>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
    var prodCount = 0;
    $(document).ready(function () {
        // checkdayendprocess();
        $("#helpername").select2();

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
                url: "scripts/customerporderlist.php",
                type: "POST", // you can use GET
            },
            "order": [
                [0, "desc"]
            ],
            "columns": [{
                    "data": "idtbl_porder"
                },
                {
                    "data": "orderdate"
                },
                {
                    "targets": -1,
                    "className": '',
                    "data": null,
                    "render": function (data, type, full) {
                        return 'PO0' + full['idtbl_porder'];
                    }
                },
                {
                    "data": "repname"
                },
                {
                    "data": "area"
                },
                {
                    "data": "cusname"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function (data, type, full) {
                        return parseFloat(full['subtotal']).toFixed(2);
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function (data, type, full) {
                        return parseFloat(full['disamount']).toFixed(2);
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function (data, type, full) {
                        return parseFloat(full['nettotal']).toFixed(2);
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-center',
                    "data": null,
                    "render": function (data, type, full) {
                        var html = '';
                        if (full['confirmstatus'] == 1) {
                            html += '<i class="fas fa-check text-success"></i>&nbsp;Confirm';
                        } else if (full['confirmstatus'] == 2) {
                            html += '<i class="fas fa-times text-danger"></i>&nbsp;Cancelled';
                        } else {
                            html += '<i class="fas fa-times text-danger"></i>&nbsp;Not Confirm';
                        }
                        return html;
                    }
                },
                {
                    "data": "trackingno"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function (data, type, full) {
                        var button = '';
                        button +=
                            '<button class="btn btn-outline-dark btn-sm btnview mr-1 " data-toggle="tooltip" data-placement="bottom" title="View PO Details" id="' +
                            full['idtbl_porder'] + '" name="' + full['confirmstatus'] +
                            '"><i class="far fa-eye"></i></button>';
                        if (usertype == 2 || usertype == 1) {
                            button +=
                                '<button class="btn btn-outline-secondary btn-sm btneditorder mr-1" id="' +
                                full['idtbl_porder'] +
                                '"><i class="fas fa-pen"></i></button>';
                        }
                        if (full['status'] == 2) {
                            button +=
                                '<button class="btn btn-secondary btn-sm btnreactive mr-1" id="' +
                                full['idtbl_porder'] +
                                '"><i class="fas fa-check"></i></button>';
                        }
                        if (full['status'] == 1) {
                            button +=
                                '<button class="btn btn-outline-primary btn-sm mr-1 btnprint" data-toggle="tooltip" data-placement="bottom" title="Print Order" id="' +
                                full['idtbl_porder'] +
                                '"><i class="fas fa-print"></i></button>';
                        }

                        if (full['callstatus'] == 0 && full['status'] == 1) {
                            button +=
                                '<button class="btn btn-danger btn-sm btncall mr-1" data-toggle="tooltip" data-placement="bottom" title="Call to customer" id="' +
                                full['idtbl_porder'] +
                                '"><i class="fas fa-phone"></i></button>';
                        } else if (full['status'] == 1) {
                            button +=
                                '<button class="btn btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="bottom" title="Call completed" ><i class="fas fa-phone"></i></button>';
                        }

                        if (full['status'] == 1) {
                            button +=
                                '<button class="btn btn-outline-secondary btn-sm btntracking mr-1" id="' +
                                full['idtbl_porder'] +
                                '"><i class="fas fa-map-marker-alt"></i></button>'
                        }

                        if (full['confirmstatus'] == 0 && full['status'] == 1) {
                            button +=
                                '<button class="btn btn-outline-orange btn-sm btnaccept mr-1" data-toggle="tooltip" data-placement="bottom" title="Not Accept Order" id="' +
                                full['idtbl_porder'] +
                                '"><i class="fas fa-times"></i></button>';
                        } else if (full['confirmstatus'] == 1 && full['status'] == 1) {
                            button +=
                                '<button class="btn btn-outline-success btn-sm mr-1" data-toggle="tooltip" data-placement="bottom" title="Accepted Order"><i class="fas fa-check"></i></button>';
                        }

                        // if(full['paystatus']==0 && full['status']==1){button+='<button class="btn btn-outline-danger btn-sm mr-1 btnpayment" data-toggle="tooltip" data-placement="bottom" title="Payment not Completed" id="'+full['idtbl_porder']+'"><i class="fas fa-money-bill-alt"></i></button>';}
                        // else if(full['status']==1){button+='<button class="btn btn-outline-success btn-sm mr-1 btnpaydone" data-toggle="tooltip" data-placement="bottom" title="Payment Completed" id="'+full['idtbl_porder']+'"><i class="fas fa-money-bill-alt"></i></button>';}

                        // if(full['shipstatus']==0 && full['status']==1){button+='<button class="btn btn-outline-danger btn-sm mr-1 btnship" data-toggle="tooltip" data-placement="bottom" title="Order not ship" id="'+full['idtbl_porder']+'"><i class="fas fa-dolly"></i></button>';}
                        // else if(full['status']==1){button+='<button class="btn btn-outline-success btn-sm mr-1" data-toggle="tooltip" data-placement="bottom" title="Order shipped"><i class="fas fa-dolly"></i></button>';}

                        // if(full['deliverystatus']==0 && full['status']==1){button+='<button class="btn btn-outline-danger btn-sm mr-1 btndelivery" data-toggle="tooltip" data-placement="bottom" title="Delivery not completed" id="'+full['idtbl_porder']+'"><i class="fas fa-truck"></i></button>';}
                        // else if(full['status']==1){button+='<button class="btn btn-outline-success btn-sm mr-1" data-toggle="tooltip" data-placement="bottom" title="Delivery completed"><i class="fas fa-truck"></i></button>';}

                        // if(full['deliverystatus']==0 && full['status']==1){button+='<button class="btn btn-outline-danger btn-sm mr-1 btncancel" data-toggle="tooltip" data-placement="bottom" title="Cancel order" id="'+full['idtbl_porder']+'"><i class="fas fa-window-close"></i></button><button class="btn btn-primary btn-sm btnreturn" id="'+full['idtbl_porder']+'"><i class="fas fa-redo-alt"></i></button>';}
                        if (full['deliverystatus'] == 0 && full['status'] == 1) {
                            button +=
                                '<button class="btn btn-outline-danger btn-sm mr-1 btncancel" data-toggle="tooltip" data-placement="bottom" title="Cancel order" id="' +
                                full['idtbl_porder'] +
                                '"><i class="fas fa-window-close"></i></button>';
                        }
                        return button;
                    }
                }
            ]
        });
        $('#dataTable tbody').on('click', '.btnpayment', function () {
            var r = confirm("Are you sure, Payment complete this order ? ");
            if (r == true) {
                var id = $(this).attr('id');
                var type = '1';
                statuschange(id, type);
            }
        });
        $('#dataTable tbody').on('click', '.btnship', function () {
            var r = confirm("Are you sure, Ship this order ? ");
            if (r == true) {
                var id = $(this).attr('id');
                var type = '2';
                statuschange(id, type);
            }
        });
        $('#dataTable tbody').on('click', '.btndelivery', function () {
            var r = confirm("Are you sure, Delivery complete this order ? ");
            if (r == true) {
                var id = $(this).attr('id');
                var type = '3';
                statuschange(id, type);
            }
        });
        $('#dataTable tbody').on('click', '.btntracking', function () {

            var id = $(this).attr('id');
            $('#orderid').val(id);
            // alert(id)
            $('#modaltrack').modal('show');


        });
        $('#dataTable tbody').on('click', '.btneditorder', function () {

            var id = $(this).attr('id');
            $('#editorderid').val(id);
            $('#modalporderedit').modal('show');


        });
        $('#dataTable tbody').on('click', '.btncall', function () {
            var r = confirm("Are you sure, customer call to customer ? ");
            if (r == true) {
                var id = $(this).attr('id');
                var type = '6';
                statuschange(id, type);
            }
        });
        $('#dataTable tbody').on('click', '.btnaccept', function () {
            var r = confirm("Are you sure, Accept this order ? ");
            if (r == true) {
                var id = $(this).attr('id');
                var type = '5';
                //alert(id);
                statuschange(id, type);
            }
        });
        $('#dataTable tbody').on('click', '.btncancel', function () {
            var r = confirm("Are you sure, Cancel this order ? ");
            if (r == true) {
                var id = $(this).attr('id');
                $('#recordID').val(id);
                $('#modalcancel').modal('show');
            }
        });

        $('.dpd1a').datepicker({
            uiLibrary: 'bootstrap4',
            autoclose: 'true',
            todayHighlight: true,
            startDate: 'today',
            format: 'yyyy-mm-dd'
        });

        // Customer part
        $('#area').change(function () {
            var areaID = $(this).val();

            $.ajax({
                type: "POST",
                data: {
                    areaID: areaID
                },
                url: 'getprocess/getasmlistaccoarea.php',
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

                    $('#repname').empty().append(html);
                }
            });


        });

        $('#repname').change(function () {
            var areaID = $('#area').val();
            var repId = $(this).val();

            if (repId == 7) {
                $("#directcustomerdiv").attr("hidden", false);
                $("#directcustomer").attr("required", true);

                $("#customeraddress").attr("readonly", false);
                $("#customercontact").attr("readonly", false);


                $("#customerdiv").attr("hidden", true);
                $("#customer").attr("required", false);
            } else {
                $("#directcustomer").attr("required", false);
                $("#directcustomerdiv").attr("hidden", true);

                $("#customeraddress").attr("readonly", true);
                $("#customercontact").attr("readonly", true);

                $("#customer").attr("required", true);
                $("#customerdiv").attr("hidden", false);


            }
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
                        html += objfirst[i].name;
                        html += '</option>';
                    });

                    $('#customer').empty().append(html);
                }
            });
        })
        // Prodcut part
        $('#product').change(function () {
            var productID = $(this).val();
            var customerID = $('#customer').val();
            $.ajax({
                type: "POST",
                data: {
                    productID: productID,
                    customerID: customerID
                },
                url: 'getprocess/getsalpriceaccoproductcustomer.php',
                success: function (result) { //alert(result);
                    var obj = JSON.parse(result);
                    $('#unitprice').val(obj.unitprice);
                    $('#saleprice').val(obj.saleprice);
                    $('#fieldsupplier').val(obj.suppliername);
                    $('#fieldsize').val(obj.commonname);

                    $('#newqty').focus();
                    $('#newqty').select();
                }
            });
        });

        $('#customer').change(function () {
            var customerID = $(this).val();

            $.ajax({
                type: "POST",
                data: {
                    customerID: customerID
                },
                url: 'getprocess/getcustomerlocationdetails.php',
                success: function (result) { //alert(result);
                    var obj = JSON.parse(result);
                    $('#customercontact').val(obj.phone);
                    $('#customeraddress').val(obj.address);
                }
            });
        });

        $("#newqty").keyup(function () {
            var qty = $(this).val();
            var productID = $('#product').val();

            $.ajax({
                type: "POST",
                data: {
                    productID: productID,
                    qty: qty
                },
                url: 'getprocess/getproductfreewtyaccoproductqty.php',
                success: function (result) { //alert(result);
                    var obj = JSON.parse(result);
                    $('#freeqty').val(obj.freecount);
                    $('#freeproductname').val(obj.productname);
                    $('#freeproductid').val(obj.productid);
                }
            });
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
                success: function (result) { //alert(result);
                    var obj = JSON.parse(result);

                    $('#divsubtotalview').html(obj.subtotal);
                    $('#divdiscountview').html(obj.disamount);
                    $('#divtotalview').html(obj.nettotalshow);
                    $('#remarkview').html(obj.remark);
                    $('#dcusname').html(obj.cusname);
                    $('#dcuscontact').html(obj.cuscontact);
                    $('#viewmodaltitle').html('Order No: PO-' + id);

                    var objfirst = obj.tablelist;
                    $.each(objfirst, function (i, item) {
                        //alert(objfirst[i].id);

                        $('#tableorderview > tbody:last').append('<tr><td>' +
                            objfirst[i].productname +
                            '</td><td class="d-none">' + objfirst[i].productid +
                            '</td><td class="text-center editnewqty">' +
                            objfirst[i].newqty + '</td><td class="">' +
                            objfirst[i].freeproduct +
                            '</td><td class="d-none">' + objfirst[i]
                            .freeproductid + '</td><td class="text-center">' +
                            objfirst[i].freeqty +
                            '</td><td class="text-right total">' + objfirst[i]
                            .total + '</td><td class="d-none">' + objfirst[i]
                            .unitprice + '</td></tr>');
                    });
                    if (confirmstatus == 1) {
                        $('#btnUpdate').html(
                            '<i class="far fa-save"></i>&nbsp;Already confirmed');
                        $('#btnUpdate').prop('disabled', true);
                    } else {
                        $('#btnUpdate').html('<i class="far fa-save"></i>&nbsp;Update');
                        $('#btnUpdate').prop('disabled', false);
                    }
                    $('#modalorderview').modal('show');
                }
            });
        });


        $('#tableorderview tbody').on('click', '.editnewqty', function (e) {
            var row = $(this);
            // var rowid = row.closest("tr").find('td:eq(0)').text();
            // var selectvalueone = $('.optionpiorityone' + rowid).val();
            // row.closest("tr").find('td:eq(7)').text(selectvalueone);

            e.preventDefault();
            e.stopImmediatePropagation();

            $this = $(this);
            if ($this.data('editing')) return;

            var val = $this.text();

            $this.empty();
            $this.data('editing', true);

            $('<input type="Text" class="form-control form-control-sm optionnewqty">').val(val)
                .appendTo($this);
            textremove('.optionnewqty', row);
        });

        //Text remove
        function textremove(classname, row) {
            $('#tableorderview tbody').on('keyup', classname, function (e) {
                if (e.keyCode === 13) {
                    $this = $(this);
                    var val = $this.val();
                    var td = $this.closest('td');
                    td.empty().html(val).data('editing', false);

                    var rowID = row.closest("td").parent()[0].rowIndex;
                    var unitprice = parseFloat(row.closest("tr").find('td:eq(7)').text());
                    var newqty = parseFloat(row.closest("tr").find('td:eq(2)').text());
                    var totnew = newqty * unitprice;

                    var showtotnew = addCommas(parseFloat(totnew).toFixed(2));
                    // var total = parseFloat(totrefill+totnew).toFixed(2);
                    // var showtotal = addCommas(total);

                    $('#tableorderview').find('tr').eq(rowID).find('td:eq(6)').text(showtotnew);
                    // $('#tableorderview').find('tr').eq(rowID).find('td:eq(11)').text(showtotal);

                    tabletotal1();
                }
            });
        }

        function tabletotal1() {
            var sum = 0;
            $(".total").each(function () {
                var cleansum = $(this).text().split(",").join("")
                sum += parseFloat(cleansum);
            });

            var showsum = addCommas(parseFloat(sum).toFixed(2));

            var discount = $("#divdiscountview").text();
            var cleandiscount = discount.split(",").join("")
            var netTot = sum - cleandiscount;
            // alert(netTot)
            var shownet = addCommas(parseFloat(netTot).toFixed(2));

            $('#divsubtotalview').html(showsum);
            $('#divtotalview').html(shownet);
        }

        $("#createorderform").keypress(function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                return false;
            }
        })
        $("#freeqty").keyup(function (event) {
            if (event.keyCode === 13) {
                $("#formsubmit").click();
            }
        });


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

        // Create order part
        $('#btnordercreate').click(function () {
            $('#modalcreateorder').modal('show');
            $('#modalcreateorder').on('shown.bs.modal', function () {
                $('#orderdate').trigger('focus');
            })
        });
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
        $('#orderdate').change(function () {
            $('#repname').focus();
        });
        $("#formsubmit").click(function () {
            if (!$("#createorderform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#submitBtn").click();
            } else {
                checkTarget()
            }
        });

        function checkTarget() {
            var productID = $('#product').val();
            var product = $("#product option:selected").text();
            var repname = $("#repname option:selected").text();
            var repID = $('#repname').val();
            var orderdate = $('#orderdate').val();

            $.ajax({
                type: 'POST',
                data: {
                    productID: productID,
                    repID: repID,
                    orderdate: orderdate
                },
                url: 'getprocess/checkemptarget.php',
                success: function (result) {
                    var obj = JSON.parse(result);
                    var val = obj.count

                    //Change below to if(val > 0)
                    if (true) {
                        prodCount++;
                        var discount = parseFloat($('#discountpresentage').val());
                        var productID = $('#product').val();
                        var product = $("#product option:selected").text();
                        var unitprice = parseFloat($('#unitprice').val());
                        var saleprice = parseFloat($('#saleprice').val());
                        var newqty = parseFloat($('#newqty').val());
                        var freeqty = parseFloat($('#freeqty').val());

                        var freeproductname = $('#freeproductname').val();
                        var freeproductid = $('#freeproductid').val();

                        var newtotal = parseFloat(saleprice * newqty);
                        var totalqty = parseFloat(freeqty + newqty);

                        var total = parseFloat(newtotal);
                        var showtotal = addCommas(parseFloat(total).toFixed(2));

                        $('#tableorder > tbody:last').append('<tr class="pointer"><td>'+ prodCount +'</td><td>' + product +
                            '</td><td class="d-none">' + productID +
                            '</td><td class="d-none">' + unitprice +
                            '</td><td class="d-none">' + saleprice +
                            '</td><td class="text-center">' + newqty + '</td><td class="">' +
                            freeproductname + '</td><td class="d-none">' + freeproductid +
                            '</td><td class="text-center">' + freeqty +
                            '</td><td class="text-center">' + totalqty +
                            '</td><td class="text-right">' + addCommas(saleprice) +
                            '</td><td class="total d-none">' + total +
                            '</td><td class="text-right">' + showtotal + '</td></tr>');

                        $('#product').val('');
                        $('#unitprice').val('');
                        $('#saleprice').val('');
                        $('#newqty').val('0');
                        $('#freeqty').val('0');
                        $('#freeproductname').val('');
                        $('#freeproductid').val('0');

                        var sum = 0;
                        $(".total").each(function () {
                            sum += parseFloat($(this).text());
                        });

                        var disvalue = (sum * discount) / 100;
                        var nettotal = sum - disvalue;

                        var showsum = addCommas(parseFloat(sum).toFixed(2));
                        var showdis = addCommas(parseFloat(disvalue).toFixed(2));
                        var shownettotal = addCommas(parseFloat(nettotal).toFixed(2));

                        $('#divsubtotal').html('Rs. ' + showsum);
                        $('#hidetotalorder').val(sum);
                        $('#divdiscount').html('Rs. ' + showdis);
                        $('#hidediscount').val(disvalue);
                        $('#divtotal').html('Rs. ' + shownettotal);
                        $('#hidenettotalorder').val(nettotal);
                        $('#product').focus();
                    } else {
                        var productname = $("#product option:selected").text();

                        $('#errordiv').empty().html(
                            "  <div class='alert alert-danger alert-dismissible fade show' role='alert'><h5 id = 'errormessage'></h5><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>"
                        )


                        $('#errormessage').html("There is no targets for employee '" + repname +
                            "' in the product " + productname)
                    }
                }
            })

        }

        $('#btncreateorder').click(function () { //alert('IN');
            var tbody = $("#tableorder tbody");

            if (tbody.children().length > 0) {
                jsonObj = [];
                $("#tableorder tbody tr").each(function () {
                    item = {}
                    $(this).find('td').each(function (col_idx) {
                        item["col_" + (col_idx + 1)] = $(this).text();
                    });
                    jsonObj.push(item);
                });
                // console.log(jsonObj);

                var discountpresentage = $('#discountpresentage').val();
                var orderdate = $('#orderdate').val();
                var remark = $('#remark').val();
                var repname = $('#repname').val();
                var area = $('#area').val();
                var customer = $('#customer').val();
                var directcustomer = $('#directcustomer').val();
                var total = $('#hidetotalorder').val();
                var discount = $('#hidediscount').val();
                var nettotal = $('#hidenettotalorder').val();
                var customeraddress = $('#customeraddress').val();
                var customercontact = $('#customercontact').val();
                var paymentoption = $("input[name='paymentoption']:checked").val();

                $.ajax({
                    type: "POST",
                    data: {
                        tableData: jsonObj,
                        orderdate: orderdate,
                        discountpresentage: discountpresentage,
                        total: total,
                        discount: discount,
                        nettotal: nettotal,
                        remark: remark,
                        repname: repname,
                        area: area,
                        customer: customer,
                        paymentoption: paymentoption,
                        customercontact: customercontact,
                        customeraddress: customeraddress,
                        directcustomer: directcustomer
                    },
                    url: 'process/customerporderprocess.php',
                    success: function (result) { //alert(result);
                        $('#modalcreateorder').modal('hide');
                        action(result);
                        location.reload();
                    }
                });
            }
        });

        $('#btnUpdate').click(function () {
            jsonObj = [];
            $("#tableorderview tbody tr").each(function () {
                item = {}
                $(this).find('td').each(function (col_idx) {
                    item["col_" + (col_idx + 1)] = $(this).text();
                });
                jsonObj.push(item);
            });

            // console.log(jsonObj);

            var poID = $('#hiddenpoid').val();


            var discount = $('#divdiscountview').text();
            var cleandiscount = discount.split(",").join("")

            var nettotal = $('#divtotalview').text();

            var clearnettotal = nettotal.split(",").join("")

            var total = $('#divsubtotalview').text();
            var cleartotal = total.split(",").join("")


            $.ajax({
                type: "POST",
                data: {
                    poID: poID,
                    tableData: jsonObj,
                    discount: cleandiscount,
                    nettotal: clearnettotal,
                    total: cleartotal,
                },
                url: 'process/updatecustomerpoprocess.php',
                success: function (result) { //alert(result);
                    action(result);
                    location.reload();
                }
            });

        });

        $('#tableorder').on('click', 'tr', function () {
            var r = confirm("Are you sure, You want to remove this product ? ");
            if (r == true) {
                $(this).closest('tr').remove();

                var sum = 0;
                $(".total").each(function () {
                    sum += parseFloat($(this).text());
                });

                var showsum = addCommas(parseFloat(sum).toFixed(2));

                $('#divtotal').html('Rs. ' + showsum);
                $('#hidetotalorder').val(sum);
                $('#product').focus();
            }
        });

        $("#discountpresentage").keyup(function () {
            if ($(this).val() != '') {
                var discount = parseFloat($(this).val());
            } else {
                var discount = 0;
            }

            var sum = 0;
            $(".total").each(function () {
                sum += parseFloat($(this).text());
            });

            var disvalue = (sum * discount) / 100;
            var nettotal = sum - disvalue;

            var showsum = addCommas(parseFloat(sum).toFixed(2));
            var showdis = addCommas(parseFloat(disvalue).toFixed(2));
            var shownettotal = addCommas(parseFloat(nettotal).toFixed(2));

            $('#divsubtotal').html('Rs. ' + showsum);
            $('#hidetotalorder').val(sum);
            $('#divdiscount').html('Rs. ' + showdis);
            $('#hidediscount').val(disvalue);
            $('#divtotal').html('Rs. ' + shownettotal);
            $('#hidenettotalorder').val(nettotal);
        });
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

    function checkdayendprocess() {
        $.ajax({
            type: "POST",
            data: {

            },
            url: 'getprocess/getstatuslastdayendinfo.php',
            success: function (result) { //alert(result);
                if (result == 1) {
                    $('#viewmessage').html("Can't create anything, because today transaction is end");
                    $('#warningDayEndModal').modal('show');
                } else if (result == 0) {
                    $('#viewmessage').html(
                        "Can't create anythind, because yesterday day end process end not yet.");
                    $('#warningDayEndModal').modal('show');
                }
            }
        });
    }

    function statuschange(id, type) { //alert(id);
        var cancelreason = '';

        $.ajax({
            type: "POST",
            data: {
                recordID: id,
                type: type,
                cancelreason: cancelreason
            },
            url: 'process/statuscusporder.php',
            success: function (result) { //alert(result);
                // action(result);
                $('#dataTable').DataTable().ajax.reload(null, false);
                // loaddatatable();
            }
        });
    }
</script>
<?php include "include/footer.php"; ?>
