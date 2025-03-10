<head>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">
</head>
<?php 
include "include/header.php";  

$sql="SELECT `idtbl_customer`, `name`, `nic`, `phone`, `status`, `type`, `tbl_area_idtbl_area` FROM `tbl_customer` WHERE `status` IN (1,2)";
$result =$conn-> query($sql); 

$sqlemployee="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `status` IN (1,2)";
$resultemployee =$conn-> query($sqlemployee); 

$productarray=array();
$sqlproduct="SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1";
$resultproduct =$conn-> query($sqlproduct); 
while ($rowproduct = $resultproduct-> fetch_assoc()) {
    $obj=new stdClass();
    $obj->productID=$rowproduct['idtbl_product'];
    $obj->product=$rowproduct['product_name'];

    array_push($productarray, $obj);
}

$sqlarea="SELECT `idtbl_area`, `area` FROM `tbl_area` WHERE `status`=1";
$resultarea =$conn-> query($sqlarea); 

$sqlcustomer="SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE `status`=1 ORDER BY `name` ASC";
$resultcustomer =$conn-> query($sqlcustomer); 

$sqlproduct="SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1";
$resultproduct =$conn-> query($sqlproduct); 

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
                            <div class="page-header-icon"><i data-feather="users"></i></div>
                            <span>Customer</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-4">
                                <form action="process/customerprocess.php" method="post" autocomplete="off"  enctype="multipart/form-data">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Customer Name*</label>
                                        <input type="text" class="form-control form-control-sm" id="cusName"
                                            name="cusName" required>
                                    </div>
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Customer Type*</label>
                                            <select name="cusType" id="cusType" class="form-control form-control-sm"
                                                required>
                                                <option value="">Select</option>
                                                <option value="1">Co-operate</option>
                                                <option value="2">Retail</option>
                                                <!-- <option value="3">Laugfs Agent</option> -->
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">NIC</label>
                                            <input type="text" class="form-control form-control-sm" id="cusNic"
                                                name="cusNic" placeholder="">
                                        </div>
                                    </div>
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Area*</label>
                                            <select name="cusArea" id="cusArea" class="form-control form-control-sm"
                                                required>
                                                <option value="">Select</option>
                                                <?php if($resultarea->num_rows > 0) {while ($rowarea = $resultarea-> fetch_assoc()) { ?>
                                                <option value="<?php echo $rowarea['idtbl_area'] ?>">
                                                    <?php echo $rowarea['area'] ?></option>
                                                <?php }} ?>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Mobile*</label>
                                            <input type="text" class="form-control form-control-sm" id="cusMobile"
                                                name="cusMobile" required>
                                        </div>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Address</label>
                                        <textarea class="form-control form-control-sm" id="address"
                                            name="address"></textarea>
                                    </div>
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Vat Num</label>
                                            <input type="text" class="form-control form-control-sm" id="cusVatNum"
                                                name="cusVatNum" placeholder="">
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">S-Vat</label>
                                            <input type="text" class="form-control form-control-sm" id="cusSVat"
                                                name="cusSVat" placeholder="">
                                        </div>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Email</label>
                                        <input type="email" class="form-control form-control-sm" id="cusEmail"
                                            name="cusEmail">
                                    </div>
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Payment person</label>
                                            <input type="text" class="form-control form-control-sm" id="personpayment"
                                                name="personpayment" placeholder="">
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Mobile*</label>
                                            <input type="text" class="form-control form-control-sm" id="paymentmobile"
                                                name="paymentmobile" required>
                                        </div>
                                    </div>
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Delivery person</label>
                                            <input type="text" class="form-control form-control-sm" id="deliveryperson"
                                                name="deliveryperson" placeholder="">
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Mobile*</label>
                                            <input type="text" class="form-control form-control-sm" id="deliverymobile"
                                                name="deliverymobile" required>
                                        </div>
                                    </div>
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Credit Type</label>
                                            <div class="input-group input-group-sm">
                                                <select class="form-control" id="cuscredittype" name="cuscredittype">
                                                    <option value="">Select</option>
                                                    <option value="1">Bill To Bill</option>
                                                    <option value="2">Credit Days</option>
                                                    <option value="3">Cash</option>
                                                </select>
                                                <input type="text" class="form-control" id="cuscreditdays"
                                                    name="cuscreditdays" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Credit Limit</label>
                                            <input type="text" class="form-control form-control-sm" id="cusCreditlimit"
                                                name="cusCreditlimit" placeholder="">
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">No of visit days</label>
                                            <input type="text" class="form-control form-control-sm" id="cusNoVisit"
                                                name="cusNoVisit" placeholder="">
                                        </div>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Remarks</label>
                                        <input type="text" class="form-control form-control-sm" id="remarks"
                                            name="remarks">
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Ref</label>
                                        <select name="ref" id="ref" class="form-control form-control-sm" required>
                                            <option value="">Select</option>
                                            <?php if($resultemployee->num_rows > 0) {while ($rowarea = $resultemployee-> fetch_assoc()) { ?>
                                            <option value="<?php echo $rowarea['idtbl_employee'] ?>">
                                                <?php echo $rowarea['name'] ?></option>
                                            <?php }} ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Comment box</label>
                                        <textarea class="form-control form-control-sm" id="comment"
                                            name="comment"></textarea>
                                    </div>
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Buisness Copy</label>
                                            <input type="file" name="buisnesscopy" id="buisnesscopy"
                                                class="form-control form-control-sm" style="padding-bottom:32px;">
                                            <small id="" class="form-text text-danger">Image size 800X800 Pixel</small>
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Dealer Board</label>
                                            <input type="file" name="dealerboard" id="dealerboard"
                                                class="form-control form-control-sm" style="padding-bottom:32px;"
                                                >
                                            <small id="" class="form-text text-danger">Image size 452X452 Pixel</small>
                                        </div>
                                    </div>
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Selfie</label>
                                            <input type="file" name="selfie" id="selfie"
                                                class="form-control form-control-sm" style="padding-bottom:32px;">
                                            <small id="" class="form-text text-danger">Image size 800X800 Pixel</small>
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Product Image</label>
                                            <input type="file" name="productimage" id="productimage"
                                                class="form-control form-control-sm" style="padding-bottom:32px;"
                                                >
                                            <small id="" class="form-text text-danger">Image size 452X452 Pixel</small>
                                        </div>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Day</label>
                                        <select name="cusVisitDays[]" id="cusVisitDays"
                                            class="form-control form-control-sm" multiple>
                                            <option value="1">Monday</option>
                                            <option value="2">Tuesday</option>
                                            <option value="3">Wednesday</option>
                                            <option value="4">Thursday</option>
                                            <option value="5">Friday</option>
                                            <option value="6">Saturday</option>
                                            <option value="7">Sunday</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Form Code</label>
                                        <input type="text" class="form-control form-control-sm" id="formcode"
                                            name="formcode" placeholder="">
                                    </div>
                                    <div class="form-group mt-2">
                                        <button type="submit" id="submitBtn"
                                            class="btn btn-outline-primary btn-sm px-4 fa-pull-right"
                                            <?php if($addcheck==0){echo 'disabled';} ?>><i
                                                class="far fa-save"></i>&nbsp;Add</button>
                                    </div>
                                    <input type="hidden" name="recordOption" id="recordOption" value="1">
                                    <input type="hidden" name="recordID" id="recordID" value="">
                                </form>
                            </div>
                            <div class="col-8">
                                <div class="row">
                                    <div class="col">
                                        <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right"
                                            id="btnassignref"><i class="fas fa-plus"></i>&nbsp;Assign Ref</button>
                                    </div>
                                </div>
                                <div class="scrollbar pb-3 mt-4" id="style-2">
                                    <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Area</th>
                                                <th>Address</th>
                                                <th>Type</th>
                                                <th>Sales Ref</th>
                                                <th>NIC</th>
                                                <th>Contact</th>
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
<!-- Modal Product Price List For Co-operate Customer -->
<div class="modal fade" id="modaladdproductprice" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h5 class="modal-title" id="staticBackdropLabel">Add Product Price</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <form id="addproductform" autocomplete="off">
                            <div class="form-row">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Product*</label>
                                    <select name="productlist" id="productlist" class="form-control form-control-sm"
                                        required>
                                        <option value="">Select</option>
                                        <?php foreach($productarray as $rowprocutlist) { ?>
                                        <option value="<?php echo $rowprocutlist->productID ?>">
                                            <?php echo $rowprocutlist->product ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <label class="small font-weight-bold text-dark">Sale Price*</label>
                                    <input type="text" class="form-control form-control-sm" id="newsaleprice"
                                        name="newsaleprice" required>
                                </div>
                            </div>
                            <div class="form-group mt-2">
                                <button type="button" id="submitmodalBtn"
                                    class="btn btn-outline-primary btn-sm px-4 fa-pull-right"
                                    <?php if($addcheck==0){echo 'disabled';} ?>><i
                                        class="far fa-save"></i>&nbsp;Add</button>
                                <input type="submit" class="d-none" id="hidesubmit" value="">
                            </div>
                            <input type="hidden" name="hidecusid" id="hidecusid" value="">
                        </form>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <div id="viewenterlist"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Product Stock Qty For Retail Customer -->
<div class="modal fade" id="modaladdproductstock" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h5 class="modal-title" id="staticBackdropLabel">Add Product Buffer Stock</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <form id="addproductstockform" autocomplete="off">
                            <div class="form-row">
                                <div class="col-7">
                                    <label class="small font-weight-bold text-dark">Product*</label>
                                    <select name="productliststock" id="productliststock"
                                        class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                        <?php foreach($productarray as $rowprocutlist) { ?>
                                        <option value="<?php echo $rowprocutlist->productID ?>">
                                            <?php echo $rowprocutlist->product ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Full Qty*</label>
                                    <input type="text" class="form-control form-control-sm" id="fullqty" name="fullqty"
                                        required>
                                </div>
                                <!-- <div class="col">
                                    <label class="small font-weight-bold text-dark">Empty Qty*</label>
                                    <input type="text" class="form-control form-control-sm" id="emptyqty" name="emptyqty" required>
                                </div> -->
                                <input type="hidden" class="form-control form-control-sm" id="emptyqty" name="emptyqty"
                                    value="0">
                            </div>
                            <div class="form-group mt-2">
                                <button type="button" id="submitstockmodalBtn"
                                    class="btn btn-outline-primary btn-sm px-4 fa-pull-right"
                                    <?php if($addcheck==0){echo 'disabled';} ?>><i
                                        class="far fa-save"></i>&nbsp;Add</button>
                                <input type="submit" class="d-none" id="hidestocksubmit" value="">
                            </div>
                            <input type="hidden" name="hidestockcusid" id="hidestockcusid" value="">
                        </form>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <div id="viewenterstocklist"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Customer Shop Close -->
<div class="modal fade" id="modalshopclose" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h5 class="modal-title" id="staticBackdropLabel">Close Dealer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-3">
                        <form id="dealercloseform">
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Customer*</label>
                                    <select name="customerclose" id="customerclose" class="form-control form-control-sm"
                                        disabled>
                                        <option value="">Select</option>
                                        <?php if($resultcustomer->num_rows > 0) {while ($rowcustomer = $resultcustomer-> fetch_assoc()) { ?>
                                        <option value="<?php echo $rowcustomer['idtbl_customer'] ?>">
                                            <?php echo $rowcustomer['name'] ?></option>
                                        <?php }} ?>
                                    </select>
                                    <input type="hidden" name="hidecustomerclose" id="hidecustomerclose" value="">
                                </div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Product*</label>
                                    <select name="productclose" id="productclose" class="form-control form-control-sm"
                                        required>
                                        <option value="">Select</option>
                                        <?php if($resultproduct->num_rows > 0) {while ($rowproduct = $resultproduct-> fetch_assoc()) { ?>
                                        <option value="<?php echo $rowproduct['idtbl_product'] ?>">
                                            <?php echo $rowproduct['product_name'] ?></option>
                                        <?php }} ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Full Qty*</label>
                                    <input type="text" class="form-control form-control-sm" id="fullqtyclose"
                                        name="fullqtyclose" value="0" required>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Empty Qty*</label>
                                    <input type="text" class="form-control form-control-sm" id="emtyqtyclose"
                                        name="emtyqtyclose" value="0" required>
                                </div>
                            </div>
                            <input type="hidden" name="hidenewprice" id="hidenewprice" value="">
                            <input type="hidden" name="hideemptyprice" id="hideemptyprice" value="">
                            <div class="form-group mt-2">
                                <button type="button" id="submitbtnclose"
                                    class="btn btn-outline-primary btn-sm px-4 fa-pull-right"
                                    <?php if($addcheck==0){echo 'disabled';} ?>><i
                                        class="far fa-save"></i>&nbsp;Add</button>
                                <input type="submit" class="d-none" id="hideclosesubmit" value="">
                            </div>
                        </form>
                    </div>
                    <div class="col-9">
                        <table class="table table-striped table-bordered table-sm" id="tableclosecustomer">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="d-none">ProductID</th>
                                    <th class="d-none">New Price</th>
                                    <th class="d-none">Empty Price</th>
                                    <th class="text-center">Full Qty</th>
                                    <th class="text-center">Empty Qty</th>
                                    <th class="d-none">Total</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <div class="row">
                            <div class="col text-right">
                                <h1 class="font-weight-600" id="divtotal">Rs. 0.00</h1>
                            </div>
                            <input type="hidden" id="hidetotalinvoice" value="0">
                        </div>
                        <div class="row">
                            <div class="col-6">&nbsp;</div>
                            <div class="col-6">
                                <button class="btn btn-outline-primary btn-sm fa-pull-right mt-2 px-4"
                                    id="btndealerclose">Dealer Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Customer Shop Close View -->
<div class="modal fade" id="modalshopcloseview" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h5 class="modal-title" id="staticBackdropLabel">Close Dealer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div id="dealercloseviewinfo"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal assign ref -->
<div class="modal fade" id="modalassignref" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h5 class="modal-title" id="staticBackdropLabel">Assign ref</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id = "assignmodalbody">
                
            </div>
        </div>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>

<script>
    $(document).ready(function () {
        var addcheck = '<?php echo $addcheck; ?>';
        var editcheck = '<?php echo $editcheck; ?>';
        var statuscheck = '<?php echo $statuscheck; ?>';
        var deletecheck = '<?php echo $deletecheck; ?>';

        $("#cusVisitDays").select2();
        // $('#tableassignref').DataTable({
        //     paging: false,
        //     ordering: false,
        //     info: false,
        // })

        $('#cuscredittype').change(function () {
            var type = $(this).val();
            if (type == 2) {
                $('#cuscreditdays').prop('readonly', false);
            } else {
                $('#cuscreditdays').prop('readonly', true);
            }
        });

        $('#dataTable').DataTable({
            dom: 'Blfrtip',
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "scripts/customerlist.php",
                type: "POST", // you can use GET
            },
            "order": [
                [0, "desc"]
            ],
            "columns": [{
                    "data": "idtbl_customer"
                },
                {
                    "data": "name"
                },
                {
                    "data": "area"
                },
                {
                    "data": "address"
                },
                {
                    "targets": -1,
                    "className": 'text-center',
                    "data": null,
                    "render": function (data, type, full) {
                        var html = '';
                        if (full['type'] == 1) {
                            html += 'Co-operate';
                        } else if (full['type'] == 2) {
                            html += 'Retail';
                        } else {
                            html += 'Laugfs Agent';
                        }

                        return html;
                    }
                },
                {
                    "data": "empname"
                },
                {
                    "data": "nic"
                },
                {
                    "data": "phone"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function (data, type, full) {
                        var button = '';
                        button += '<a href="customerprofile.php?record=' + full[
                                'idtbl_customer'] +
                            '&type=1"  target="_self" class="btn btn-outline-primary btn-sm "><i class="far fa-eye"></i></a>'
                        if (full['type'] == 2) {
                            button +=
                                '<button class="btn btn-outline-dark btn-sm btnAddProductStock mr-1 ';
                            if (addcheck == 0) {
                                button += 'd-none';
                            }
                            button += '" id="' + full['idtbl_customer'] +
                                '"><i class="fas fa-warehouse"></i></button>';
                        }
                        if (full['type'] == 1 | full['type'] == 3) {
                            button +=
                                '<button class="btn btn-outline-purple btn-sm btnAddProduct mr-1 ';
                            if (addcheck == 0) {
                                button += 'd-none';
                            }
                            button += '" id="' + full['idtbl_customer'] +
                                '"><i class="fas fa-shopping-cart"></i></button>';
                        }
                        button += '<button class="btn btn-outline-primary btn-sm btnEdit mr-1 ';
                        if (editcheck == 0) {
                            button += 'd-none';
                        }
                        button += '" id="' + full['idtbl_customer'] +
                            '"><i class="fas fa-pen"></i></button>';
                        if (full['status'] == 1) {
                            button += '<a href="process/statuscustomer.php?record=' + full[
                                    'idtbl_customer'] +
                                '&type=2" onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 ';
                            if (statuscheck == 0) {
                                button += 'd-none';
                            }
                            button += '"><i class="fas fa-check"></i></a>';
                        } else if (full['status'] != 5) {
                            button += '<a href="process/statuscustomer.php?record=' + full[
                                    'idtbl_customer'] +
                                '&type=1" onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 ';
                            if (statuscheck == 0) {
                                button += 'd-none';
                            }
                            button += '"><i class="fas fa-times"></i></a>';
                        }
                        if (full['status'] == 1) {
                            button +=
                                '<button type="button" class="btn btn-outline-pink btn-sm mr-1 btnclose ';
                            if (deletecheck == 0) {
                                button += 'd-none';
                            }
                            button += '" id="' + full['idtbl_customer'] +
                                '"><i class="fas fa-times-circle"></i></button>';
                            button += '<a href="process/statuscustomer.php?record=' + full[
                                    'idtbl_customer'] +
                                '&type=4" onclick="return emergancyactive_confirm()" target="_self" class="btn btn-outline-dark btn-sm mr-1 ';
                            if (statuscheck == 0) {
                                button += 'd-none';
                            }
                            button += '"><i class="far fa-calendar-check"></i></a>';
                        } else if (full['status'] == 5) {
                            button +=
                                '<button type="button" class="btn btn-outline-purple btn-sm mr-1 btncloseview" id="' +
                                full['idtbl_customer'] +
                                '"><i class="fas fa-file"></i></button>';
                        }
                        if (full['status'] != 5) {
                            button += '<a href="process/statuscustomer.php?record=' + full[
                                    'idtbl_customer'] +
                                '&type=3" onclick="return delete_confirm()" target="_self" class="btn btn-outline-danger btn-sm ';
                            if (deletecheck == 0) {
                                button += 'd-none';
                            }
                            button += '"><i class="far fa-trash-alt"></i></a>';
                        }

                        return button;
                    }
                }
            ]
        });
        $('#dataTable tbody').on('click', '.btnEdit', function () {
            var r = confirm("Are you sure, You want to Edit this ? ");
            if (r == true) {
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id
                    },
                    url: 'getprocess/getcustomer.php',
                    success: function (result) { //alert(result);
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#cusName').val(obj.name);
                        $('#cusType').val(obj.type);
                        $('#cusNic').val(obj.nic);
                        $('#cusMobile').val(obj.phone);
                        $('#address').val(obj.address);
                        $('#cusVatNum').val(obj.vat_num);
                        $('#cusSVat').val(obj.svat);
                        $('#cusEmail').val(obj.email);
                        $('#cusArea').val(obj.area);
                        $('#cusNoVisit').val(obj.nodays);
                        $('#cusCreditlimit').val(obj.credit);
                        $('#cuscredittype').val(obj.credittype);
                        $('#cuscreditdays').val(obj.creditperiod);
                        $('#formcode').val(obj.formcode);

                        $('#remarks').val(obj.remarks);
                        $('#ref').val(obj.ref);
                        $('#comment').val(obj.comment);
                        $('#personpayment').val(obj.paymentpersonname);
                        $('#paymentmobile').val(obj.paymentpersonmobile);
                        $('#deliveryperson').val(obj.deliverypersonname);
                        $('#deliverymobile').val(obj.deliverypersonmobile);

                        if (obj.credittype == 2) {
                            $('#cuscreditdays').prop('readonly', false);
                        } else {
                            $('#cuscreditdays').prop('readonly', true);
                        }

                        var dayslist = obj.dayslist;
                        var dayslistoption = [];
                        $.each(dayslist, function (i, item) {
                            dayslistoption.push(dayslist[i].daysID);
                        });

                        $('#cusVisitDays').val(dayslistoption);
                        $('#cusVisitDays').trigger('change');

                        $('#recordOption').val('2');
                        $('#buisnesscopy').prop('required', false);
                        $('#dealerboard').prop('required', false);
                        $('#selfie').prop('required', false);
                        $('#productimage').prop('required', false);
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                    }
                });
            }
        });
        $('#dataTable tbody').on('click', '.btnAddProduct', function () {
            var id = $(this).attr('id');
            loadproductpricelist(id);
            $('#hidecusid').val(id);
            $('#modaladdproductprice').modal('show');
        });
        $('#submitmodalBtn').click(function () {
            if (!$("#addproductform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmit").click();
            } else {
                var productID = $('#productlist').val();
                var newsaleprice = $('#newsaleprice').val();
                var refillsaleprice = $('#refillsaleprice').val();
                var hidecusid = $('#hidecusid').val();

                $.ajax({
                    type: "POST",
                    data: {
                        productID: productID,
                        newsaleprice: newsaleprice,
                        refillsaleprice: refillsaleprice,
                        hidecusid: hidecusid
                    },
                    url: 'process/customerproductpriceprocess.php',
                    success: function (result) { //alert(result);
                        action(result);
                        loadproductpricelist(hidecusid);

                        $('#productlist').val('');
                        $('#newsaleprice').val('');
                        $('#refillsaleprice').val('');
                    }
                });
            }
        });
        $('#modaladdproductprice').on('hidden.bs.modal', function (e) {
            $('#viewenterlist').html('');
        });

        $('#dataTable tbody').on('click', '.btnAddProductStock', function () {
            var id = $(this).attr('id');
            loadproductstocklist(id);
            $('#hidestockcusid').val(id);
            $('#modaladdproductstock').modal('show');
        });
        $('#submitstockmodalBtn').click(function () {
            if (!$("#addproductstockform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidestocksubmit").click();
            } else {
                var productID = $('#productliststock').val();
                var fullqty = $('#fullqty').val();
                var emptyqty = $('#emptyqty').val();
                var hidecusid = $('#hidestockcusid').val();

                $.ajax({
                    type: "POST",
                    data: {
                        productID: productID,
                        fullqty: fullqty,
                        emptyqty: emptyqty,
                        hidecusid: hidecusid
                    },
                    url: 'process/customerproductstockprocess.php',
                    success: function (result) { //alert(result);
                        action(result);
                        loadproductstocklist(hidecusid);

                        $('#productliststock').val('');
                        $('#fullqty').val('');
                        $('#emptyqty').val('');
                    }
                });
            }
        });
        // Close shop
        $('#dataTable tbody').on('click', '.btnclose', function () {
            var id = $(this).attr('id');
            $('#customerclose').val(id);
            $('#hidecustomerclose').val(id);
            $('#modalshopclose').modal('show');
        });
        $('#productclose').change(function () {
            var productID = $(this).val();

            $.ajax({
                type: "POST",
                data: {
                    productID: productID
                },
                url: 'getprocess/getclosepricesaccoproduct.php',
                success: function (result) { //alert(result);
                    var obj = JSON.parse(result);
                    $('#hidenewprice').val(obj.newsaleprice);
                    $('#hideemptyprice').val(obj.emptyprice);

                    $('#fullqtyclose').focus();
                    $('#fullqtyclose').select();
                }
            });
        });
        $("#submitbtnclose").click(function () {
            if (!$("#dealercloseform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hideclosesubmit").click();
            } else {
                var productID = $('#productclose').val();
                var product = $("#productclose option:selected").text();
                var newprice = parseFloat($('#hidenewprice').val());
                var emptyprice = parseFloat($('#hideemptyprice').val());
                var fullqty = parseFloat($('#fullqtyclose').val());
                var emptyqty = parseFloat($('#emtyqtyclose').val());

                var newtotal = parseFloat(newprice * fullqty);
                var emptytotal = parseFloat(emptyprice * emptyqty);

                var total = parseFloat(newtotal + emptytotal);
                var showtotal = addCommas(parseFloat(total).toFixed(2));

                $('#tableclosecustomer > tbody:last').append('<tr class="pointer"><td>' + product +
                    '</td><td class="d-none">' + productID + '</td><td class="d-none">' + newprice +
                    '</td><td class="d-none">' + emptyprice + '</td><td class="text-center">' +
                    fullqty + '</td><td class="text-center">' + emptyqty +
                    '</td><td class="total d-none">' + total + '</td><td class="text-right">' +
                    showtotal + '</td></tr>');

                $('#productclose').val('');
                $('#hidenewprice').val('');
                $('#hideemptyprice').val('');
                $('#fullqtyclose').val('0');
                $('#emtyqtyclose').val('0');

                var sum = 0;
                $(".total").each(function () {
                    sum += parseFloat($(this).text());
                });

                var showsum = addCommas(parseFloat(sum).toFixed(2));

                $('#divtotal').html('Rs. ' + showsum);
                $('#hidetotalinvoice').val(sum);
                $('#productclose').focus();
            }
        });
        $('#tableclosecustomer').on('click', 'tr', function () {
            var r = confirm("Are you sure, You want to remove this product ? ");
            if (r == true) {
                $(this).closest('tr').remove();

                var sum = 0;
                $(".total").each(function () {
                    sum += parseFloat($(this).text());
                });

                var showsum = addCommas(parseFloat(sum).toFixed(2));

                $('#divtotal').html('Rs. ' + showsum);
                $('#hidetotalinvoice').val(sum);
                $('#productclose').focus();
            }
        });
        $('#btndealerclose').click(function () {
            jsonObj = [];
            $("#tableclosecustomer tbody tr").each(function () {
                item = {}
                $(this).find('td').each(function (col_idx) {
                    item["col_" + (col_idx + 1)] = $(this).text();
                });
                jsonObj.push(item);
            });
            // console.log(jsonObj);

            var customer = $('#hidecustomerclose').val();
            var total = $('#hidetotalinvoice').val();

            $.ajax({
                type: "POST",
                data: {
                    tableData: jsonObj,
                    customer: customer,
                    total: total
                },
                url: 'process/customercloseprocess.php',
                success: function (result) { //alert(result);
                    $('#modalshopclose').modal('hide');
                    action(result);
                    location.reload();
                }
            });
        });
        $('#dataTable tbody').on('click', '.btncloseview', function () {
            var id = $(this).attr('id');

            $.ajax({
                type: "POST",
                data: {
                    customerID: id
                },
                url: 'getprocess/getclosedealerinformation.php',
                success: function (result) { //alert(result);
                    $('#dealercloseviewinfo').html(result);
                    $('#modalshopcloseview').modal('show');
                }
            });
        });
    });
    

    $('#btnassignref').click(function () {
        $.ajax({
            type: "POST",
            url: 'getprocess/getassigncustomerdetails.php',
            success: function (result) { //alert(result);
                $('#assignmodalbody').html(result);
                $('#modalassignref').modal('show')

            }
        });
    })

    function loadproductpricelist(cusID) {
        var deletecheck = '<?php echo $deletecheck; ?>';
        $.ajax({
            type: "POST",
            data: {
                cusID: cusID,
                deletecheck: deletecheck
            },
            url: 'getprocess/getproductpriceaccocustomer.php',
            success: function (result) { //alert(result);
                $('#viewenterlist').html(result);
                loadlistoption(cusID);
            }
        });
    }

    function loadlistoption(cusID) {
        $('#tableproductlist tbody').on('click', '.btnremoveproduct', function () {
            var r = confirm("Are you sure, You want to Remove this ? ");
            if (r == true) {
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    data: {
                        cusproductID: id
                    },
                    url: 'process/statuscustomerproductprice.php',
                    success: function (result) { //alert(result);
                        action(result);
                        loadproductpricelist(cusID)
                    }
                });
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

    function loadproductstocklist(cusID) {
        var deletecheck = '<?php echo $deletecheck; ?>';
        $.ajax({
            type: "POST",
            data: {
                cusID: cusID,
                deletecheck: deletecheck
            },
            url: 'getprocess/getproductstockaccocustomer.php',
            success: function (result) { //alert(result);
                $('#viewenterstocklist').html(result);
                loadstocklistoption(cusID);
            }
        });
    }

    function loadstocklistoption(cusID) {
        $('#tablestockproductlist tbody').on('click', '.btnremovestockproduct', function () {
            var r = confirm("Are you sure, You want to Remove this ? ");
            if (r == true) {
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    data: {
                        cusproductID: id
                    },
                    url: 'process/statuscustomerproductstock.php',
                    success: function (result) { //alert(result);
                        action(result);
                        loadproductstocklist(cusID)
                    }
                });
            }
        });
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

    function emergancyactive_confirm() {
        return confirm("Are you sure you want to emergency this customer?");
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
</script>
<?php include "include/footer.php"; ?>