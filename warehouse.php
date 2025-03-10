<?php
include "include/header.php";

$sql = "SELECT * FROM `tbl_porder` WHERE `confirmstatus` IN (1,0,2)";
$result = $conn->query($sql);

$sqlproduct = "SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1";
$resultproduct = $conn->query($sqlproduct);

$sqlbank = "SELECT `idtbl_bank`, `bankname` FROM `tbl_bank` WHERE `status`=1 AND `idtbl_bank`>1";
$resultbank = $conn->query($sqlbank);

$sqlreplist = "SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `tbl_user_type_idtbl_user_type`=7 AND `status`=1";
$resultreplist = $conn->query($sqlreplist);

$sqlarealist = "SELECT `idtbl_area`, `area` FROM `tbl_area` WHERE `status`=1";
$resultarealist = $conn->query($sqlarealist);

$sqlhelperlist = "SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `tbl_user_type_idtbl_user_type`=5 AND `status`=1";
$resulthelperlist = $conn->query($sqlhelperlist);

$sqlquerycompany = "SELECT `idtbl_query_company`, `name` FROM `tbl_query_company` WHERE `status`=1";
$resultquerycompany = $conn->query($sqlquerycompany);




$sqlcommonnames1 = "SELECT DISTINCT `common_name` FROM `tbl_product` WHERE `status`=1";
$resultcommonnames1 = $conn->query($sqlcommonnames1);
$sqlproduct1 = "SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1";
$resultproduct = $conn->query($sqlproduct1);

$sqlreplist1 = "SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `tbl_user_type_idtbl_user_type`=7 AND `status`=1";
$resultreplist1 = $conn->query($sqlreplist1);

$sqlcustomerlist1 = "SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE `status`=1";
$resultcustomerlist1 = $conn->query($sqlcustomerlist1);

$sqllocationlist1 = "SELECT `idtbl_locations`, `locationname` FROM `tbl_locations` WHERE `status`=1";
$resultlocationlist1 = $conn->query($sqllocationlist1);

$sqlarealist1 = "SELECT `idtbl_area`, `area` FROM `tbl_area` WHERE `status`=1 ORDER BY `area`";
$resultarealist1 = $conn->query($sqlarealist1);

$sqlhelperlist1 = "SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `tbl_user_type_idtbl_user_type`=8 AND `status`=1";
$resulthelperlist1 = $conn->query($sqlhelperlist1);

include "include/topnavbar.php";
?>
<style>
    .tableprint {
        table-layout: fixed;
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
                            <div class="page-header-icon"><i class="fas fa-warehouse"></i></div>
                            <span>Warehouse</span>
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
                                    <input type="text" id='hiddenqtyflag' value='0'>
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
                                                <th class="text-center">Ship</th>
                                                <th class="text-center">Delivery</th>
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
            <div class="modal-body">
                <table class="table table-striped table-bordered table-sm small" id="tableorderview">
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
                    <!-- <div class="col-9 text-right">
                        <h5 class="font-weight-600">Discount%</h5>
                    </div>
                    <div class="col-3 text-right">
                        <h5 class="font-weight-600" id="discountperview">Discount%: Rs. 0.00</h5>
                    </div>
                    <div class="col-9 text-right">
                        <h5 class="font-weight-600">PO Discount%</h5>
                    </div>
                    <div class="col-3 text-right">
                        <h5 class="font-weight-600" id="discountperviewpo">PO Discount%: Rs. 0.00</h5>
                    </div> -->
                    <div class="col-9 text-right">
                        <h5 class="font-weight-600">Total Discount</h5>
                    </div>
                    <div class="col-3 text-right">
                        <h5 class="font-weight-600" id="divdiscountview">Rs. 0.00</h5>
                    </div>
                    <div class="col-9 text-right">
                        <h5 class="font-weight-600">Total PO Discount</h5>
                    </div>
                    <div class="col-3 text-right">
                        <h5 class="font-weight-600" id="podivdiscountviewamount">Rs. 0.00</h5>
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
            </div>
        </div>
    </div>
</div>
<!-- Modal Invoice Create -->
<div class="modal fade" id="modalinvoicecreate" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h6 class="modal-title" id="viewmodaltitle"></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3">
                        <form id="hiddenform">
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold text-dark">Order Date*</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control dpd1a" placeholder="" name="orderdate"
                                        id="orderdate" value="" readonly>
                                    <div class="input-group-append">
                                        <span class="btn btn-light border-gray-500"><i
                                                class="far fa-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold text-dark">Rep Name*</label>
                                <select class="form-control form-control-sm" name="repname" id="repname" required>
                                    <option value="">Select</option>
                                </select>
                            </div>
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold text-dark">Area*</label>
                                <select class="form-control form-control-sm" name="area" id="area" required>
                                    <option value="">Select</option>
                                </select>
                            </div>
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold text-dark">Location*</label>
                                <select class="form-control form-control-sm" name="location" id="location" required>
                                    <option value="">Select</option>
                                </select>
                            </div>
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold text-dark">Customer*</label>
                                <select class="form-control form-control-sm" name="customer" id="customer" required>
                                    <option value="">Select</option>
                                </select>
                            </div>
                            <!-- <div class="form-group mb-1">
                                <label class="small font-weight-bold text-dark">Query Company*</label>
                                <select class="form-control form-control-sm" name="company" id="company" required>
                                    <option value="">Select</option>
                                    <?php if ($resultquerycompany->num_rows > 0) {
                                        while ($row = $resultquerycompany->fetch_assoc()) { ?>
                                    <option value="<?php echo $row['idtbl_query_company'] ?>">
                                        <?php echo $row['name'] ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold text-dark">Tracking number*</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" placeholder="" name="trackingnumber"
                                        id="trackingnumber" value="" required>
                                </div>
                                <input id = "hiddensubmitbtn" class = "d-none" type="submit">
                            </div> -->

                        </form>
                    </div>

                    <div class="col-sm-12 col-md-12 col-lg-9 col-xl-9">
                        <table class="table table-striped table-bordered table-sm small" id="invoicetable">
                            <thead>
                                <tr>
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
                                <h5 class="font-weight-600">Subtotal</h5>
                            </div>
                            <div class="col-3 text-right">
                                <h5 class="font-weight-600" id="divsubtotal">Subtotal: Rs. 0.00</h5>
                            </div>
                            <!-- <div class="col-9 text-right">
                                <h5 class="font-weight-600">Discount%</h5>
                            </div>
                            <div class="col-3 text-right">
                                <h5 class="font-weight-600" id="divdiscountpercentage">Subtotal: Rs. 0.00</h5>
                            </div> -->
                            <div class="col-9 text-right">
                                <h5 class="font-weight-600">Discount</h5>
                            </div>
                            <div class="col-3 text-right">
                                <h5 class="font-weight-600" id="divdiscount">Rs. 0.00</h5>
                            </div>
                            <div class="col-9 text-right">
                                <h5 class="font-weight-600">PO Discount</h5>
                            </div>
                            <div class="col-3 text-right">
                                <h5 class="font-weight-600" id="divdiscountpo">Rs. 0.00</h5>
                            </div>
                            <div class="col-9 text-right">
                                <h1 class="font-weight-600">Nettotal</h1>
                            </div>
                            <div class="col-3 text-right">
                                <h1 class="font-weight-600" id="divtotal">Rs. 0.00</h1>
                            </div>
                            <input type="hidden" id="hidetotalorder" value="0">
                            <input type="hidden" id="hideorderid" value="0">
                        </div>
                        <hr>
                        <div class="form-group">
                            <label class="small font-weight-bold text-dark">Remark</label>
                            <textarea name="remark" id="remark" class="form-control form-control-sm"></textarea>
                        </div>
                        <div class="form-group mt-2">
                            <button type="button" id="btncreateinvoice"
                                class="btn btn-outline-primary btn-sm fa-pull-right"
                                <?php if ($addcheck == 0) {
                                                                                                                                    echo 'disabled';
                                                                                                                                } ?>><i class="fas fa-save"></i>&nbsp;Create
                                Invoice</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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
                    <div class="col-12">
                        <form id="createorderform" autocomplete="off">
                            <div class="row">

                                <div class="form-group mb-2 col-3">
                                    <label class="small font-weight-bold text-dark">Order Date*</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control dpd1a" placeholder="" name="orderdate1"
                                            id="orderdate1" required>
                                        <div class="input-group-append">
                                            <span class="btn btn-light border-gray-500"><i
                                                    class="far fa-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-2 col-3">
                                    <label class="small font-weight-bold text-dark">Rep Name*</label>
                                    <select class="form-control form-control-sm" name="repname2" id="repname2" required>
                                        <option value="">Select</option>
                                        <?php if ($resulthelperlist1->num_rows > 0) {
                                            while ($rowemplist1 = $resulthelperlist1->fetch_assoc()) { ?>
                                        <option value="<?php echo $rowemplist1['idtbl_employee'] ?>">
                                            <?php echo $rowemplist1['name'] ?></option>
                                        <?php }
                                        } ?>
                                    </select>
                                </div>
                                <div class="form-group mb-2 col-3">
                                    <label class="small font-weight-bold text-dark">Location*</label>
                                    <select class="form-control form-control-sm" name="location1" id="location1"
                                        required>
                                        <option value="">Select</option>
                                        <?php if ($resultlocationlist1->num_rows > 0) {
                                            while ($rowloclist = $resultlocationlist1->fetch_assoc()) { ?>
                                        <option value="<?php echo $rowloclist['idtbl_locations'] ?>">
                                            <?php echo $rowloclist['locationname'] ?></option>
                                        <?php }
                                        } ?>
                                    </select>
                                </div>
                                <div class="form-group mb-2 col-3">
                                    <label class="small font-weight-bold text-dark">Area*</label>
                                    <select class="form-control form-control-sm" name="area1" id="area1" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>

                                <div class="form-group mb-2 col-3" id="directcustomerdiv" hidden>
                                    <label class="small font-weight-bold text-dark">Customers*</label>
                                    <input type="text" placeholder="Enter customer name"
                                        class="form-control form-control-sm" name="directcustomer" id="directcustomer"
                                        readonly></input>
                                </div>

                                <div class="form-group mb-2 col-3" id="customerdiv">
                                    <label class="small font-weight-bold text-dark">Customer*</label>
                                    <select class="form-control form-control-sm" name="customer" id="customer" readonly>
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <div class="form-group mb-2 col-3">
                                    <label class="small font-weight-bold text-dark">Customer address*</label>
                                    <input type="text" id="customeraddress" name="customeraddress"
                                        class="form-control form-control-sm" readonly>
                                </div>
                                <div class="form-group mb-2 col-3">
                                    <label class="small font-weight-bold text-dark">Customer contact*</label>
                                    <input type="text" id="customercontact" name="customercontact"
                                        class="form-control form-control-sm" readonly>
                                </div>

                                <div class="form-group mb-2 col-3">
                                    <label class="small font-weight-bold text-dark">Discount %</label>
                                    <input type="number" id="discountpresentage" name="discountpresentage"
                                        class="form-control form-control-sm" value="25">
                                </div>
                                <div class="form-group mb-2 col-3">
                                    <label class="small font-weight-bold text-dark">Common name*</label>
                                    <select class="form-control form-control-sm" name="productcommonname"
                                        id="productcommonname">
                                        <option value="">Select</option>
                                        <?php if ($resultcommonnames1->num_rows > 0) {
                                            while ($rowcommonname = $resultcommonnames1->fetch_assoc()) { ?>
                                        <option value="<?php echo $rowcommonname['common_name'] ?>">
                                            <?php echo $rowcommonname['common_name'] ?></option>
                                        <?php }
                                        } ?>
                                    </select>
                                </div>
                                <div class="form-group mb-2 col-3">
                                    <label class="small font-weight-bold text-dark">Product*</label>
                                    <select class="form-control form-control-sm" name="product" id="product" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <!-- <div class="form-group mb-2 col-3 d-none">
                                    <label class="small font-weight-bold text-dark">Supplier</label>
                                    <input type="text" id="fieldsupplier" name="fieldsupplier" class="form-control form-control-sm" value="" readonly>
                                </div>
                                <div class="form-group mb-2 col-3 d-none">
                                    <label class="small font-weight-bold text-dark">Size</label>
                                    <input type="text" id="fieldsize" name="fieldsize" class="form-control form-control-sm" value="" readonly>
                                </div> -->
                                <div class="form-row mb-1 col-3">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Qty*</label>
                                        <input type="text" id="newqty" name="newqty"
                                            class="form-control form-control-sm" value="0" required>
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Free Qty</label>
                                        <input type="text" id="freeqty" name="freeqty"
                                            class="form-control form-control-sm" value="0">
                                    </div>
                                </div>
                                <div class="form-group mb-1 col-3">
                                    <label class="small font-weight-bold text-dark">Free Product</label>
                                    <input type="text" id="freeproductname" name="freeproductname"
                                        class="form-control form-control-sm" readonly>
                                </div>
                                <div class="form-group mb-1 col-3">
                                    <label class="small font-weight-bold text-dark">Sale Price</label>
                                    <input type="text" id="saleprice" name="saleprice"
                                        class="form-control form-control-sm" value="0" readonly>
                                </div>
                                <div class="form-group mb-2 col-3">
                                    <label class="small font-weight-bold text-dark">PO Discount %</label>
                                    <input type="number" id="discountpo" name="discountpo"
                                        class="form-control form-control-sm" value="0">
                                </div>
                                <div class="form-group mt-4 col-3">
                                    <button type="button" id="formsubmit" class="btn btn-outline-primary btn-sm"
                                        <?php if ($addcheck == 0) {
                                                                                                                        echo 'disabled';
                                                                                                                    } ?>><i class="fas fa-plus"></i>&nbsp;Add
                                        Product</button>
                                    <input name="submitBtn" type="submit" value="Save" id="submitBtn" class="d-none">
                                </div>
                                <input type="hidden" name="unitprice" id="unitprice" value="">
                                <input type="hidden" name="recordOption" id="recordOption" value="1">
                                <input type="hidden" name="freeproductid" id="freeproductid" value="">
                                <input type="hidden" name="hiddencustomertype" id="hiddencustomertype" value="">
                                <input type="hidden" name="recordID" id="recordID" value="">
                        </form>
                    </div>
                </div>
                <div class="col-12 mt-2">
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
                            <h6 class="" id="divsubtotal1">Rs. 0.00</h6>
                        </div>
                        <input type="hidden" id="hidetotalorder1" value="0">
                    </div>
                    <div class="row">
                        <div class="col-9 text-right">
                            <h6 class="">Discount</h6>
                        </div>
                        <div class="col text-right">
                            <h6 class="" id="divdiscount1">Rs. 0.00</h6>
                        </div>
                        <input type="hidden" id="hidediscount1" value="0">
                    </div>
                    <div class="row">
                        <div class="col-9 text-right">
                            <h6 class="">PO Discount</h6>
                        </div>
                        <div class="col text-right">
                            <h6 class="" id="divdiscountPO1">Rs. 0.00</h6>
                        </div>
                        <input type="hidden" id="hidediscountPO1" value="0">
                    </div>
                    <div class="row">
                        <div class="col-9 text-right">
                            <h1 class="">Nettotal</h1>
                        </div>
                        <div class="col text-right">
                            <h1 class="" id="divtotal1">Rs. 0.00</h1>
                        </div>
                        <input type="hidden" id="hidenettotalorder1" value="0">
                    </div>
                    <hr>
                    <div class='row'>
                        <div class="form-group col-4">
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
                        <div class="form-group col-8">
                            <label class="small font-weight-bold text-dark">Remark</label>
                            <textarea name="remark" id="remark" class="form-control form-control-sm"></textarea>
                        </div>
                    </div>


                    <div class="form-group mt-2">
                        <button type="button" id="btncreateorder" class="btn btn-outline-primary btn-sm fa-pull-right"
                            <?php if ($addcheck == 0) {
                                                                                                                            echo 'disabled';
                                                                                                                        } ?>><i class="fas fa-save"></i>&nbsp;Create
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
<?php include "include/footerscripts.php"; ?>
<script>
    var prodCount = 0;
    $(document).ready(function () {
        checkdayendprocess();
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

        $('#dataTable').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "scripts/warehouseporderlist.php",
                type: "POST", // you can use GET
            },
            "order": [
                [0, "desc"]
            ],
            "columns": [{
                    "data": "tbl_porder_idtbl_porder"
                },
                {
                    "data": "orderdate"
                },
                {
                    "targets": -1,
                    "className": '',
                    "data": null,
                    "render": function (data, type, full) {
                        return 'PO0' + full['tbl_porder_idtbl_porder'];
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
                    "targets": -1,
                    "className": 'text-center',
                    "data": null,
                    "render": function (data, type, full) {
                        var html = '';
                        if (full['shipstatus'] == 1) {
                            html += '<i class="fas fa-check text-success"></i>&nbsp;Shipped';
                        } else {
                            html += '<i class="fas fa-times text-danger"></i>&nbsp;Not Shipped';
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
                        if (full['deliverystatus'] == 1) {
                            html += '<i class="fas fa-check text-success"></i>&nbsp;Delivered';
                        } else {
                            html +=
                                '<i class="fas fa-times text-danger"></i>&nbsp;Not Delivered';
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
                            '<button class="btn btn-outline-dark btn-sm btnview mr-1" id="' +
                            full['tbl_porder_idtbl_porder'] +
                            '"><i class="far fa-eye"></i></button>';
                        //  if (usertype == 2 || usertype == 1) {
                        button +=
                            '<button class="btn btn-outline-secondary btn-sm btneditorder mr-1" id="' +
                            full['tbl_porder_idtbl_porder'] +
                            '"><i class="fas fa-pen"></i></button>';
                        //  }
                        // button+='<button class="btn btn-outline-primary btn-sm mr-1 btnprint" data-toggle="tooltip" data-placement="bottom" title="Print Order" id="'+full['idtbl_porder']+'"><i class="fas fa-print"></i></button>';
                        button +=
                            '<button class="btn btn-outline-success btn-sm mr-1" data-toggle="tooltip" data-placement="bottom" title="Accepted Order"><i class="fas fa-check"></i></button>';
                        button +=
                            '<button class="btn btn-outline-primary btn-sm mr-1 btninvoice" data-toggle="tooltip" data-placement="bottom" title="Create Invoice" id="' +
                            full['tbl_porder_idtbl_porder'] +
                            '"><i class="far fa-file-alt"></i></button>';

                        return button;
                    }
                }
            ]
        });

        $('#btnordercreate').click(function () {
            $('#modalcreateorder').modal('show');
            $('#modalcreateorder').on('shown.bs.modal', function () {
                $('#orderdate1').trigger('focus');
            })
        });

        // $('#orderdate1').change(function() {
        //     $('#repnam2').focus();
        // });
        $('#dataTable tbody').on('click', '.btneditorder', function () {
            recordID = $('#recordOption').val();
            // alert(recordID);
            var id = $(this).attr('id');
            // alert(id);
            // $('#editorderid').val(id);
            $('#modalcreateorder').modal('show');
            $.ajax({
                type: "POST",
                data: {
                    recordID: id
                },
                url: 'getprocess/getwerehouseedit.php',
                success: function (result) { //alert(result);
                    //  console.log(result);
                    var obj = JSON.parse(result);
                    // alert(obj.name);
                    // alert(obj.name2);
                    //   $('#formsubmit').attr("disabled", true);
                    $('#recordID').val(obj.id);
                    $('#orderdate1').val(obj.orderdate);
                    $('#repname2').val(obj.idtbl_employee);
                    category(obj.idtbl_employee, obj.idtbl_area);
                    $('#discountpresentage').val(obj.discount);
                    $('#discountpo').val(obj.podiscount);
                    if (obj.idtbl_employee == 7) {
                        $("#directcustomerdiv").attr("hidden", false);
                        $("#directcustomer").attr("required", true);
                        $("#customeraddress").attr("readonly", false);
                        $("#customercontact").attr("readonly", false);
                        $("#customerdiv").attr("hidden", true);
                        $("#customer").attr("required", false);
                        $('#directcustomer').val(obj.name2);
                        $('#customercontact').val(obj.phone);
                        $('#customeraddress').val(obj.address);
                        $('#repname2').val(obj.idtbl_employee);

                    } else {
                        $("#directcustomer").attr("required", false);
                        $("#directcustomerdiv").attr("hidden", true);

                        $("#customeraddress").attr("readonly", true);
                        $("#customercontact").attr("readonly", true);

                        $("#customer").attr("required", true);
                        $("#customerdiv").attr("hidden", false);
                        selectcustomer(obj.idtbl_employee, obj.idtbl_area, obj.name);

                    };
                    $('#productcommonname').val(obj.address);
                    $('#location1').val(obj.idtbl_locations);
                    $('#customer').val(obj.name);

                    $('#remark').val(obj.remark);

                    if (obj.payfullhalf == 0) {
                        $('#paymentoption1').prop('checked', true);
                    } else {
                        $('#paymentoption2').prop('checked', true);
                    }
                    $('#recordOption').val('2');
                    $('#btncreateorder').html('<i class="far fa-save"></i>&nbsp;Update');

                    $.ajax({
                        type: "POST",
                        data: {
                            recordID: id
                        },
                        url: 'getprocess/geteditwarehousetable.php',
                        success: function (data) {
                            records = JSON.parse(data);
                            updateTable(records);

                        }


                    });


                }
            });


        });

        $('#area1').change(function () {
            var repId = $('#repname2').val();
            var areaID = $(this).val();


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
        $('#repname2').change(function () {
            var areaID = $('#area1').val();
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
                }
            });
        })

        $('#productcommonname').change(function () {
            var productcommonname = $('#productcommonname option:selected').val();
            var value = '';

            $.ajax({
                type: "POST",
                data: {
                    productcommonname: productcommonname
                },
                url: 'getprocess/getproductsaccocommonname.php',
                success: function (result) { // alert(result);
                    var objfirst = JSON.parse(result);
                    var html1 = '';
                    html1 += '<option value="">Select</option>';
                    $.each(objfirst, function (i, item) {
                        // alert(objfirst[i].id);
                        html1 += '<option value="' + objfirst[i].id + '">';
                        html1 += objfirst[i].name;
                        html1 += '</option>';
                    });

                    $('#product').empty().append(html1);

                    if (value != '') {
                        $('#size').val(value);
                    }
                }
            });

        })

        $('#product').change(function () {
            var productID = $(this).val();
            var customerID = $('#customer').val();
            var customerType = $('#hiddencustomertype').val();
            $.ajax({
                type: "POST",
                data: {
                    productID: productID,
                    customerID: customerID,
                    customerType: customerType
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
            var repname = $("#repname2 option:selected").text();
            var repID = $('#repname2').val();
            var orderdate = $('#orderdate1').val();

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

            $('#tableorder > tbody:last').append('<tr class="pointer"><td>' + prodCount + '</td><td>' +
                product +
                '</td><td class="d-none">' + productID +
                '</td><td class="d-none">' + unitprice +
                '</td><td class="d-none">' + saleprice +
                '</td><td class="text-center">' + newqty + '</td><td class="">' +
                freeproductname + '</td><td class="d-none">' + freeproductid +
                '</td><td class="text-center">' + freeqty +
                '</td><td class="text-center">' + totalqty +
                '</td><td class="text-right">' + addCommas(saleprice.toFixed(2)) +
                '</td><td class="total1 d-none">' + total +
                '</td><td class="text-right">' + showtotal + '</td><td class="d-none">' + saleprice +
                '</td><td class="d-none"></td></tr>');

            $('#product').val('');
            $('#unitprice').val('');
            $('#saleprice').val('');
            $('#newqty').val('0');
            $('#freeqty').val('0');
            $('#freeproductname').val('');
            $('#freeproductid').val('0');

            var sum = 0;
            $(".total1").each(function () {
                sum += parseFloat($(this).text());
            });
            var discount = parseFloat($('#discountpresentage').val());
            var discountpo = parseFloat($('#discountpo').val());
            var disvalue = (sum * discount) / 100;
            var nettotal = sum - disvalue;
            var disvaluepo = (nettotal * discountpo) / 100;
            var nettotal = nettotal - disvaluepo;

            var showsum = addCommas(parseFloat(sum).toFixed(2));
            var showdis = addCommas(parseFloat(disvalue).toFixed(2));
            var showdispo = addCommas(parseFloat(disvaluepo).toFixed(2));
            var shownettotal = addCommas(parseFloat(nettotal).toFixed(2));

            $('#divsubtotal1').html('Rs. ' + showsum);
            $('#hidetotalorder1').val(sum);
            $('#divdiscount1').html('Rs. ' + showdis);
            $('#divdiscountPO1').html('Rs. ' + showdispo);
            $('#hidediscount1').val(disvalue);
            $('#hidediscountPO1').val(disvaluepo);
            $('#divtotal1').html('Rs. ' + shownettotal);
            $('#hidenettotalorder1').val(nettotal);
            $('#product').focus();
        }

        $('#tableorder').on('click', 'tr', function () {
            var r = confirm("Are you sure, You want to remove this product ? ");
            if (r == true) {
                $(this).closest('tr').remove();

                var sum = 0;
                $(".total1").each(function () {
                    sum += parseFloat($(this).text());
                });
                var discount = parseFloat($('#discountpresentage').val());
                var discountpo = parseFloat($('#discountpo').val());
                var disvalue = (sum * discount) / 100;
                var nettotal = sum - disvalue;
                var disvaluepo = (nettotal * discountpo) / 100;
                var nettotal = nettotal - disvaluepo;

                var showsum = addCommas(parseFloat(sum).toFixed(2));
                var showdis = addCommas(parseFloat(disvalue).toFixed(2));
                var showdispo = addCommas(parseFloat(disvaluepo).toFixed(2));
                var shownettotal = addCommas(parseFloat(nettotal).toFixed(2));

                $('#divsubtotal1').html('Rs. ' + showsum);
                $('#hidetotalorder1').val(sum);
                $('#divdiscount1').html('Rs. ' + showdis);
                $('#divdiscountPO1').html('Rs. ' + showdispo);
                $('#hidediscount1').val(disvalue);
                $('#hidediscountPO1').val(disvaluepo);
                $('#divtotal1').html('Rs. ' + shownettotal);
                $('#hidenettotalorder1').val(nettotal);
                $('#product').focus();
            }
        });

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
                console.log(jsonObj);
                jsonObj = JSON.stringify(jsonObj);
                var discountpresentage = $('#discountpresentage').val();
                var orderdate = $('#orderdate').val();
                var remark = $('#remark').val();
                var repname = $('#repname').val();
                var area = $('#area').val();
                var location = $('#location').val();
                var customer = $('#customer').val();
                var directcustomer = $('#directcustomer').val();
                var total = $('#hidetotalorder1').val();
                var discount = $('#hidediscount1').val();
                var podiscount = $('#discountpo').val();
                var podiscountamount = $('#hidediscountPO1').val();
                var nettotal = $('#hidenettotalorder1').val();
                var customeraddress = $('#customeraddress').val();
                var customercontact = $('#customercontact').val();
                var paymentoption = $("input[name='paymentoption']:checked").val();
                var recordOption = $('#recordOption').val();
                var recordID = $('#recordID').val();
                // console.log(discountpresentage + ',' + repname + ',' + total + ',' + discount + ',' + nettotal + ',' + recordOption + ',' + recordID + ',' + podiscount + ',' + podiscountamount);
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
                        directcustomer: directcustomer,
                        recordOption: recordOption,
                        recordID: recordID,
                        podiscountamount: podiscountamount,
                        podiscount: podiscount

                    },
                    url: 'process/warehouseprocess.php',
                    success: function (result) { //alert(result);
                        $('#modalcreateorder').modal('hide');
                        action(result);
                        setTimeout(function () {
                            window.location.reload();

                        }, 1500);
                    }
                });
            }
        });

        $('#dataTable tbody').on('click', '.btninvoice', function () {
            $('#hiddenqtyflag').val(0)
            var id = $(this).attr('id');
            $('#hideorderid').val(id);
            $.ajax({
                type: "POST",
                data: {
                    orderID: id
                },
                url: 'getprocess/getorderdetailaccoorderid.php',
                success: function (result) { //alert(result);
                    var obj = JSON.parse(result);
                    $('#orderdate').val(obj.orderdate);

                    let htmlrep = '';
                    htmlrep += '<option value="' + obj.idtbl_employee + '">' + obj.repname +
                        '</option>'
                    $('#repname').empty().append(htmlrep);

                    let htmlarea = '';
                    htmlarea += '<option value="' + obj.idtbl_area + '">' + obj.area +
                        '</option>'
                    $('#area').empty().append(htmlarea);

                    let htmllocation = '';
                    htmllocation += '<option value="' + obj.idtbl_locations + '">' + obj
                        .locationname +
                        '</option>'
                    $('#location').empty().append(htmllocation);

                    let htmlcustomer = '';
                    htmlcustomer += '<option value="' + obj.idtbl_customer + '">' + obj
                        .cusname + '</option>'
                    $('#customer').empty().append(htmlcustomer);

                    $('#divsubtotal').html(addCommas(parseFloat(obj.subtotal).toFixed(2)));
                    $('#divdiscount').html(addCommas(parseFloat(obj.disamount).toFixed(2)));
                    $('#divdiscountpo').html(addCommas(parseFloat(obj.po_amount).toFixed(
                        2)));
                    $('#divdiscountpercentage').html(obj.discount + '%');
                    $('#divtotal').html(addCommas(parseFloat(obj.nettotal).toFixed(2)));
                    $('#hidetotalorder').val(obj.nettotal);
                    $('#remark').val(obj.remark);

                    $('#invoicetable > tbody:last').empty();
                    var objfirst = obj.datainfo;
                    $.each(objfirst, function (i, item) {
                        //alert(objfirst[i].id);

                        let saleprice = parseFloat(objfirst[i].saleprice);
                        let qty = parseFloat(objfirst[i].qty);
                        let freeqty = parseFloat(objfirst[i].freeqty);
                        let totqty = parseFloat(qty + freeqty);

                        let itemtotal = parseFloat(saleprice * qty).toFixed(2);

                        $('#invoicetable > tbody:last').append('<tr><td>' +
                            objfirst[i].product + '</td><td class="d-none">' +
                            objfirst[i].productid + '</td><td class="d-none">' +
                            objfirst[i].unitprice + '</td><td class="d-none">' +
                            objfirst[i].saleprice +
                            '</td><td class="text-center editnewqty">' +
                            objfirst[i].qty + '</td><td class="">' + objfirst[i]
                            .freeproduct + '</td><td class="d-none">' +
                            objfirst[i].freeproductid +
                            '</td><td class="text-center editfreeqty">' +
                            objfirst[i].freeqty +
                            '</td><td class="text-center">' + totqty +
                            '</td><td class="text-right">' + addCommas(
                                parseFloat(objfirst[i].saleprice).toFixed(2)) +
                            '</td><td class="total d-none">' + itemtotal +
                            '</td><td class="text-right">' + addCommas(
                                itemtotal) + '</td></tr>');

                        checkStock(objfirst[i].productid, objfirst[i].qty)

                    });

                    $('#modalinvoicecreate').modal('show');

                }
            });
        });
        $('#invoicetable tbody').on('click', '.editnewqty', function (e) {
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
        $('#invoicetable tbody').on('click', '.editfreeqty', function (e) {
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

            $('<input type="Text" class="form-control form-control-sm optionfreeqty">').val(val)
                .appendTo($this);
            textremove('.optionfreeqty', row);
        });

        $('#btncreateinvoice').click(function () { //alert('IN');
            if (!$("#hiddenform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hiddensubmitbtn").click();
            } else {
                var qtystatus = $('#hiddenqtyflag').val();
                if (qtystatus == 1) {
                    alert('There is not enough stock available to invoice some products')
                    return;
                }
                var tbody = $("#invoicetable tbody");

                if (tbody.children().length > 0) {
                    jsonObj = [];
                    $("#invoicetable tbody tr").each(function () {
                        item = {}
                        $(this).find('td').each(function (col_idx) {
                            item["col_" + (col_idx + 1)] = $(this).text();
                        });
                        jsonObj.push(item);
                    });
                    console.log(jsonObj);
                    jsonObj = JSON.stringify(jsonObj);
                    var orderdate = $('#orderdate').val();
                    var remark = $('#remark').val();
                    var repname = $('#repname').val();
                    var area = $('#area').val();
                    var location = $('#location').val();
                    var customer = $('#customer').val();
                    var total = $('#hidetotalorder').val();
                    var orderID = $('#hideorderid').val();
                    // var companyId = $('#company').val();
                    // var trackingnumber = $('#trackingnumber').val();

                    $.ajax({
                        type: "POST",
                        data: {
                            tableData: jsonObj,
                            orderdate: orderdate,
                            total: total,
                            remark: remark,
                            repname: repname,
                            area: area,
                            location: location,
                            customer: customer,
                            orderID: orderID,
                            // companyId: companyId,
                            // trackingnumber: trackingnumber
                        },
                        url: 'process/createinvoiceaccoporderprocess.php',
                        success: function (result) { //alert(result);
                            $('#modalinvoicecreate').modal('hide');
                            action(result);
                            // location.reload();
                        }
                    });
                }
            }
        });

        $('.dpd1a').datepicker({
            uiLibrary: 'bootstrap4',
            autoclose: 'true',
            todayHighlight: true,
            startDate: 'today',
            format: 'yyyy-mm-dd'
        });

        // Order view part
        $('#dataTable tbody').on('click', '.btnview', function () {
            var id = $(this).attr('id');
            //  alert(id);
            $.ajax({
                type: "POST",
                data: {
                    orderID: id
                },
                url: 'getprocess/getcusorderlistaccoorderid.php',
                success: function (result) { //alert(result);
                    var obj = JSON.parse(result);
                    console.log(obj);
                    $('#divsubtotalview').html(obj.subtotal);
                    $('#divdiscountview').html(obj.disamount);
                    $('#podivdiscountviewamount').html(obj.po_amount);
                    $('#divtotalview').html(obj.nettotalshow);
                    $('#remarkview').html(obj.remark);
                    $('#discountperview').html(obj.discount + '%');
                    $('#discountperviewpo').html(obj.podiscount + '%');
                    $('#viewmodaltitle').html('Order No: PO-' + id);

                    var objfirst = obj.tablelist;
                    console.log(objfirst);
                    $.each(objfirst, function (i, item) {
                        //alert(objfirst[i].id);

                        $('#tableorderview > tbody:last').append('<tr><td>' +
                            objfirst[i].productname +
                            '</td><td class="d-none">' + objfirst[i].productid +
                            '</td><td class="text-center">' + objfirst[i]
                            .newqty + '</td><td class="">' + objfirst[i]
                            .freeproduct + '</td><td class="d-none">' +
                            objfirst[i].freeproductid +
                            '</td><td class="text-center">' + objfirst[i]
                            .freeqty + '</td><td class="text-right total">' +
                            objfirst[i].total + '</td></tr>');
                    });
                    $('#modalorderview').modal('show');
                }
            });
        });
        $('#modalorderview').on('hidden.bs.modal', function (e) {
            $('#tableorderview > tbody').html('');
        });
    });

    function checkStock(productid, qty) {
        $.ajax({
            type: 'POST',
            data: {
                locationid: '0',
                productid: productid
            },
            url: 'getprocess/checkstock.php',
            success: function (result) {
                var obj = JSON.parse(result);
                var availableqty = obj.qty

                if (parseInt(qty) > parseInt(availableqty)) {
                    $('#hiddenqtyflag').val(1)
                }
            }
        })
    }

    function category(repId, value) {
        //  alert(repId);
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

                $('#area1').empty().append(html);

                if (value != '') {
                    $('#area1').val(value);
                }
            }
        });
    }

    function updateTable(records) {
        var count = 0;
        $('#tableorder > tbody').empty(); // Clear existing rows

        records.forEach(function (obj) {
            count++;
            var freeqty = parseFloat(obj.freeqty);
            var qty = parseFloat(obj.qty);
            var totalqty = freeqty + qty;
            var saleprice = addCommas(parseFloat(obj.saleprice).toFixed(2));
            //  alert(obj.idtbl_porder_detail);
            //  alert(obj.saleprice);
            var totalprice = totalqty * obj.saleprice;
            // alert(totalprice);
            $('#tableorder > tbody:last').append('<tr class="pointer">' +
                '<td>' + count + '</td>' +
                '<td>' + obj.product_name + '</td>' +
                '<td class="d-none">' + obj.tbl_product_idtbl_product + '</td>' +
                '<td class="d-none">' + obj.unitprice + '</td>' +
                '<td class="d-none">' + obj.saleprice + '</td>' +
                '<td class="text-center chngeqty">' + obj.qty + '</td>' +
                '<td></td>' +
                '<td class="d-none">' + obj.freeproductid + '</td>' +
                '<td class="text-center chngeqtyfree">' + obj.freeqty + '</td>' +
                '<td class="text-center totalqty">' + totalqty + '</td>' +
                '<td class="text-right">' + saleprice + '</td>' +
                '<td class="d-none total1">' + totalprice + '</td>' +
                '<td class="text-right totalsumshow">' + addCommas(totalprice.toFixed(2)) + '</td>' +
                '<td class="total d-none totalsum saleprice">' + obj.saleprice +
                '</td><td class=" d-none">' + obj.idtbl_porder_detail + '</td></tr>');


        });
        calculateTotaleditable();


    }

    function calculateTotaleditable() {
        var sum = 0;
        $(".total1").each(function () {
            sum += parseFloat($(this).text());
        });
        // alert(sum);


        // console.log(sum);
        var discount = parseFloat($('#discountpresentage').val());
        var discountpo = parseFloat($('#discountpo').val());
        // alert(discount);
        // alert(discountpo);
        var disvalue = (sum * discount) / 100;
        var nettotal = sum - disvalue;
        var disvaluepo = (nettotal * discountpo) / 100;
        var nettotal = nettotal - disvaluepo;

        var showsum = addCommas(parseFloat(sum).toFixed(2));
        var showdis = addCommas(parseFloat(disvalue).toFixed(2));
        var showdispo = addCommas(parseFloat(disvaluepo).toFixed(2));
        var shownettotal = addCommas(parseFloat(nettotal).toFixed(2));
        // alert(showsum);
        $('#divsubtotal1').html('Rs. ' + showsum);
        $('#hidetotalorder1').val(sum);
        $('#divdiscount1').html('Rs. ' + showdis);
        $('#divdiscountPO1').html('Rs. ' + showdispo);
        $('#hidediscount1').val(disvalue);
        $('#hidediscountPO1').val(disvaluepo);
        $('#divtotal1').html('Rs. ' + shownettotal);
        $('#hidenettotalorder1').val(nettotal);
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
                    //  alert(objfirst[i].id);
                    html += '<option value="' + objfirst[i].id + '">';
                    html += objfirst[i].name;
                    html += '</option>';
                });

                $('#customer').empty().append(html);
                if (value != '') {
                    $('#customer').val(value);
                }
            }
        });
    }

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
            style: '@page { size: landscape; margin:0.25cm; }',
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

    function delete_confirm() {
        return confirm("Are you sure you want to remove this?");
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

    function textremove(classname, row) {
        $('#invoicetable tbody').on('keyup', classname, function (e) {
            if (e.keyCode === 13) {
                $this = $(this);
                var val = $this.val();
                var td = $this.closest('td');
                td.empty().html(val).data('editing', false);

                var rowID = row.closest("td").parent()[0].rowIndex;
                var unitprice = parseFloat(row.closest("tr").find('td:eq(2)').text());
                var saleprice = parseFloat(row.closest("tr").find('td:eq(3)').text());

                var newqty = parseFloat(row.closest("tr").find('td:eq(4)').text());
                var freeqty = parseFloat(row.closest("tr").find('td:eq(5)').text());

                var totqty = newqty + freeqty;
                var totnew = newqty * saleprice;

                var total = parseFloat(totnew).toFixed(2);
                var showtotal = addCommas(total);

                $('#invoicetable').find('tr').eq(rowID).find('td:eq(6)').text(totqty);
                $('#invoicetable').find('tr').eq(rowID).find('td:eq(8)').text(total);
                $('#invoicetable').find('tr').eq(rowID).find('td:eq(9)').text(showtotal);

                tabletotal();
            }
        });
    }

    function tabletotal() {
        var sum = 0;
        $(".total").each(function () {
            sum += parseFloat($(this).text());
        });

        var showsum = addCommas(parseFloat(sum).toFixed(2));

        $('#divtotal').html('Rs. ' + showsum);
        $('#hidetotalorder').val(sum);
    }
</script>
<?php include "include/footer.php"; ?>