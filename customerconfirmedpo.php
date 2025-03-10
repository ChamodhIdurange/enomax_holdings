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
                                    <span>Customer Confirmed POrder</span>
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
                    <div class="col-12">
                        <form id="createorderform" autocomplete="off">
                            <div class="row">

                                <div class="form-group mb-2 col-3">
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
                                <div class="form-group mb-2 col-3">
                                    <label class="small font-weight-bold text-dark">Sales Manager*</label>
                                    <select class="form-control form-control-sm" name="salesmanager" id="salesmanager">
                                        <option value="">Select</option>
                                        <?php if ($resultmanagerlist->num_rows > 0) {
                                            while ($rowsalesmanager = $resultmanagerlist->fetch_assoc()) { ?>
                                        <option value="<?php echo $rowsalesmanager['idtbl_sales_manager'] ?>">
                                            <?php echo $rowsalesmanager['salesmanagername'] ?></option>
                                        <?php }
                                        } ?>
                                    </select>
                                </div>
                                <div class="form-group mb-2 col-3">
                                    <label class="small font-weight-bold text-dark">Rep Name*</label>
                                    <select class="form-control form-control-sm" name="repname" id="repname" required>
                                        <option value="">Select</option>
                                        <?php if ($resulthelperlist->num_rows > 0) {
                                            while ($rowemplist = $resulthelperlist->fetch_assoc()) { ?>
                                        <option value="<?php echo $rowemplist['idtbl_employee'] ?>">
                                            <?php echo $rowemplist['name'] ?></option>
                                        <?php }
                                        } ?>
                                    </select>
                                </div>
                                <div class="form-group mb-2 col-3">
                                    <label class="small font-weight-bold text-dark">Location*</label>
                                    <select class="form-control form-control-sm" name="location" id="location" required>
                                        <option value="">Select</option>
                                        <?php if ($resultlocationlist->num_rows > 0) {
                                            while ($rowloclist = $resultlocationlist->fetch_assoc()) { ?>
                                        <option value="<?php echo $rowloclist['idtbl_locations'] ?>">
                                            <?php echo $rowloclist['locationname'] ?></option>
                                        <?php }
                                        } ?>
                                    </select>
                                </div>
                                <div class="form-group mb-2 col-3">
                                    <label class="small font-weight-bold text-dark">Area*</label>
                                    <select class="form-control form-control-sm" name="area" id="area" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>

                                <div class="form-group mb-2 col-6" id="directcustomerdiv" hidden>
                                    <!-- <label class="small font-weight-bold text-dark">Customers*</label>
                                    <input type="text" placeholder="Enter customer name"
                                        class="form-control form-control-sm" name="directcustomer" id="directcustomer"
                                        required></input> -->
                                </div>

                                <div class="form-group mb-2 col-6" id="customerdiv">
                                    <label class="small font-weight-bold text-dark">Customer*</label>
                                    <select class="form-control form-control-sm" name="customer" id="customer" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <div class="form-group mb-2 col-3 d-none">
                                    <label class="small font-weight-bold text-dark">Customer address*</label>
                                    <input type="text" id="customeraddress" name="customeraddress"
                                        class="form-control form-control-sm" readonly required>
                                </div>
                                <div class="form-group mb-2 col-3">
                                    <label class="small font-weight-bold text-dark">Customer contact*</label>
                                    <input type="text" id="customercontact" name="customercontact"
                                        class="form-control form-control-sm" readonly required>
                                </div>
                                <div class="form-group mb-2 col-3">
                                    <label class="small font-weight-bold text-dark">Discount %</label>
                                    <input type="number" id="discountpresentage" name="discountpresentage"
                                        class="form-control form-control-sm" value="0">
                                </div>
                                <div class="mb-2 col-3">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Common name*</label>
                                        <select class="form-control form-control-sm select2" style="width: 100%;" name="productcommonname"
                                            id="productcommonname">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mb-2 col-3">
                                    <label class="small font-weight-bold text-dark">Product*</label>
                                    <select class="form-control form-control-sm" name="product" id="product" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <div class="form-group mb-2 col-3 d-none">
                                    <label class="small font-weight-bold text-dark">Supplier</label>
                                    <input type="text" id="fieldsupplier" name="fieldsupplier"
                                        class="form-control form-control-sm" value="" readonly>
                                </div>
                                <div class="form-group mb-2 col-3 d-none">
                                    <label class="small font-weight-bold text-dark">Size</label>
                                    <input type="text" id="fieldsize" name="fieldsize"
                                        class="form-control form-control-sm" value="" readonly>
                                </div>
                                <div class="form-row mb-1 col-3">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Qty*</label>
                                        <input type="text" id="newqty" name="newqty"
                                            class="form-control form-control-sm" value="0" required>
                                    </div>
                                    <div class="col d-none">
                                        <label class="small font-weight-bold text-dark">Free Qty</label>
                                        <input type="text" id="freeqty" name="freeqty"
                                            class="form-control form-control-sm" value="0">
                                    </div>
                                </div>
                                <div class="form-group mb-1 col-3 d-none">
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
                                        <?php if ($addcheck == 0) {echo 'disabled';} ?>><i
                                            class="fas fa-plus"></i>&nbsp;Add
                                        Product</button>
                                    <input name="submitBtn" type="submit" value="Save" id="submitBtn" class="d-none">
                                </div>
                                <input type="hidden" name="unitprice" id="unitprice" value="">
                                <input type="hidden" name="recordOption" id="recordOption" value="1">
                                <input type="hidden" name="freeproductid" id="freeproductid" value="">
                                <input type="hidden" name="hiddencustomertype" id="hiddencustomertype" value="">
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
                                <th class="d-none">Free Product</th>
                                <th class="d-none">Freeproductid</th>
                                <th class="text-center d-none">Free Qty</th>
                                <th class="text-right">Sale Price</th>
                                <th class="text-right">Line Discount</th>
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
                            <h6 class="">PO Discount</h6>
                        </div>
                        <div class="col text-right">
                            <h6 class="" id="divdiscountPO">Rs. 0.00</h6>
                        </div>
                        <input type="hidden" id="hidediscountPO" value="0">
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
                    <div class='row'>
                        <div class="form-group col-8">
                            <label class="small font-weight-bold text-dark">Remark</label>
                            <textarea name="remark" id="remark" class="form-control form-control-sm"></textarea>
                        </div>
                    </div>


                    <div class="form-group mt-2">
                        <button type="button" id="btncreateorder" class="btn btn-outline-primary btn-sm fa-pull-right"
                            <?php if ($addcheck == 0) {echo 'disabled';} ?>><i class="fas fa-save"></i>&nbsp;Create
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
                    <button class="btn btn-primary btn-sm fa-pull-right mt-3" id="btncuspoupdate"><i
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

        $("#productcommonname").select2({
            ajax: {
                url: "getprocess/getcommonnamesselect2.php",
                // url: "getprocess/getproductaccosupplier.php",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term, // search term
                    };
                },
                processResults: function (response) { //console.log(response)
                    return {
                        results: response
                    };
                },
                cache: true
            },
            dropdownParent: $("#modalcreateorder")
        });

        $("#editcustomer").select2({
            ajax: {
                url: "getprocess/getcustomerlistforreturn.php",
                // url: "getprocess/getproductaccosupplier.php",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term, 
                    };
                },
                processResults: function (response) { //console.log(response)
                    return {
                        results: response
                    };
                },
                cache: true
            },
            dropdownParent: $("#modaleditpo")
        });
        $("#modaleditproduct").select2({
            ajax: {
                url: "getprocess/getproductsforcustomerpo.php",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term
                    };
                },
                processResults: function (response) { // console.log(response)
                    return {
                        results: response
                    };
                },
                cache: true
            },
            dropdownParent: $('#modalorderview')
        });

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
                url: "scripts/customerconfirmedporderlist.php",
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
                            '<button class="btn btn-outline-primary btn-sm btnEdit mr-1 " data-toggle="tooltip" data-placement="bottom" title="Edit PO Details" id="' +
                            full['idtbl_customer_order'] + '" name="' + full['confirm'] +
                            '"  data-podate="' + full['date'] +
                            '" data-customerid="' + full['tbl_customer_idtbl_customer'] +
                            '" data-customername="' + full['cusname'] + ' - ' +  full['cusaddress']  +
                            '" data-repid="' + full['tbl_employee_idtbl_employee'] +
                            '"><i class="fas fa-pen"></i></button>';
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

                        button +=
                            '<button class="btn btn-outline-dark btn-sm btnview mr-1 " data-toggle="tooltip" data-placement="bottom" title="View PO Details" id="' +
                            full['idtbl_customer_order'] + '" name="' + full['confirm'] +
                            '"><i class="far fa-eye"></i></button>';
                        // if (usertype == 2 || usertype == 1) {
                        //     button +=
                        //         '<button class="btn btn-outline-secondary btn-sm btneditorder mr-1" id="' +
                        //         full['idtbl_customer_order'] +
                        //         '"><i class="fas fa-pen"></i></button>';
                        // }
                        if (full['status'] == 2 && statuscheck == 1) {
                            button +=
                                '<button class="btn btn-secondary btn-sm btnreactive mr-1" id="' +
                                full['idtbl_customer_order'] +
                                '"><i class="fas fa-check"></i></button>';
                        }
                        if (full['status'] == 1 && full['confirm'] == null && editcheck == 1) {
                            button +=
                                '<button class="btn btn-warning btn-sm btnConfirm mr-1" data-toggle="tooltip" data-placement="bottom" title="Confirm Order" name="' +
                                full['confirm'] +
                                '" id="' +
                                full['idtbl_customer_order'] +
                                '"><i class="fa fa-times"></i></button>';
                        } else if (full['status'] == 1 && full['confirm'] == 1 && editcheck ==
                            1) {
                            button +=
                                '<button class="btn btn-success btn-sm  mr-1" data-toggle="tooltip" data-placement="bottom" title="Order Confirmed"  id="' +
                                full['idtbl_customer_order'] +
                                '"><i class="fa fa-check"></i></button>';
                        }

                        if (full['status'] == 1 && full['confirm'] == 1 && full[
                                'dispatchissue'] == null && editcheck == 1) {
                            button +=
                                '<button class="btn btn-warning btn-sm btnDispatch mr-1" data-toggle="tooltip" data-placement="bottom" title="Dispatch Order" name="' +
                                full['confirm'] +
                                '" id="' +
                                full['idtbl_customer_order'] +
                                '"><i class="fa fa-paper-plane"></i></button>';
                        } else if (full['status'] == 1 && full['confirm'] == 1 && full[
                                'dispatchissue'] == 1 && editcheck == 1) {
                            button +=
                                '<button class="btn btn-success btn-sm  mr-1" data-toggle="tooltip" data-placement="bottom" title="Order Dispatched"  id="' +
                                full['idtbl_customer_order'] +
                                '"><i class="fa fa-paper-plane"></i></button>';
                        }

                        if (full['status'] == 1 && full['confirm'] == 1 && full[
                                'dispatchissue'] == 1 && full['delivered'] == null &&
                            editcheck == 1) {
                            button +=
                                '<button class="btn btn-warning btn-sm btnDeliver mr-1" data-toggle="tooltip" data-placement="bottom" title="Deliver Order" name="' +
                                full['confirm'] +
                                '" id="' +
                                full['idtbl_customer_order'] +
                                '"><i class="fa fa-truck"></i></button>';
                        } else if (full['status'] == 1 && full['confirm'] == 1 && full[
                                'dispatchissue'] == 1 && full['delivered'] == 1 && editcheck ==
                            1) {
                            button +=
                                '<button class="btn btn-success btn-sm  mr-1" data-toggle="tooltip" data-placement="bottom" title="Order Delivered"  id="' +
                                full['idtbl_customer_order'] +
                                '"><i class="fa fa-truck"></i></button>';
                        }
                        // if (full['status'] == 1) {
                        //     button +=
                        //         '<button class="btn btn-outline-primary btn-sm mr-1 btnprint" data-toggle="tooltip" data-placement="bottom" title="Print Order" id="' +
                        //         full['idtbl_customer_order'] +
                        //         '"><i class="fas fa-print"></i></button>';
                        // }
                        // if (full['confirm'] == 0 && full['status'] == 1) {
                        //     button +=
                        //         '<button class="btn btn-outline-orange btn-sm btnaccept mr-1" data-toggle="tooltip" data-placement="bottom" title="Not Accept Order" id="' +
                        //         full['idtbl_customer_order'] +
                        //         '"><i class="fas fa-times"></i></button>';
                        // } else if (full['confirm'] == 1 && full['status'] == 1) {
                        //     button +=
                        //         '<button class="btn btn-outline-success btn-sm mr-1" data-toggle="tooltip" data-placement="bottom" title="Accepted Order"><i class="fas fa-check"></i></button>';
                        // }
                        if (full['delivered'] != 1 && full['status'] == 1) {
                            button +=
                                '<button class="btn btn-outline-danger btn-sm mr-1 btncancel" data-toggle="tooltip" data-placement="bottom" title="Cancel order" id="' +
                                full['idtbl_customer_order'] +
                                '"><i class="fas fa-window-close"></i></button>';
                        }
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

        $('#modaleditsaleprice, #modaleditqty').keyup(function(){
            calculateNewAddedProductTot();
        })
        $('#modaleditdiscountamount').keyup(function(){
            var saleprice = $('#modaleditsaleprice').val();
            var qty = $('#modaleditqty').val();
            var discountAmount = $(this).val();

            var discountPrecentage = (discountAmount * 100)/ (saleprice * qty);
            $('#modaleditdiscountpercentage').val(parseFloat(discountPrecentage).toFixed(2));

            calculateNewAddedProductTot();
        })
        $('#modaleditdiscountpercentage').keyup(function(){
            var saleprice = $('#modaleditsaleprice').val();
            var qty = $('#modaleditqty').val();
            var discountPrecentage = $(this).val();

            var discountAmount = (saleprice * qty * discountPrecentage)/100;
            $('#modaleditdiscountamount').val(parseFloat(discountAmount).toFixed(2));

            calculateNewAddedProductTot();
        })
        
        $('#btnAddNewProduct').click(function () {
            addNewCheckStock();
        })

        function addNewCheckStock() {
            var productID = $('#modaleditproduct').val();
            var newqty = parseFloat($('#modaleditqty').val());

            $.ajax({
                type: "POST",
                data: {
                    productID: productID,
                    usingqty: newqty
                },
                url: 'getprocess/checkavailablestock.php',
                success: function (result) { //alert(result)
                    var obj = JSON.parse(result);
                    if (obj.availableqty < newqty) {
                        var productname = $("#product option:selected").text();

                        $('#errordivaddnew').empty().html(
                            "  <div class='alert alert-danger alert-dismissible fade show' role='alert'><h5 id = 'errormessageaddnew'></h5><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>"
                        )
                        $('#errormessageaddnew').html(
                            "There is not enough stock available for product '" + productname)
                    }else{

                        var productID = $('#modaleditproduct').val();
                        var product = $("#modaleditproduct option:selected").text();
                        var saleprice = $('#modaleditsaleprice').val();
                        var qty = $('#modaleditqty').val();
                        var productCode = $('#modaleditproductcode').val();
                        var discountAmount = $('#modaleditdiscountamount').val();
                        var discountPercentage = $('#modaleditdiscountpercentage').val();
                        var netTotal = $('#modaleditnettotal').val();

                        var totAmount = saleprice * qty;
                        $('#tableorderview > tbody:last').append('<tr><td>' +
                            product +
                            '</td><td>' +
                            productCode +
                            '</td><td class="d-none">' + productID +
                            '</td><td class="d-none">' + 0 +
                            '</td><td class="text-center editnewqty">' +
                            qty +
                            '</td><td class="text-center editlinediscountpernetage">' +
                            discountPercentage +
                            '</td><td class="text-center editlinediscount">' +
                            discountAmount +
                            '</td><td class="text-right total">' + netTotal +
                            '</td><td class="text-right colunitprice">' +
                            saleprice +
                            '</td><td class="text-right"><button class="btn btn-outline-danger btn-sm btnDeleteNewProduct mr-1" data-placement="bottom" title="Invoice Print" id="' +
                            0 +
                            '"><i class="fas fa-trash"></i></button></td><td class="d-none">' +
                            1 +
                            '</td><td class="d-none totwithoutdiscount">' +
                            totAmount +
                            '</td><td class="d-none">1</td></tr>');

                        $('#modaleditproduct').val('')
                        $('#modaleditproduct').trigger('change');

                        $('#modaleditsaleprice').val(0)
                        $('#modaleditqty').val(0)
                        $('#modaleditdiscountamount').val(0)
                        $('#modaleditdiscountpercentage').val(0)
                        $('#modaleditnettotal').val(0)
                        
                        tabletotal1();
                    }
                }
            });
        }
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



        $('#dataTable tbody').on('click', '.btnEdit', function () {
            var id = $(this).attr('id');
            var customerid = $(this).data('customerid');
            var customername = $(this).data('customername');
            var podate = $(this).data('podate');
            var repid = $(this).data('repid');
            $('#editsalesrep').val(repid);

            $('#hiddencustomerpoid').val(id);
            $('#modaleditpo').modal('show');

            $('#editorderdate').val(podate);
            $('#editsalesrep').val(repid);

            $('#editcustomer').append(new Option(customername, customerid, true, true)).trigger('change');
            $('#editcustomer').val(customerid);
        });


        $('#dataTable tbody').on('click', '.btnConfirm', function () {
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
                    $('#tableorderview > tbody').empty();

                    $('#divsubtotalview').html(obj.subtotal);
                    $('#divdiscountview').html(obj.disamount);
                    $('#divdiscountPOview').html(obj.po_amount);
                    $('#divtotalview').html(obj.nettotalshow);
                    $('#remarkview').val(obj.remark);
                    $('#dcusname').html(obj.cusname);
                    $('#dcuscontact').html(obj.cuscontact);
                    $('#viewmodaltitle').html('Order No: PO-' + id);
                    $('#editpodiscount').val(obj.podiscountpercentage);
                    
                    var objfirst = obj.tablelist;
                    $.each(objfirst, function (i, item) {
                        $('#tableorderview > tbody:last').append('<tr><td>' +
                            objfirst[i].productname +
                            '</td><td>' +
                            objfirst[i].productcode +
                            '</td><td class="d-none">' + objfirst[i].productid +
                            '</td><td class="d-none">' + objfirst[i]
                            .podetailid +
                            '</td><td class="text-center editnewqty">' +
                            objfirst[i].orderqty +
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
                            '"><i class="fas fa-trash"></i></button></td><td class="d-none">' +
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
                        }
                    });

                    $('#btnUpdate').html('<i class="far fa-save"></i>&nbsp;Confirm');
                    $('#btnUpdate').prop('disabled', false);
                    $('#acceptanceType').val(1)

                    $('#modalorderview').modal('show');

                    tabletotal1();
                }
            });
        });
        $('#dataTable tbody').on('click', '.btnDeliver', function () {
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
                    $('#tableorderview > tbody').empty();

                    $('#divsubtotalview').html(obj.subtotal);
                    $('#divdiscountview').html(obj.disamount);
                    $('#divdiscountPOview').html(obj.po_amount);
                    $('#divtotalview').html(obj.nettotalshow);
                    $('#remarkview').val(obj.remark);
                    $('#dcusname').html(obj.cusname);
                    $('#dcuscontact').html(obj.cuscontact);
                    $('#viewmodaltitle').html('Order No: PO-' + id);
                    $('#editpodiscount').val(obj.podiscountpercentage);

                    var objfirst = obj.tablelist;
                    $.each(objfirst, function (i, item) {
                        //alert(objfirst[i].id);
                        $('#tableorderview > tbody:last').append('<tr><td>' +
                            objfirst[i].productname +
                            '</td><td>' +
                            objfirst[i].productcode +
                            '</td><td class="d-none">' + objfirst[i].productid +
                            '</td><td class="d-none">' + objfirst[i]
                            .podetailid +
                            '</td><td class="text-center editnewqty">' +
                            objfirst[i].dispatchqty +
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
                            '"><i class="fas fa-trash"></i></button></td><td class="d-none">' +
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

                        }
                    });
                    
                    $('#btnUpdate').html('<i class="far fa-save"></i>&nbsp;Deliver');
                    $('#btnUpdate').prop('disabled', false);
                    $('#acceptanceType').val(3)

                    $('#modalorderview').modal('show');
                    tabletotal1();
                }
            });
        });
        $('#dataTable tbody').on('click', '.btnDispatch', function () {
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
                    $('#tableorderview > tbody').empty();

                    $('#divsubtotalview').html(obj.subtotal);
                    $('#divdiscountview').html(obj.disamount);
                    $('#divdiscountPOview').html(obj.po_amount);
                    $('#divtotalview').html(obj.nettotalshow);
                    $('#remarkview').val(obj.remark);
                    $('#dcusname').html(obj.cusname);
                    $('#dcuscontact').html(obj.cuscontact);
                    $('#viewmodaltitle').html('Order No: PO-' + id);
                    $('#editpodiscount').val(obj.podiscountpercentage);

                    var objfirst = obj.tablelist;
                    $.each(objfirst, function (i, item) {
                        //alert(objfirst[i].id);

                        $('#tableorderview > tbody:last').append('<tr><td>' +
                            objfirst[i].productname +
                            '</td><td>' +
                            objfirst[i].productcode +
                            '</td><td class="d-none">' + objfirst[i].productid +
                            '</td><td class="d-none">' + objfirst[i]
                            .podetailid +
                            '</td><td class="text-center editnewqty">' +
                            objfirst[i].confirmqty +
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
                            '"><i class="fas fa-trash"></i></button></td><td class="d-none">' +
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

                        }
                    });

                    $('#btnUpdate').html('<i class="far fa-save"></i>&nbsp;Dispatch');
                    $('#btnUpdate').prop('disabled', false);
                    $('#acceptanceType').val(2)

                    $('#modalorderview').modal('show');
                    tabletotal1();
                }
            });
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

        $('.dpd2a').datepicker({
            uiLibrary: 'bootstrap4',
            autoclose: 'true',
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        });

        // Customer part
        $('#salesmanager').change(function () {
            var salesmanagerid = $(this).val();

            $.ajax({
                type: "POST",
                data: {
                    salesmanagerid: salesmanagerid
                },
                url: 'getprocess/getemployeesaccosalesmanager.php',
                success: function (result) { //alert(result);
                    var objfirst = JSON.parse(result);
                    var html1 = '';
                    html1 += '<option value="">Select</option>';
                    $.each(objfirst, function (i, item) {
                        // alert(objfirst[i].id);
                        html1 += '<option value="' + objfirst[i].id + '">';
                        html1 += objfirst[i].name;
                        html1 += '</option>';
                    });

                    $('#repname').empty().append(html1);


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

        // $('#area').change(function () {
        //     var areaID = $(this).val();

        //     $.ajax({
        //         type: "POST",
        //         data: {
        //             areaID: areaID
        //         },
        //         url: 'getprocess/getasmlistaccoarea.php',
        //         success: function (result) { //alert(result);
        //             var objfirst = JSON.parse(result);

        //             var html = '';
        //             html += '<option value="">Select</option>';
        //             $.each(objfirst, function (i, item) {
        //                 //alert(objfirst[i].id);
        //                 html += '<option value="' + objfirst[i].id + '">';
        //                 html += objfirst[i].name;
        //                 html += '</option>';
        //             });

        //             $('#repname').empty().append(html);
        //         }
        //     });
        // });

        $('#area').change(function () {
            var repId = $('#repname').val();
            var areaID = $(this).val();

            selectcustomer(repId, areaID, '');
        })
        $('#repname').change(function () {
            var areaID = $('#area').val();
            var repId = $(this).val();

            // if (repId == 7) {
            //     $("#directcustomerdiv").attr("hidden", false);
            //     $("#directcustomer").attr("required", true);

            //     $("#customeraddress").attr("readonly", false);
            //     $("#customercontact").attr("readonly", false);


            //     $("#customerdiv").attr("hidden", true);
            //     $("#customer").attr("required", false);
            // } else {
            //     $("#directcustomer").attr("required", false);
            //     $("#directcustomerdiv").attr("hidden", true);

            //     $("#customeraddress").attr("readonly", true);
            //     $("#customercontact").attr("readonly", true);

            //     $("#customer").attr("required", true);
            //     $("#customerdiv").attr("hidden", false);
            // }
            category(repId, '');
        })
        // Prodcut part
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
                    $('#hiddencustomertype').val(obj.type);
                }
            });
        });

        // $("#newqty").keyup(function () {
        //     var qty = $(this).val();
        //     var productID = $('#product').val();

        //     $.ajax({
        //         type: "POST",
        //         data: {
        //             productID: productID,
        //             qty: qty
        //         },
        //         url: 'getprocess/getproductfreewtyaccoproductqty.php',
        //         success: function (result) { //alert(result);
        //             var obj = JSON.parse(result);
        //             $('#freeqty').val(obj.freecount);
        //             $('#freeproductname').val(obj.productname);
        //             $('#freeproductid').val(obj.productid);
        //         }
        //     });
        // });

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
                    $('#tableorderview > tbody').empty();

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

        $('#editpodiscount').keyup(function () {
            var discountprecentage = $(this).val();

            if (discountprecentage == null) {
                discountprecentage = 0;
            }
            var linediscount = $("#divdiscountview").text();
            var cleanlinediscount = linediscount.split(",").join("");
            var cleanlinediscount = parseFloat(cleanlinediscount, 10);

            var subtotal = $("#divsubtotalview").text();
            var cleansubtotal = subtotal.split(",").join("");
            var cleansubtotal = parseFloat(cleansubtotal, 10);

            var poDiscountAmount = (cleansubtotal - cleanlinediscount) * (discountprecentage / 100);
            $('#editpodiscountamount').val(parseFloat(poDiscountAmount).toFixed(2))

            var netTotal = cleansubtotal - (cleanlinediscount + poDiscountAmount);

            // alert(netTotal)
            var shownet = addCommas(parseFloat(netTotal).toFixed(2));
            var showPoDiscount = addCommas(parseFloat(poDiscountAmount).toFixed(2));

            $('#divdiscountPOview').html(showPoDiscount);
            $('#divtotalview').html(shownet);


        })
        $('#editpodiscountamount').keyup(function () {
            var discountAmount = $(this).val();

            if (discountAmount == null) {
                discountprecentage = 0;
            }
            var discountAmount = parseFloat(discountAmount);

            var linediscount = $("#divdiscountview").text();
            var cleanlinediscount = linediscount.split(",").join("");
            var cleanlinediscount = parseFloat(cleanlinediscount, 10);

            var subtotal = $("#divsubtotalview").text();
            var cleansubtotal = subtotal.split(",").join("");
            var cleansubtotal = parseFloat(cleansubtotal, 10);

            var poDiscountPercentage = (discountAmount * 100) / (cleansubtotal - cleanlinediscount);

            $('#editpodiscount').val(parseFloat(poDiscountPercentage).toFixed(2))
            var netTotal = cleansubtotal - (cleanlinediscount + discountAmount);

            var shownet = addCommas(parseFloat(netTotal).toFixed(2));
            var showPoDiscount = addCommas(discountAmount);

            $('#divdiscountPOview').html(showPoDiscount);
            $('#divtotalview').html(shownet);
        })

        $('#tableorderview tbody').on('click', '.editnewqty, .editlinediscountpernetage', function (e) {
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
            textremoveQtyandPrecentageAndSalePrice('.optionnewqty', row);
        });

        function qtyChangeCheckStock(classname, row) {
            return new Promise((resolve, reject) => {
                var productID = parseFloat(row.closest("tr").find('td:eq(2)').text());
                var newqty = parseFloat(row.closest("tr").find('td:eq(4) input').val());
                var customerOrderId = $('#hiddenpoid').val();

                $.ajax({
                    type: "POST",
                    data: {
                        productID: productID,
                        customerOrderId: customerOrderId
                    },
                    url: 'getprocess/checkavailablestockinlineedit.php',
                    success: function (result) {// alert(result)
                        var obj = JSON.parse(result);

                        if (obj.availableqty < newqty) {
                            var productname = $("#product option:selected").text();

                            $('#errordivaddnew').empty().html(
                                "<div class='alert alert-danger alert-dismissible fade show' role='alert'>" +
                                "<h5 id='errormessageaddnew'></h5>" +
                                "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>" +
                                "<span aria-hidden='true'>&times;</span></button></div>"
                            );
                            $('#errormessageaddnew').html(
                                "There is not enough stock available for this Product"
                            );

                            resolve(true); 
                        } else {
                            resolve(false); 
                        }
                    },
                    error: function (xhr, status, error) {
                        reject(error);
                    }
                });
            });
        }

        function textremoveQtyandPrecentageAndSalePrice(classname, row) {
            $(classname).keyup(function (e) {
                if (e.keyCode === 13) {
                    qtyChangeCheckStock('.optionnewqty', row).then((donotproceed) => {
                        if (donotproceed) {
                            return; 
                        }

                        $this = $(this);
                        var val = $this.val();
                        var td = $this.closest('td');
                        td.empty().html(val).data('editing', false);

                        var rowID = row.closest("td").parent()[0].rowIndex;
                        var unitprice = parseFloat(row.closest("tr").find('td:eq(8)').text());
                        var newqty = parseFloat(row.closest("tr").find('td:eq(4)').text());
                        var discountprecent = parseFloat(row.closest("tr").find('td:eq(5)').text());
                        var discountamount = parseFloat(row.closest("tr").find('td:eq(6)').text());

                        var totwithoutdiscount = newqty * unitprice;
                        var totnew = totwithoutdiscount;
                        var newdiscount = (totnew * discountprecent) / 100;

                        totnew = totnew - newdiscount;

                        var showtotnew = addCommas(parseFloat(totnew).toFixed(2));

                        $('#tableorderview').find('tr').eq(rowID).find('td:eq(7)').text(showtotnew);
                        $('#tableorderview').find('tr').eq(rowID).find('td:eq(6)').text(newdiscount);
                        $('#tableorderview').find('tr').eq(rowID).find('td:eq(11)').text(
                            totwithoutdiscount
                        );

                        tabletotal1();
                    }).catch(error => {
                        console.error('Error checking stock:', error);
                    });
                }
            });
        }


        $('#tableorderview tbody').on('click', '.colunitprice', function (e) {
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

            $('<input type="Text" class="form-control form-control-sm optionsaleprice">').val(val)
                .appendTo($this);
                textremoveQtyandPrecentageAndSalePrice('.optionsaleprice', row);
        });

        

        $('#tableorderview tbody').on('click', '.editlinediscount', function (e) {
            var row = $(this);

            e.preventDefault();
            e.stopImmediatePropagation();

            $this = $(this);
            if ($this.data('editing')) return;

            var val = $this.text();

            $this.empty();
            $this.data('editing', true);

            $('<input type="Text" class="form-control form-control-sm optionnewlinediscount">').val(val)
                .appendTo($this);

            textremoveDiscountAmount('.optionnewlinediscount', row);
        });

        function textremoveDiscountAmount(classname, row) {
            // $('#tableorderview tbody').on('keyup', classname, function (e) {
            $(classname).keyup(function (e) {
                if (e.keyCode === 13) {
                    $this = $(this);
                    var val = $this.val();
                    var td = $this.closest('td');
                    td.empty().html(val).data('editing', false);


                    var rowID = row.closest("td").parent()[0].rowIndex;
                    var unitprice = parseFloat(row.closest("tr").find('td:eq(8)').text());
                    var newqty = parseFloat(row.closest("tr").find('td:eq(4)').text());

                    var totwithoutdiscount = newqty * unitprice;
                    var discountPrecentage = (val * 100) / totwithoutdiscount;
                    discountPrecentage = parseFloat(discountPrecentage).toFixed(2);

                    $('#tableorderview').find('tr').eq(rowID).find('td:eq(5)').text(discountPrecentage);

                    var totnew = totwithoutdiscount;
                    var newdiscount = val;

                    totnew = totnew - newdiscount;


                    var showtotnew = addCommas(parseFloat(totnew).toFixed(2));

                    row.closest("tr").find('td:eq(7)').text(showtotnew);
                    row.closest("tr").find('td:eq(6)').text(newdiscount);
                    row.closest("tr").find('td:eq(11)').text(totwithoutdiscount);

                    tabletotal1();
                }
            });
        }

        // function TextInputRemove(rowdata){
        //     $(".editfieldpay").keyup(function(event) {
        //         if (event.keyCode === 13) {            
        //             $this = $(this);
        //             var val = $this.val();
        //             var td = $this.closest('td');
        //             td.empty().html(parseFloat(val).toFixed(2)).data('editing', false);

        //             var deferred = $.Deferred();

        //             setTimeout(function() {
        //                 // completes status
        //                 deferred.resolve();
        //             }, 1000);

        //             // returns complete status

        //             var qty = parseFloat(rowdata.closest("tr").find('.qty').text()).toFixed(2);
        //             var rowtotal = parseFloat(val).toFixed(2)*qty;
        //             rowdata.closest("tr").find('.total').text(addCommas(parseFloat(rowtotal).toFixed(2)));

        //             TotalCalculation('1');

        //             return deferred.promise();
        //         }
        //     });
        // }


        $('#tableorderview tbody').on('click', '.btnDeleteOrderProduct', function (e) {
            var row = $(this).closest('tr');
            row.css('background-color', '#ffcccc');

            row.find('td:nth-child(11)').text('3');
            tabletotal1();
        });
        $('#tableorderview tbody').on('click', '.btnDeleteNewProduct', function (e) {
            var row = $(this).closest('tr');
            row.remove();
            tabletotal1();
        });


        function tabletotal1() {
            var sum = 0;
            var totallinediscount = 0;
            var count = 0;
            $(".totwithoutdiscount").each(function () {
                var row = $(this).closest('tr');
                var status = row.find('td:nth-child(11)').text();

                if (status == 3) {
                    return;
                }
                var cleansum = $(this).text().split(",").join("")
                sum += parseFloat(cleansum);
            });

            $(".totwithoutdiscount").each(function () {
                var row = $(this).closest('tr');
                var status = row.find('td:nth-child(11)').text();
                if (status != 3) {
                    count++;
                }
            });

            $(".editlinediscount").each(function () {
                var row = $(this).closest('tr');
                var status = row.find('td:nth-child(11)').text();

                if (status == 3) {
                    return;
                }
                var cleantotallinediscount = $(this).text().split(",").join("")
                totallinediscount += parseFloat(cleantotallinediscount);
            });

            var showsum = addCommas(parseFloat(sum).toFixed(2));
            var showlinediscount = addCommas(parseFloat(totallinediscount).toFixed(2));


            var podiscountPercent = $('#editpodiscount').val();
            var poDiscount = ((sum - totallinediscount) * podiscountPercent) / 100;

            var showPoDiscount = addCommas(parseFloat(poDiscount).toFixed(2));

            var fulldiscount = totallinediscount + poDiscount;
            var netTot = sum - fulldiscount;

            var shownet = addCommas(parseFloat(netTot).toFixed(2));

            $('#divitemcountview').html(count);
            $('#divsubtotalview').html(showsum);
            $('#divtotalview').html(shownet);
            $('#divdiscountview').html(showlinediscount);
            $('#divdiscountPOview').html(showPoDiscount);
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


        // $('#modalorderview').on('hidden.bs.modal', function (e) {
        //     $('#tableorderview > tbody').html('');
        // });
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
                checkCommon()
            }
        });
        $("#btncuspoupdate").click(function () {
            if (!$("#editcusporderform")[0].checkValidity()) {
                $("#hiddeneditsubmit").click();
            } else {
                var orderdate = $('#editorderdate').val();
                var customerid = $('#editcustomer').val();
                var salesrepId = $('#editsalesrep').val();
                var porderId = $('#hiddencustomerpoid').val();

                $.ajax({
                    type: "POST",
                    data: {
                        orderdate: orderdate,
                        customerid: customerid,
                        salesrepId: salesrepId,
                        porderId: porderId
                    },
                    url: 'process/updatecustomerporder.php',
                    success: function (result) { // alert(result)
                        var obj = JSON.parse(result);
                        action(obj);
                        setTimeout(function () {
                            window.location.reload();
                        }, 1500);
                    }
                });
            }
        });

        
        function checkCommon() {
            var productID = $('#product').val();
            var existsflag = 0;

            $(".productIds").each(function () {
                var id = $(this).text()
                if (productID == id) {
                    existsflag = 1;
                }
            });

            if (existsflag == 1) {
                var productname = $("#product option:selected").text();

                $('#errordiv').empty().html(
                    "  <div class='alert alert-warning alert-dismissible fade show' role='alert'><h5 id = 'errormessage'></h5><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>"
                )
                $('#errormessage').html("The product '" + productname + " is already selected")
            } else {
                checkStock();
            }
        }

        function checkStock() {
            var productID = $('#product').val();
            var newqty = parseFloat($('#newqty').val());

            $.ajax({
                type: "POST",
                data: {
                    productID: productID,
                    usingqty: newqty
                },
                url: 'getprocess/checkavailablestock.php',
                success: function (result) { //alert(result)
                    var obj = JSON.parse(result);
                    if (obj.availableqty >= newqty) {
                        checkTarget()
                    } else {
                        var productname = $("#product option:selected").text();

                        $('#errordiv').empty().html(
                            "  <div class='alert alert-danger alert-dismissible fade show' role='alert'><h5 id = 'errormessage'></h5><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>"
                        )
                        $('#errormessage').html(
                            "There is not enough stock available for product '" + productname)
                    }
                }
            });
        }

        function checkTarget() {
            var productID = $('#product').val();
            var product = $("#product option:selected").text();
            var repname = $("#repname option:selected").text();
            var repID = $('#repname').val();
            var orderdate = $('#orderdate').val();

            prodCount++;
            var discount = parseFloat($('#discountpresentage').val());
            var discountpo = parseFloat($('#discountpo').val());
            var productID = $('#product').val();
            var product = $("#product option:selected").text();
            var unitprice = parseFloat($('#unitprice').val());
            var saleprice = parseFloat($('#saleprice').val());
            var newqty = parseFloat($('#newqty').val());

            var freeqty = parseFloat($('#freeqty').val());

            var freeproductname = $('#freeproductname').val();
            var freeproductid = $('#freeproductid').val();

            var newtotal = parseFloat(saleprice * newqty);
            var linediscount = (newtotal * discount) / 100;

            var totalqty = parseFloat(freeqty + newqty);

            var fulltotwihoutdiscount = parseFloat(newtotal);
            var total = parseFloat(newtotal - linediscount);
            var showtotal = addCommas(parseFloat(total).toFixed(2));
            var showlinediscount = addCommas(parseFloat(linediscount).toFixed(2));

            $('#tableorder > tbody:last').append('<tr class="pointer"><td>' + prodCount + '</td><td>' +
                product +
                '</td><td class="d-none productIds">' + productID +
                '</td><td class="d-none">' + unitprice +
                '</td><td class="d-none">' + saleprice +
                '</td><td class="text-center">' + newqty + '</td><td class="d-none">' +
                freeproductname + '</td><td class="d-none">' + freeproductid +
                '</td><td class="text-center d-none">' + freeqty +
                '</td><td class="text-right">' + addCommas(parseFloat(saleprice).toFixed(2)) +
                '</td><td class="text-right">' + showlinediscount +
                '</td><td class="linediscount d-none">' + linediscount +
                '</td><td class="d-none">' + total +
                '</td><td class="total d-none">' + fulltotwihoutdiscount +
                '</td><td class="text-right">' + showtotal +
                '</td><td><button type="button" class="btn btn-danger btn-sm btndlt"><i class="fas fa-trash-alt"></i></td></tr>'
            );

            $('#product').val('');
            $('#unitprice').val('');
            $('#saleprice').val('');
            $('#newqty').val('0');
            $('#freeqty').val('0');
            $('#freeproductname').val('');
            $('#freeproductid').val('0');
            // $('#discountpo').val('0');

            var sum = 0;
            $(".total").each(function () {
                sum += parseFloat($(this).text());
            });


            var disvalue = (sum * discount) / 100;
            var nettotal = sum - disvalue;
            var disvaluepo = (nettotal * discountpo) / 100;
            // var newdis = disvalue + disvaluepo;
            var nettotal = nettotal - disvaluepo;

            var showsum = addCommas(parseFloat(sum).toFixed(2));
            var showdis = addCommas(parseFloat(disvalue).toFixed(2));
            var showdispo = addCommas(parseFloat(disvaluepo).toFixed(2));
            var shownettotal = addCommas(parseFloat(nettotal).toFixed(2));

            $('#divsubtotal').html('Rs. ' + showsum);
            $('#hidetotalorder').val(sum);
            $('#divdiscount').html('Rs. ' + showdis);
            $('#divdiscountPO').html('Rs. ' + showdispo);
            $('#hidediscount').val(disvalue);
            $('#hidediscountPO').val(disvaluepo);
            $('#divtotal').html('Rs. ' + shownettotal);
            $('#hidenettotalorder').val(nettotal);
            $('#product').focus();
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
                //console.log(jsonObj);
                jsonObj = JSON.stringify(jsonObj);

                var discountpresentage = $('#discountpresentage').val();
                var orderdate = $('#orderdate').val();
                var remark = $('#remark').val();
                var repname = $('#repname').val();
                var area = $('#area').val();
                var location = $('#location').val();
                var customer = $('#customer').val();
                // var directcustomer = $('#directcustomer').val();
                var directcustomer = '';
                var total = $('#hidetotalorder').val();
                var discount = $('#hidediscount').val();
                var podiscount = $('#discountpo').val();
                var podiscountamount = $('#hidediscountPO').val();
                var nettotal = $('#hidenettotalorder').val();
                var customeraddress = $('#customeraddress').val();
                var customercontact = $('#customercontact').val();
                var recordOption = $('#recordOption').val();
                var recordID = $('#recordID').val();
                //  alert(recordOption);
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
                        location: location,
                        customer: customer,
                        customercontact: customercontact,
                        customeraddress: customeraddress,
                        directcustomer: directcustomer,
                        podiscount: podiscount,
                        podiscountamount: podiscountamount,
                        recordOption: recordOption,
                        recordID: recordID
                    },
                    url: 'process/customerporderprocess.php',
                    success: function (result) { //console.log(result);
                        $('#modalcreateorder').modal('hide');
                        action(result);
                        setTimeout(function () {
                            window.location.reload();

                        }, 1500);
                    }
                });
            }
        });

        $('#btnUpdate').click(function () {
            jsonObj = [];
            $('#btnUpdate').prop('disabled', true);
            
            $("#tableorderview tbody tr").each(function () {
                item = {}
                $(this).find('td').each(function (col_idx) {
                    item["col_" + (col_idx + 1)] = $(this).text();
                });
                jsonObj.push(item);
            });
            jsonObj = JSON.stringify(jsonObj);


            var poID = $('#hiddenpoid').val();
            var podiscountprecentage = $('#editpodiscount').val();
            var acceptanceType = $('#acceptanceType').val();
            var remarkVal = $('#remarkview').val()

            var discount = $('#divdiscountview').text();
            var cleandiscount = discount.split(",").join("")

            var nettotal = $('#divtotalview').text();
            var clearnettotal = nettotal.split(",").join("")

            var total = $('#divsubtotalview').text();
            var cleartotal = total.split(",").join("")
            var statusValue = $('#statusValue').is(':checked') ? 1 : 0;

            var podiscountAmount = $('#divdiscountPOview').text();
            var clearPodiscountAmount = podiscountAmount.split(",").join("")

            $.ajax({
                type: "POST",
                data: {
                    poID: poID,
                    tableData: jsonObj,
                    acceptanceType: acceptanceType,
                    discount: cleandiscount,
                    nettotal: clearnettotal,
                    total: cleartotal,
                    podiscountPrecentage: podiscountprecentage,
                    podiscountAmount: clearPodiscountAmount,
                    remarkVal: remarkVal,
                    isChangeStatus: statusValue
                },
                url: 'process/updatecustomerpoprocess.php',
                success: function (result) { console.log(result);
                    action(result);
                    $('#modalorderview').modal('hide');

                    $('#dataTable').DataTable().ajax.reload();
                    location.reload();
                }
            });

        });

        $('#tableorder').on('click', '.btndlt', function () {

            var r = confirm("Are you sure, You want to remove this product ? ");
            if (r == true) {
                var row = $(this).closest('tr');
                row.remove();

                calculateTotals(row);
                $('#product').focus();
            }
        });

        // $('#tableorder').on('click', 'tr', function() {
        //     var r = confirm("Are you sure, You want to remove this product ? ");
        //     if (r == true) {
        //         $(this).closest('tr').remove();

        //         var sum = 0;
        //         $(".total").each(function() {
        //             sum += parseFloat($(this).text());
        //         });

        //         var showsum = addCommas(parseFloat(sum).toFixed(2));

        //         $('#divtotal').html('Rs. ' + showsum);
        //         $('#hidetotalorder').val(sum);
        //         $('#product').focus();
        //     }
        // });

        $("#discountpo").keyup(function () {
            if ($(this).val() != '') {
                var discount = parseFloat($(this).val());
            } else {
                var discount = 0;
            }


            var sum = 0;
            var disvalue = 0;
            $(".total").each(function () {
                sum += parseFloat($(this).text());
            });
            $(".linediscount").each(function () {
                disvalue += parseFloat($(this).text());
            });

            var totVal = $('#hidetotalorder').val();
            var netTotal = sum - disvalue;

            var totpodiscount = (netTotal * discount) / 100;
            netTotal = netTotal - totpodiscount;

            var showpodiscount = addCommas(parseFloat(totpodiscount).toFixed(2));
            var shownettotal = addCommas(parseFloat(netTotal).toFixed(2));

            $('#divtotal').html('Rs.' + shownettotal);
            $('#hidenettotalorder').val(netTotal);

            $('#divdiscountPO').html('Rs. ' + showpodiscount);
            $('#hidediscountPO').val(totpodiscount);

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