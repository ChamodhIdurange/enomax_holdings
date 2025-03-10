<?php 
include "include/header.php";  

$sql="SELECT * FROM `tbl_porder` WHERE `confirmstatus` IN (1,0,2)";
$result =$conn-> query($sql); 

$sqlcommonnames="SELECT DISTINCT `common_name` FROM `tbl_product` WHERE `status`=1";
$resultcommonnames =$conn-> query($sqlcommonnames); 

$sqlproduct="SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1";
$resultproduct =$conn-> query($sqlproduct); 
$aresultproduct =$conn-> query($sqlproduct); 

$sqlmaterial="SELECT `idtbl_material`, `materialname` FROM `tbl_material` WHERE `status`=1";
$resultmaterial =$conn-> query($sqlmaterial); 

$sqlbank="SELECT `idtbl_bank`, `bankname` FROM `tbl_bank` WHERE `status`=1 AND `idtbl_bank`>1";
$resultbank =$conn-> query($sqlbank); 

$sqlvehicle="SELECT `idtbl_vehicle`, `vehicleno` FROM `tbl_vehicle` WHERE `type`=0 AND `status`=1";
$resultvehicle =$conn-> query($sqlvehicle); 

$sqlvehicletrailer="SELECT `idtbl_vehicle`, `vehicleno` FROM `tbl_vehicle` WHERE `type`=1 AND `status`=1";
$resultvehicletrailer =$conn-> query($sqlvehicletrailer); 

$sqldiverlist="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `tbl_user_type_idtbl_user_type`=4 AND `status`=1";
$resultdiverlist =$conn-> query($sqldiverlist);

$sqlofficerlist="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `tbl_user_type_idtbl_user_type`=6 AND `status`=1";
$resultofficerlist =$conn-> query($sqlofficerlist);

$sqlhelperlist="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `tbl_user_type_idtbl_user_type`=5 AND `status`=1";
$resulthelperlist =$conn-> query($sqlhelperlist);

$sqlexpensetype="SELECT `idtbl_expences_type`, `expencestype` FROM `tbl_expences_type` WHERE `status`=1";
$resultexpensetype =$conn-> query($sqlexpensetype);

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
                            <div class="page-header-icon"><i data-feather="list"></i></div>
                            <span>Purchsing Order</span>
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
                                        <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right ml-2"
                                            id="btncheckreorder"><i class="fas fa-plus"></i>&nbsp;Check Reorder</button>
                                        <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right ml-2"
                                            id="btnordercreate"><i class="fas fa-plus"></i>&nbsp;Create Purchsing
                                            Order</button>
                                        <!-- <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right ml-2"
                                            id="btnmaterialcreate"><i class="fas fa-plus"></i>&nbsp;Create Material
                                            Order</button>
                                        <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right"
                                            id="btnAssemble"><i class="fas fa-plus"></i>&nbsp;Assemble
                                            Product</button> -->
                                    </div>
                                </div>
                                <hr>
                                <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Order No</th>
                                            <th>Request</th>
                                            <th class="text-right">Nettotal</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>
<!-- Modal Material Order -->
<div class="modal fade" id="modalmaterialorder" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header p-2">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                        <form id="materialorderform" autocomplete="off">
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold text-dark">Order Date*</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control dpd1a" placeholder="" name="morderdate"
                                        id="morderdate" required>
                                    <div class="input-group-append">
                                        <span class="btn btn-light border-gray-500"><i
                                                class="far fa-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold text-dark">Material*</label>
                                <select class="form-control form-control-sm" name="material" id="material">
                                    <option value="">Select</option>
                                    <?php if($resultmaterial->num_rows > 0) {while ($rowmaterial = $resultmaterial-> fetch_assoc()) { ?>
                                    <option value="<?php echo $rowmaterial['idtbl_material'] ?>">
                                        <?php echo $rowmaterial['materialname'] ?></option>
                                    <?php }} ?>
                                </select>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Qty*</label>
                                    <input type="text" id="mnewqty" name="mnewqty" class="form-control form-control-sm"
                                        required>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Unit Price</label>
                                    <input type="text" id="munitprice" name="munitprice"
                                        class="form-control form-control-sm" value="0" readonly>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <button type="button" id="formmaterialsubmit"
                                    class="btn btn-outline-primary btn-sm fa-pull-right"
                                    <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-plus"></i>&nbsp;Add
                                    Material</button>
                                <input name="msubmitBtn" type="submit" value="Save" id="msubmitBtn" class="d-none">
                            </div>
                            <input type="hidden" name="msaleprice" id="msaleprice" value="">
                            <input type="hidden" name="mrefillprice" id="mrefillprice" value="">
                        </form>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-8 col-xl-8">
                        <table class="table table-striped table-bordered table-sm small" id="tablematerialorder">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="d-none">ProductID</th>
                                    <th class="d-none">Unitprice</th>
                                    <th class="d-none">Saleprice</th>
                                    <th class="text-center">Qty</th>
                                    <th class="d-none">HideTotal</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <div class="row">
                            <div class="col text-right">
                                <h1 class="font-weight-600" id="mdivtotal">Rs. 0.00</h1>
                            </div>
                            <input type="hidden" id="mhidetotalorder" value="0">
                        </div>
                        <hr>
                        <div class="form-group">
                            <label class="small font-weight-bold text-dark">Remark</label>
                            <textarea name="mremark" id="mremark" class="form-control form-control-sm"></textarea>
                        </div>
                        <div class="form-group mt-2">
                            <button type="button" id="mbtncreateorder"
                                class="btn btn-outline-primary btn-sm fa-pull-right"
                                <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-save"></i>&nbsp;Create
                                Order</button>
                        </div>
                        <div class="form-group mt-3 errordiv">
                            <span class="badge badge-danger mr-2">&nbsp;&nbsp;</span> Stock quantity warning
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Create Order -->
<div class="modal fade" id="modalcreateorder" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header p-2">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
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
                                <label class="small font-weight-bold text-dark">Common name*</label>
                                <select class="form-control form-control-sm" name="productcommonname"
                                    id="productcommonname">
                                    <option value="">Select</option>
                                    <?php if($resultcommonnames->num_rows > 0) {while ($rowcommonname = $resultcommonnames-> fetch_assoc()) { ?>
                                    <option value="<?php echo $rowcommonname['common_name'] ?>">
                                        <?php echo $rowcommonname['common_name'] ?></option>
                                    <?php }} ?>
                                </select>
                            </div>
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold text-dark">Product*</label>
                                <select class="form-control form-control-sm" name="product" id="product">
                                    <option value="">Select</option>
                                </select>
                            </div>
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold text-dark">Supplier</label>
                                <input type="text" id="modalsupplier" name="modalsupplier"
                                    class="form-control form-control-sm" value="" readonly>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Qty*</label>
                                    <input type="text" id="newqty" name="newqty" class="form-control form-control-sm"
                                        required>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Unit Price</label>
                                    <input type="text" id="unitprice" name="unitprice"
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
                            <input type="hidden" name="saleprice" id="saleprice" value="">
                            <input type="hidden" name="refillprice" id="refillprice" value="">
                        </form>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-8 col-xl-8">
                        <table class="table table-striped table-bordered table-sm small" id="tableorder">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="d-none">ProductID</th>
                                    <th class="d-none">Unitprice</th>
                                    <th class="d-none">Saleprice</th>
                                    <th class="text-center">Qty</th>
                                    <th class="d-none">HideTotal</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <div class="row">
                            <div class="col text-right">
                                <h1 class="font-weight-600" id="divtotal">Rs. 0.00</h1>
                            </div>
                            <input type="hidden" id="hidetotalorder" value="0">
                        </div>
                        <hr>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Assemble Order -->
<div class="modal fade" id="modalassembleorder" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header p-2">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                        <form id="acreateorderform" autocomplete="off">
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold text-dark">Order Date*</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control dpd1a" placeholder="" name="aorderdate"
                                        id="aorderdate" required>
                                    <div class="input-group-append">
                                        <span class="btn btn-light border-gray-500"><i
                                                class="far fa-calendar"></i></span>
                                    </div>
                                </div>

                                <div class="form-group mb-2">
                                    <label class="small font-weight-bold text-dark">Product*</label>
                                    <select class="form-control form-control-sm" name="aproduct" id="aproduct">
                                        <option value="">Select</option>
                                        <?php if($aresultproduct->num_rows > 0) {while ($rowproduct = $aresultproduct-> fetch_assoc()) { ?>
                                        <option value="<?php echo $rowproduct['idtbl_product'] ?>">
                                            <?php echo $rowproduct['product_name'] ?></option>
                                        <?php }} ?>
                                    </select>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Qty*</label>
                                        <input type="text" id="anewqty" name="anewqty"
                                            class="form-control form-control-sm" required>
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <button type="button" id="aformsubmit"
                                        class="btn btn-outline-primary btn-sm fa-pull-right"
                                        <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-plus"></i>&nbsp;Add
                                        Product</button>
                                    <input name="asubmitBtn" type="submit" value="Save" id="asubmitBtn" class="d-none">
                                </div>

                        </form>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-8 col-xl-8">
                        <table class="table table-striped table-bordered table-sm small" id="assembletable">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="d-none">ProductID</th>
                                    <th class="d-none">Unitprice</th>
                                    <th class="d-none">Saleprice</th>
                                    <th class="text-center">Qty</th>
                                    <th class="d-none">HideTotal</th>
                                    <th class="text-right d-none">Total</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <hr>
                        <div class="form-group">
                            <label class="small font-weight-bold text-dark">Remark</label>
                            <textarea name="aremark" id="aremark" class="form-control form-control-sm"></textarea>
                        </div>
                        <div class="form-group mt-2">
                            <button type="button" id="abtncreateorder"
                                class="btn btn-outline-primary btn-sm fa-pull-right"
                                <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-save"></i>&nbsp;Create
                                Order</button>
                        </div>
                        <div class="form-group mt-3 text-danger small">
                            <span class="badge badge-danger mr-2">&nbsp;&nbsp;</span> Stock quantity warning
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
            <div class="modal-body">
                <table class="table table-striped table-bordered table-sm small" id="tableorderview">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th class="d-none">ProductID</th>
                            <th class="text-center"> Qty</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <div class="row">
                    <div class="col-12 text-right">
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
<!-- Modal cheque view -->
<div class="modal fade" id="modalcheque" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h6 class="modal-title" id="">Last week bill Information</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="tablelastbillshow"></div>
                <input type="hidden" name="chequehideorderid" id="chequehideorderid" value="">
                <div class="row">
                    <div class="col-12">
                        <button class="btn btn-outline-primary btn-sm px-4 fa-pull-right" id="btnproceedcheque"><i
                                class="fas fa-plus"></i>&nbsp;Proceed to cheque</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal order view -->
<div class="modal fade" id="modalotherinfo" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h6 class="modal-title" id="">Vehicle Information</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form id="otherform" method="post">
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold text-dark">Company Lorries*</label><br>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="companylorry1" name="companylorry"
                                        class="custom-control-input" value="1">
                                    <label class="custom-control-label font-weight-bold" for="companylorry1">Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="companylorry2" name="companylorry"
                                        class="custom-control-input" value="0" checked>
                                    <label class="custom-control-label font-weight-bold" for="companylorry2">No</label>
                                </div>
                            </div>
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold text-dark">Distributor Lorries*</label><br>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="distributorlorry1" name="distributorlorry"
                                        class="custom-control-input" value="1">
                                    <label class="custom-control-label font-weight-bold"
                                        for="distributorlorry1">Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="distributorlorry2" name="distributorlorry"
                                        class="custom-control-input" value="0" checked>
                                    <label class="custom-control-label font-weight-bold"
                                        for="distributorlorry2">No</label>
                                </div>
                            </div>
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold text-dark">Lorry No*</label>
                                <select name="lorrynum" id="lorrynum" class="form-control form-control-sm" required>
                                    <option value="">Select</option>
                                    <?php if($resultvehicle->num_rows > 0) {while ($rowvehicle = $resultvehicle-> fetch_assoc()) { ?>
                                    <option value="<?php echo $rowvehicle['idtbl_vehicle'] ?>">
                                        <?php echo $rowvehicle['vehicleno'] ?></option>
                                    <?php }} ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="small font-weight-bold text-dark">Trailer*</label>
                                <select name="trailernum" id="trailernum" class="form-control form-control-sm" required>
                                    <option value="">Select</option>
                                    <?php if($resultvehicletrailer->num_rows > 0) {while ($rowvehicletrailer = $resultvehicletrailer-> fetch_assoc()) { ?>
                                    <option value="<?php echo $rowvehicletrailer['idtbl_vehicle'] ?>">
                                        <?php echo $rowvehicletrailer['vehicleno'] ?></option>
                                    <?php }} ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="small font-weight-bold text-dark">Time*</label>
                                <input type="time" class="form-control form-control-sm" name="scheduletime"
                                    id="scheduletime" required>
                            </div>
                            <div class="form-group mt-2">
                                <button type="button" id="btnothersubmit"
                                    class="btn btn-outline-primary btn-sm fa-pull-right"
                                    <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-plus"></i>&nbsp;Add
                                    Delivery</button>
                                <input name="othersubmitBtn" type="submit" id="othersubmitBtn" class="d-none">
                                <input name="otherresetBtn" type="reset" id="otherresetBtn" class="d-none">
                            </div>
                            <input type="hidden" name="otherhideorderid" id="otherhideorderid" value="">
                            <input type="hidden" name="recordOption" id="recordOption" value="1">
                            <input type="hidden" name="recordID" id="recordID" value="">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Dispatch Create -->
<div class="modal fade" id="modalcreatedispatch" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h5 class="modal-title" id="staticBackdropLabel">Create Dispatch</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <table class="table table-striped table-bordered table-sm small" id="tabledispatch">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="d-none">ProductID</th>
                                    <th class="d-none">UnitPrice</th>
                                    <th class="d-none">Refillprice</th>
                                    <th class="d-none">Newsaleprice</th>
                                    <th class="d-none">Refillsaleprice</th>
                                    <th class="text-center">Refill Qty</th>
                                    <th class="text-center">New Qty</th>
                                    <th class="text-center">Return Qty</th>
                                    <th class="text-center">Trust Qty</th>
                                    <th class="text-center">Safty Qty</th>
                                    <th class="text-center">Safty Return</th>
                                    <th class="d-none">HideTotal</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody id="tbodydispatchcreate"></tbody>
                        </table>
                        <div class="row">
                            <div class="col text-right">
                                <h1 class="font-weight-600" id="divtotaldispatch">Rs. 0.00</h1>
                            </div>
                            <input type="hidden" id="hidetotalorderdispatch" value="0">
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <hr>
                                <form id="formdispatchdriverofficer">
                                    <div class="form-row">
                                        <div class="col-3">
                                            <label class="small font-weight-bold text-dark">Driver*</label>
                                            <select name="drivername" id="drivername"
                                                class="form-control form-control-sm" required>
                                                <option value="">Select</option>
                                                <?php if($resultdiverlist->num_rows > 0) {while ($rowdiverlist = $resultdiverlist-> fetch_assoc()) { ?>
                                                <option value="<?php echo $rowdiverlist['idtbl_employee'] ?>">
                                                    <?php echo $rowdiverlist['name'] ?></option>
                                                <?php }} ?>
                                            </select>
                                        </div>
                                        <div class="col-3">
                                            <label class="small font-weight-bold text-dark">Officer*</label>
                                            <select name="officername" id="officername"
                                                class="form-control form-control-sm" required>
                                                <option value="">Select</option>
                                                <?php if($resultofficerlist->num_rows > 0) {while ($rowofficerlist = $resultofficerlist-> fetch_assoc()) { ?>
                                                <option value="<?php echo $rowofficerlist['idtbl_employee'] ?>">
                                                    <?php echo $rowofficerlist['name'] ?></option>
                                                <?php }} ?>
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <label class="small font-weight-bold text-dark">Helper*</label><br>
                                            <select name="helpername[]" id="helpername"
                                                class="form-control form-control-sm" style="width:100%;" multiple
                                                required>
                                                <?php if($resulthelperlist->num_rows > 0) {while ($rowhelperlist = $resulthelperlist-> fetch_assoc()) { ?>
                                                <option value="<?php echo $rowhelperlist['idtbl_employee'] ?>">
                                                    <?php echo $rowhelperlist['name'] ?></option>
                                                <?php }} ?>
                                            </select>
                                        </div>
                                        <input type="submit" class="d-none" id="driverofficersubmitBtn">
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="form-group mt-2">
                            <hr>
                            <button type="button" id="btncreatedispatch"
                                class="btn btn-outline-primary btn-sm fa-pull-right"
                                <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-save"></i>&nbsp;Create
                                Dispatch</button>
                            <input type="hidden" id="hidelorrynum" value=''>
                            <input type="hidden" id="hidetrailernum" value=''>
                            <input type="hidden" id="hideorderid" value=''>
                        </div>
                    </div>
                </div>
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
<!-- Modal Dispatch View -->
<div class="modal fade" id="modaldispatchdetail" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="dispatchprint"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger btn-sm fa-pull-right" id="btndispatchprint"><i
                        class="fas fa-print"></i>&nbsp;Print Dispatch</button>
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
<!-- Modal Porder grn detail -->
<div class="modal fade" id="pordergrndetail" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-white text-center">
                <div id="pordergrnbody"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Other Expenses -->
<div class="modal fade" id="modalotherexpenses" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="expensetitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <form id="formexpenses">
                            <div class="form-row mb-2">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Expense Type*</label>
                                    <select class="form-control form-control-sm" name="expentype" id="expentype"
                                        required>
                                        <option value="">Select</option>
                                        <?php if($resultexpensetype->num_rows > 0) {while ($rowexpensetype = $resultexpensetype-> fetch_assoc()) { ?>
                                        <option value="<?php echo $rowexpensetype['idtbl_expences_type'] ?>">
                                            <?php echo $rowexpensetype['expencestype'] ?></option>
                                        <?php }} ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Expense Value*</label>
                                    <input type="text" class="form-control form-control-sm" placeholder=""
                                        name="expenvalue" id="expenvalue" required>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <button type="button" id="btnaddexpenses"
                                    class="btn btn-outline-primary btn-sm fa-pull-right"
                                    <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-save"></i>&nbsp;Add
                                    Expenses</button>
                                <input type="submit" class="d-none" id="hideexpensessubmit">
                            </div>
                            <input type="hidden" id="hideorderexpenses" value="">
                        </form>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <div id="expensestable"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Check Reorder -->
<div class="modal fade" id="reordermodal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Check Reorder</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-white text-center">
                <div id="reorderpobody"></div>
            </div>
        </div>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
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

        $('#dataTable').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "scripts/porderlist.php",
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
                    "data": "name"
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
                    "className": 'text-right',
                    "data": null,
                    "render": function (data, type, full) {
                        var button = '';
                        button +=
                            '<button class="btn btn-outline-primary btn-sm mr-1 btnprint" data-toggle="tooltip" data-placement="bottom" title="Print Order" id="' +
                            full['idtbl_porder'] + '" ';
                        if (full['confirmstatus'] == 0 | full['confirmstatus'] == 2) {
                            button += 'disabled';
                        }
                        button +=
                            '><i class="fas fa-print"></i></button><button class="btn btn-outline-dark btn-sm mr-1 btncheque" data-toggle="tooltip" data-placement="bottom" title="Previous Bill Info" id="' +
                            full['idtbl_porder'] + '" ';
                        if (full['confirmstatus'] == 0 | full['confirmstatus'] == 2) {
                            button += 'disabled';
                        }
                        button += '><i class="fas fa-file-invoice-dollar"></i></button>';


                        button += '<button class="btn btn-outline-dark btn-sm mr-1 btnView ';
                        if (editcheck == 0) {
                            button += 'd-none';
                        }
                        button +=
                            '" data-toggle="tooltip" data-placement="bottom" title="View Order" id="' +
                            full['idtbl_porder'] + '"><i class="far fa-eye"></i></button>';


                        if (full['grnissuestatus'] == 1) {
                            button +=
                                '<button class="btn btn-outline-success btn-sm mr-1 btnGrn ';
                            if (editcheck == 0) {
                                button += 'd-none';
                            }

                        } else {
                            button +=
                                '<button class="btn btn-outline-danger btn-sm mr-1 btnGrn ';
                            if (editcheck == 0) {
                                button += 'd-none';
                            }
                        }

                        button +=
                            '" data-toggle="tooltip" data-placement="bottom" title="View GRN Details" name="' +
                            full['grnissuestatus'] +
                            '" id="' +
                            full['idtbl_porder'] +
                            '"><i class="fa fa-check-square"></i></button>';

                        button +=
                            '<button class="btn btn-outline-primary btn-sm mr-1 btnexpenses ';
                        if (editcheck == 0) {
                            button += 'd-none';
                        }
                        button +=
                            '" data-toggle="tooltip" data-placement="bottom" title="Other Expenses" id="' +
                            full['idtbl_porder'] +
                            '"><i class="fas fa-dollar-sign"></i></button>';

                        if (full['confirmstatus'] == 1) {
                            button += '<button class="btn btn-outline-success btn-sm mr-1 ';
                            if (statuscheck == 0) {
                                button += 'd-none';
                            }
                            button += '"><i class="fas fa-check"></i></button>';
                        } else {
                            button += '<a href="process/statusporder.php?record=' + full[
                                    'idtbl_porder'] +
                                '&type=1" onclick="return order_confirm()" target="_self" class="btn btn-outline-orange btn-sm mr-1 ';
                            if (statuscheck == 0 | full['confirmstatus'] == 2) {
                                button += 'd-none';
                            }
                            button += '"><i class="fas fa-times"></i></a>';
                        }

                        button += '<a href="process/statusporder.php?record=' + full[
                                'idtbl_porder'] +
                            '&type=2" onclick="return delete_confirm()" target="_self" class="btn btn-outline-danger btn-sm mr-1 ';
                        if (statuscheck == 0) {
                            button += 'd-none';
                        }
                        button += '"><i class="far fa-trash-alt"></i></a>';

                        return button;
                    }
                }
            ]
        });

        $(window).keydown(function (event) {
            // alert("a")
            if (event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });

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
        $("#newqty").keyup(function (event) {

            if (event.keyCode === 13) {
                $("#formsubmit").click();
            }
        });

        $("#mnewqty").keyup(function (event) {

            if (event.keyCode === 13) {
                $("#formmaterialsubmit").click();
            }
        });
        $("#anewqty").keyup(function (event) {

            if (event.keyCode === 13) {
                $("#aformsubmit").click();
            }
        });

        $('.dpd1a').datepicker({
            uiLibrary: 'bootstrap4',
            autoclose: 'true',
            todayHighlight: true,
            startDate: 'today',
            format: 'yyyy-mm-dd'
        });
        // Prodcut part
        $('#product').change(function () {
            var productID = $(this).val();
            $.ajax({
                type: "POST",
                data: {
                    productID: productID
                },
                url: 'getprocess/getsalpriceaccoproduct.php',
                success: function (result) { //alert(result);
                    var obj = JSON.parse(result);
                    $('#unitprice').val(obj.unitprice);
                    $('#saleprice').val(obj.saleprice);
                    $('#modalsupplier').val(obj.suppliername);

                    $('#newqty').focus();
                    $('#newqty').select();
                }
            });
        });

        $('#material').change(function () {
            var materialId = $(this).val();

            $.ajax({
                type: "POST",
                data: {
                    materialId: materialId
                },
                url: 'getprocess/getsalpriceaccomaterial.php',
                success: function (result) { //alert(result);
                    var obj = JSON.parse(result);
                    $('#munitprice').val(obj.unitprice);
                    $('#msaleprice').val(obj.saleprice);

                    $('#mnewqty').focus();
                    $('#mnewqty').select();
                }
            });
        });

        // Order view part
        $('#dataTable tbody').on('click', '.btnView', function () {
            var id = $(this).attr('id');
            $.ajax({
                type: "POST",
                data: {
                    orderID: id
                },
                url: 'getprocess/getorderlistaccoorderid.php',
                success: function (result) { //alert(result);
                    var obj = JSON.parse(result);

                    $('#divtotalview').html(obj.nettotalshow);
                    $('#remarkview').html(obj.remark);
                    $('#viewmodaltitle').html('Order No: PO-' + id);

                    var objfirst = obj.tablelist;
                    $.each(objfirst, function (i, item) {
                        //alert(objfirst[i].id);

                        $('#tableorderview > tbody:last').append('<tr><td>' +
                            objfirst[i].productname +
                            '</td><td class="d-none">' + objfirst[i].productid +
                            '</td><td class="text-center">' + objfirst[i]
                            .newqty + '</td><td class="text-right total">' +
                            objfirst[i].total + '</td></tr>');
                    });
                    $('#modalorderview').modal('show');
                }
            });
        });

        $('#dataTable tbody').on('click', '.btnGrn', function () {
            var id = $(this).attr('id');
            var grnIssueStatus = $(this).attr('name');
            $.ajax({
                type: "POST",
                data: {
                    porderID: id,
                    grnIssueStatus: grnIssueStatus
                },
                url: 'getprocess/getpordergrndetails.php',
                success: function (result) {
                    //alert(result)
                    $('#pordergrnbody').html(result);
                    $('#pordergrndetail').modal('show');
                }
            });
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
                url: 'getprocess/getorderprint.php',
                success: function (result) {
                    $('#viewdispatchprint').html(result);
                    $('#modalorderprint').modal('show');
                }
            });
        });
        document.getElementById('btnorderprint').addEventListener("click", print);
        // Order cheque part
        $('#dataTable tbody').on('click', '.btncheque', function () {
            var id = $(this).attr('id');
            $.ajax({
                type: "POST",
                data: {},
                url: 'getprocess/getlastweektotalaccocustomer.php',
                success: function (result) { //alert(result);
                    $('#tablelastbillshow').html(result);
                    $('#modalcheque').modal('show');
                }
            });

            $('#chequehideorderid').val(id);
        });
        // Create order part
        $('#btnordercreate').click(function () {
            // alert("asdasd");
            $('#modalcreateorder').modal('show');
            $('#modalcreateorder').on('shown.bs.modal', function () {
                $('#orderdate').trigger('focus');
            })
        });
        $('#btnmaterialcreate').click(function () {
            // alert("asdasd");
            $('#modalmaterialorder').modal('show');
            $('#modalmaterialorder').on('shown.bs.modal', function () {
                $('#morderdate').trigger('focus');
            })
        });

        $('#btnAssemble').click(function () {
            // alert("asdasd");
            $('#modalassembleorder').modal('show');
            $('#modalassembleorder').on('shown.bs.modal', function () {
                $('#aorderdate').trigger('focus');
            })
        });

        $('#btncheckreorder').click(function () {
            // alert("asdasd");
            $('#reordermodal').modal('show');
            $.ajax({
                type: "POST",
                url: 'getprocess/fetchporeorderdata.php',
                success: function (result) { //alert(result)
                    $('#reorderpobody').html(result);

                }
            })

        });
        $('#modalcreateorder').on('hidden.bs.modal', function () {
            $('#orderdate').val('');
            $('#product').val('');
            $('#unitprice').val('');
            $('#saleprice').val('');
            $('#newqty').val('0');
            $('#remark').val('');
            $('#divtotal').html('Rs. 0.00');
            $('#hidetotalorder').val('0');
            $('#tableorder > tbody').html('');
        })
        $('#orderdate').change(function () {
            $('#product').focus();
        });
        $("#formsubmit").click(function () {
            if (!$("#createorderform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#submitBtn").click();
            } else {
                var productID = $('#product').val();
                var product = $("#product option:selected").text();
                var unitprice = parseFloat($('#unitprice').val());
                var saleprice = parseFloat($('#saleprice').val());
                var newqty = parseFloat($('#newqty').val());

                var newtotal = parseFloat(unitprice * newqty);

                var total = parseFloat(newtotal);
                var showtotal = addCommas(parseFloat(total).toFixed(2));

                $('#tableorder > tbody:last').append('<tr class="pointer"><td>' + product +
                    '</td><td class="d-none">' + productID + '</td><td class="d-none">' +
                    unitprice + '</td><td class="d-none">' + saleprice +
                    '</td><td class="text-center">' + newqty + '</td><td class="total d-none">' +
                    total + '</td><td class="text-right">' + showtotal + '</td></tr>');

                $('#product').val('');
                $('#unitprice').val('');
                $('#saleprice').val('');
                $('#newqty').val('0');

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
        $("#aformsubmit").click(function () {
            if (!$("#acreateorderform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#asubmitBtn").click();
            } else {
                var productID = $('#aproduct').val();
                var product = $("#aproduct option:selected").text();
                var newqty = parseFloat($('#anewqty').val());

                var newtotal = parseFloat(unitprice * newqty);

                var total = parseFloat(newtotal);
                var showtotal = addCommas(parseFloat(total).toFixed(2));


                $('#assembletable > tbody:last').append('<tr class="pointer"><td>' + product +
                    '</td><td class="d-none">' + productID + '</td><td class="d-none">' +
                    0 + '</td><td class="d-none">' + 0 +
                    '</td><td class="text-center">' + newqty + '</td><td class="mtotal d-none">' +
                    0 + '</td><td class="text-right d-none">' + 0 + '</td></tr>');

                $('#aproduct').val('');
                $('#anewqty').val('0');

                $('#aproduct').focus();
            }
        });
        $("#formmaterialsubmit").click(function () {
            if (!$("#materialorderform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#msubmitBtn").click();
            } else {
                var materialId = $('#material').val();
                var material = $("#material option:selected").text();
                var unitprice = parseFloat($('#munitprice').val());
                var saleprice = parseFloat($('#msaleprice').val());
                var newqty = parseFloat($('#mnewqty').val());

                var newtotal = parseFloat(unitprice * newqty);

                var total = parseFloat(newtotal);
                var showtotal = addCommas(parseFloat(total).toFixed(2));

                $('#tablematerialorder > tbody:last').append('<tr class="pointer"><td>' + material +
                    '</td><td class="d-none">' + materialId + '</td><td class="d-none">' +
                    unitprice + '</td><td class="d-none">' + saleprice +
                    '</td><td class="text-center">' + newqty + '</td><td class="mtotal d-none">' +
                    total + '</td><td class="text-right">' + showtotal + '</td></tr>');

                $('#material').val('');
                $('#munitprice').val('');
                $('#msaleprice').val('');
                $('#mnewqty').val('0');

                var sum = 0;
                $(".mtotal").each(function () {
                    sum += parseFloat($(this).text());
                });

                var showsum = addCommas(parseFloat(sum).toFixed(2));

                $('#mdivtotal').html('Rs. ' + showsum);
                $('#mhidetotalorder').val(sum);
                $('#mproduct').focus();
            }
        });
        $('#btncreateorder').click(function () { //alert('IN');

            if (!$("#createorderform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#submitBtn").click();
            } else {
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

                    var orderdate = $('#orderdate').val();
                    var remark = $('#remark').val();
                    var total = $('#hidetotalorder').val();

                    $.ajax({
                        type: "POST",
                        data: {
                            tableData: jsonObj,
                            orderdate: orderdate,
                            total: total,
                            ordertype: '0',
                            remark: remark
                        },
                        url: 'process/porderprocess.php',
                        success: function (result) { //alert(result);
                            $('#modalcreateorder').modal('hide');
                            action(result);
                            location.reload();
                        }
                    });
                }
            }
        });
        $('#abtncreateorder').click(function () { //alert('IN');
            var tbody = $("#assembletable tbody");

            if (tbody.children().length > 0) {
                jsonObj = [];
                $("#assembletable tbody tr").each(function () {
                    item = {}
                    $(this).find('td').each(function (col_idx) {
                        item["col_" + (col_idx + 1)] = $(this).text();
                    });
                    jsonObj.push(item);
                });
                // console.log(jsonObj);

                var orderdate = $('#aorderdate').val();
                var remark = $('#aremark').val();

                $.ajax({
                    type: "POST",
                    data: {
                        tableData: jsonObj,
                        orderdate: orderdate,
                        total: '0',
                        ordertype: '0',
                        remark: remark,
                        assembledpo: '1',
                    },
                    url: 'process/porderprocess.php',
                    success: function (result) { //alert(result);
                        action(result);
                        location.reload();
                    }
                });
            }
        });
        $('#mbtncreateorder').click(function () { //alert('IN');
            if (!$("#materialorderform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#msubmitBtn").click();
            } else {
                var tbody = $("#tablematerialorder tbody");

                if (tbody.children().length > 0) {
                    jsonObj = [];
                    $("#tablematerialorder tbody tr").each(function () {
                        item = {}
                        $(this).find('td').each(function (col_idx) {
                            item["col_" + (col_idx + 1)] = $(this).text();
                        });
                        jsonObj.push(item);
                    });
                    // console.log(jsonObj);

                    var orderdate = $('#morderdate').val();
                    var remark = $('#mremark').val();
                    var total = $('#mhidetotalorder').val();

                    $.ajax({
                        type: "POST",
                        data: {
                            tableData: jsonObj,
                            orderdate: orderdate,
                            total: total,
                            ordertype: '1',
                            remark: remark
                        },
                        url: 'process/porderprocess.php',
                        success: function (result) { //alert(result);
                            $('#modalcreateorder').modal('hide');
                            action(result);
                            location.reload();
                        }
                    });
                }
            }

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
        // Create cheque part
        $("#btnproceedcheque").click(function () {
            var tbody = $("#lastweektable tbody");

            if (tbody.children().length > 0) {
                jsonObjDispatch = [];
                $("#lastweektable tbody tr").each(function () {
                    item = {}
                    $(this).find('td').each(function (col_idx) {
                        item["col_" + (col_idx + 1)] = $(this).text();
                    });
                    jsonObjDispatch.push(item);
                });


                // console.log(jsonObjDispatch);
                var tableData = jsonObjDispatch;
                var orderID = $('#chequehideorderid').val();
                var billtotal = $('#previousbilltotal').val();
                $.ajax({
                    type: "POST",
                    data: {
                        tableData: tableData,
                        orderID: orderID,
                        billtotal: billtotal
                    },
                    url: 'process/orderchequeprocess.php',
                    success: function (result) {
                        // console.log(result);
                        action(result);
                        $('#modalcheque').modal('hide');
                        location.reload();
                    }
                });
            }
        });
        $('#modalcheque').on('hidden.bs.modal', function () {
            $('#lastweekbillcheck').prop('checked', false);
            $('.collapse').collapse('hide');
            $('#tablelastbillshow').html('');
        });
        $('#dataTable tbody').on('click', '.btnexpenses', function () {
            var id = $(this).attr('id');
            $('#hideorderexpenses').val(id);

            $('#expensetitle').html('PO-' + id + ' Expenses');
            $('#modalotherexpenses').modal('show');

            loadexpensestable(id);
        });
        $('#btnaddexpenses').click(function () {
            if (!$("#formexpenses")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hideexpensessubmit").click();
            } else {
                var expentype = $('#expentype').val();
                var expenvalue = $('#expenvalue').val();
                var orderID = $('#hideorderexpenses').val();

                $.ajax({
                    type: "POST",
                    data: {
                        expentype: expentype,
                        expenvalue: expenvalue,
                        orderID: orderID
                    },
                    url: 'process/porderexpensesprocess.php',
                    success: function (result) { //alert(result);
                        $('#modalcreateorder').modal('hide');
                        action(result);
                        loadexpensestable(orderID);
                    }
                });
            }
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

    function chequeoption(orderID) {
        $('#chequeinfotable tbody').on('click', '.btnchequeremove', function () {
            var r = confirm("Are you sure, You want to Remove this ? ");
            if (r == true) {
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    data: {
                        record: id,
                        type: '3'
                    },
                    url: 'process/statusordercheque.php',
                    success: function (result) { //alert(result);
                        loadcheckinfo(orderID)
                    }
                });
            }
        });
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

    function loadexpensestable(id) {
        $.ajax({
            type: "POST",
            data: {
                orderID: id
            },
            url: 'getprocess/getoerderexpensesaccoorder.php',
            success: function (result) { //alert(result);
                $('#expensestable').html(result);
                expensesoption();
            }
        });
    }

    function expensesoption() {
        $('#expenlisttable tbody').on('click', '.btnexpendelete', function () {
            var r = confirm("Are you sure, You want to remove this ? ");
            if (r == true) {
                var orderID = $('#hideorderexpenses').val();
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    data: {
                        expensesid: id
                    },
                    url: 'process/statusporderexpenses.php',
                    success: function (result) { //alert(result);
                        action(result);
                        loadexpensestable(orderID)
                    }
                });
            }
        });
    }
</script>
<?php include "include/footer.php"; ?>