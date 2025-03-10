<?php 
include "include/header.php";  

$sqlref="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `status`=1";
$resultref =$conn-> query($sqlref);

$sqlCustomer="SELECT * FROM `tbl_customer` WHERE `status` in ('1')";
$resultCustomer=$conn->query($sqlCustomer);

$sqlLocation="SELECT `idtbl_area`, `area` FROM `tbl_area` WHERE `status` in ('1')";
$resultLocation=$conn->query($sqlLocation);

$sqlcategory="SELECT `idtbl_product_category`, `category` FROM `tbl_product_category` WHERE `status` in ('1')";
$resultcategory=$conn->query($sqlcategory);

$sqlproduct="SELECT `idtbl_product`, `productname` FROM `tbl_product` WHERE `status` in ('1')";
$resultproduct=$conn->query($sqlproduct);

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
                            <div class="page-header-icon"><i data-feather="map-pin"></i></div>
                            <span>Add direct sales</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <form action="" id="directsalesform" autocomplete="off">
                            <div class="row">
                                <div class="form-group  col-md-3">
                                    <label class="small font-weight-bold text-dark">Customer*</label>
                                    <input type="text" class="form-control form-control-sm" name="customername"
                                        id="customername" required>
                                    <input type="hidden" class="form-control form-control-sm" name="customer"
                                        id="customer">
                                </div>
                                <div class="form-group  col-md-3">
                                    <label class="small font-weight-bold text-dark">Contact no*</label>
                                    <input type="text" class="form-control form-control-sm" name="contact" id="contact">
                                </div>
                                <div class="form-group  col-md-3">
                                    <label class="small font-weight-bold text-dark">Address*</label>
                                    <input type="text" class="form-control form-control-sm" name="addresscus"
                                        id="addresscus">
                                </div>
                            </div>
                            <div class="row">

                                <div class="form-group  col-md-3">
                                    <label class="small font-weight-bold text-dark">Location*</label>
                                    <select class="form-control form-control-sm" name="locations" id="locations"
                                        required>
                                        <option value="">Select</option>
                                        <?php if($resultLocation->num_rows > 0) {while ($rowlocation = $resultLocation-> fetch_assoc()) { ?>
                                        <option value="<?php echo $rowlocation['idtbl_area'] ?>">
                                            <?php echo $rowlocation['area'] ?></option>
                                        <?php }} ?>
                                    </select>
                                </div>
                                <div class="form-group  col-md-3">
                                    <label class="small font-weight-bold text-dark">Sales rep*</label>
                                    <select class="form-control form-control-sm" name="salerep" id="salerep" >
                                        <option value="">Select</option>
                                        <?php if($resultref->num_rows > 0) {while ($rowcategory = $resultref-> fetch_assoc()) { ?>
                                        <option value="<?php echo $rowcategory['idtbl_employee'] ?>">
                                            <?php echo $rowcategory['name'] ?></option>
                                        <?php }} ?>
                                    </select>
                                </div>

                                <!-- <div class="form-group  col-md-3">
                                    <label class="small font-weight-bold text-dark">Modal Number*</label>
                                    <input type="text" class="form-control form-control-sm" name="modalnumber"
                                        id="modalnumber" required>
                                </div> -->
                            </div>
                            <div class="row">
                                <div class="form-group  col-md-3">
                                    <label class="small font-weight-bold text-dark">Select product category*</label>
                                    <select class="form-control form-control-sm" name="produtcat" id="produtcat">
                                        <option value="">Select</option>
                                        <?php if($resultcategory->num_rows > 0) {while ($rowcategory = $resultcategory-> fetch_assoc()) { ?>
                                        <option value="<?php echo $rowcategory['idtbl_product_category'] ?>">
                                            <?php echo $rowcategory['category'] ?></option>
                                        <?php }} ?>
                                        <option value="all">All</option>
                                    </select>
                                </div>

                                <div class="form-group  col-md-3">
                                    <label class="small font-weight-bold text-dark">Product*</label>
                                    <select class="form-control form-control-sm" name="product" id="product" required>
                                        <option value="">Select</option>
                                        <!-- <?php if($resultproduct->num_rows > 0) {while ($rowproduct = $resultproduct-> fetch_assoc()) { ?>
                                        <option value="<?php echo $rowproduct['idtbl_product'] ?>">
                                            <?php echo $rowproduct['productname'] ?></option>
                                        <?php }} ?> -->
                                    </select>
                                </div>
                                <div class="form-group  col-md-3">
                                    <label class="small font-weight-bold text-dark">Barcode*</label>
                                    <input type="text" class="form-control form-control-sm" name="barcode" id="barcode"
                                        readonly required>
                                </div>

                                <div class="form-group  col-md-3">
                                    <label class="small font-weight-bold text-dark">Sale price*</label>
                                    <input type="text" class="form-control form-control-sm" name="saleprice"
                                        id="saleprice" required readonly>
                                </div>

                            </div>
                            <div class="row">
                                <div class="form-group  col-md-3">
                                    <label class="small font-weight-bold text-dark">Qty*</label>
                                    <input type="text" class="form-control form-control-sm" name="qty" id="qty"
                                        required>
                                </div>
                                <div class="form-group  col-md-3">
                                    <label class="small font-weight-bold text-dark">Discount(%)*</label>
                                    <input type="text" class="form-control form-control-sm" name="discount"
                                        id="discount" required>
                                </div>
                                <div class="form-group  col-md-3">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="small font-weight-bold text-dark">Warranty(Y)*</label>
                                            <select class="form-control form-control-sm" name="warrantyyears"
                                                id="warrantyyears" required>
                                                <option value="">Select</option>
                                                <option value="1/2">1/2 year</option>
                                                <option value="1">1 year</option>
                                                <option value="2">2 years</option>
                                                <option value="3">3 years</option>
                                                <option value="4">4 years</option>
                                                <option value="5">5 years</option>
                                                <option value="7">7 years</option>
                                                <option value="10">10 years</option>
                                                <option value="15">15 years</option>
                                                <option value="20">20 years</option>
                                                <option value="25">25 years</option>
                                                <option value="0">No warranty</option>
                                            </select>


                                        </div>
                                        <!-- <div class="col-md-6">
                                            <label class="small font-weight-bold text-dark">Warranty(M)*</label>
                                            <select class="form-control form-control-sm" name="warrantymonths"
                                                id="warrantymonths" required>
                                                <option value="">Select</option>
                                                <option value="1">1 month</option>
                                                <option value="2">2 months</option>
                                                <option value="3">3 months</option>
                                                <option value="4">4 months</option>
                                                <option value="5">5 months</option>
                                                <option value="6">6 months</option>
                                                <option value="7">7 months</option>
                                                <option value="8">8 months</option>
                                                <option value="9">9 months</option>
                                                <option value="10">10 months</option>
                                                <option value="11">11 months</option>
                                            </select>
                                        </div> -->

                                    </div>

                                </div>

                                <div class="form-group  col-md-3">
                                    <label class="small font-weight-bold text-dark">Delivery Date*</label>
                                    <input type="date" class="form-control form-control-sm" name="date" id="date"
                                        required>
                                </div>

                            </div>
                            <input name="submitBtn" type="submit" value="Save" id="submitBtn" class="d-none">

                        </form>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mt-3">
                                    <button type="button" id="formsubmit"
                                        class="btn btn-outline-primary btn-sm px-4  col-md-2 fa-pull-right"
                                        <?php if($addcheck==0){echo 'disabled';} ?>><i
                                            class="far fa-save"></i>&nbsp;Add</button>

                                </div>
                            </div>
                        </div>

                        <br>
                        <div class="row">
                            <div class="col-8">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-bordered table-striped table-sm nowrap"
                                        id="directDetails">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Sale price</th>
                                                <th>Qty</th>
                                                <th>Discount</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <!-- <div class="form-check col-md-6">
                                        <input class="form-check-input" type="radio" name="paymenttype"
                                            id="paymenttypecash" value="1" checked>
                                        <label class="form-check-label" for="exampleRadios1">
                                            Cash
                                        </label>
                                    </div>
                                    <div class="form-check col-md-6">
                                        <input class="form-check-input" type="radio" name="paymenttype"
                                            id="paymenttypecredit" value="2">
                                        <label class="form-check-label" for="exampleRadios1">
                                            Credit
                                        </label>
                                    </div> -->
                                    <!-- <div class="form-check col-md-12" style="margin-top: 10px;">
                                        <input type="checkbox" id="vatcheck" class="form-check-inout" value="1">
                                        <label class="small font-weight-bold text-dark" for="">VAT Invoice</label>
                                    </div> -->
                                    <!-- <div class="form-group form-check">
                                        <input type="checkbox" class="form-check-input" id="vat">
                                        <label class="form-check-label" for="vat">VAT invoice</label>
                                    </div> -->

                                    <div class="form-group col-md-6 d-none" id="vatcusdiv">
                                        <label class="small font-weight-bold text-dark">Cus
                                            Name</label>
                                        <input type="text" class="form-control form-control-sm" id="vatcustomer">
                                    </div>
                                    <div class="form-group col-md-6 d-none" id="vatcusregno">
                                        <label class="small font-weight-bold text-dark">Vat
                                            Reg No</label>
                                        <input type="text" class="form-control form-control-sm" id="vatregno">
                                    </div>
                                    <div class="form-group col-md-6 d-none" id="vatcusregno">
                                        <label class="small font-weight-bold text-dark">Vat rate</label>
                                        <input type="text" value="10" class="form-control form-control-sm" id="vatrate">
                                    </div>
                                </div>
                                <div class="row">
                                    <input type="hidden" id="hiddentotal">
                                    <input type="hidden" id="finalhiddentotal">
                                    <div class="form-group col-md-6">
                                        <h4 class=" font-weight-bold text-dark">Sub total*</h4>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <h4 id="divtotal" class=" font-weight-bold text-dark">Rs.0.00*</h4>
                                    </div>
                                    <!-- <div class="col-md-6">

                                        <p class="font-weight-bold text-dark">Dis(%)*</p>
                                        <input class="" type="text" name="billdiscount" id="billdiscount">
                                    </div> -->
                                    <!-- <div class="form-group col-md-6">
                                        <p id="totaldiscount" value="0" class=" font-weight-bold text-dark">Rs.0.00*</p>
                                    </div> -->
                                    <div class="form-group col-md-6">
                                        <h4 class=" font-weight-bold text-dark">Discount*</h4>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <h4 id="eachproductdiscount" class=" font-weight-bold text-dark">Rs.0.00*</h4>
                                    </div>
                                    <input type="hidden" id = "hiddentotaliscount">
                                    <div class="form-group col-md-6">
                                        <h4 class=" font-weight-bold text-dark">Net total*</h4>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <h4 id="netTotal" class=" font-weight-bold text-dark">Rs.0.00*</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <button class="btn btn-outline-primary btn-sm px-4  col-md-4 fa-pull-right"
                                            <?php if($statuscheck==0){echo 'disabled';} ?> type="button"
                                            id="createdirectmodal" name="createdirectmodal"> <i
                                                class="far fa-save"></i>&nbsp;Create</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
    <!-- Modal Invoice View -->
    <div class="modal fade" id="modalinvoice" data-backdrop="static" data-keyboard="false" tabindex="-1"
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
                        <div class="col-12">
                            <div id="divinvoiceview"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-danger btn-sm fa-pull-right" id="btnreceiptprint"><i
                            class="fas fa-print"></i>&nbsp;Print Receipt</button>

                </div>
            </div>
        </div>
    </div>
    <!-- Modal payment -->
    <div class="modal fade" id="modalpayment" data-backdrop="static" data-keyboard="false" tabindex="-1"
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
                            <div id="divinvoiceview">
                                <div class="row">
                                    <div class="col-md-4">
                                        <form id="paymentform">
                                            <div class="row container">
                                                <div class="form-check col-md-6">
                                                    <input class="form-check-input" type="radio" name="paymenttype"
                                                        id="paymenttypecash" value="1" checked>
                                                    <label class="form-check-label" for="exampleRadios1">
                                                        Cash
                                                    </label>
                                                </div>
                                                <div class="form-check col-md-6">
                                                    <input class="form-check-input" type="radio" name="paymenttype"
                                                        id="paymenttypecredit" value="2">
                                                    <label class="form-check-label" for="exampleRadios1">
                                                        Credit
                                                    </label>
                                                </div>
                                                <div class="form-group  col-md-12 mt-2">
                                                    <label class="small font-weight-bold text-dark">Advance
                                                        payment*</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        name="advancepayment" id="advancepayment" required>
                                                </div>
                                                <div class="form-group  col-md-12 mt-2">
                                                    <label class="small font-weight-bold text-dark">Customer Discount*</label>
                                                    <input value = "0" type="text" class="form-control form-control-sm"
                                                        name="customerdiscount" id="customerdiscount" required>
                                                    <input value = "" type="hidden" class="form-control form-control-sm"
                                                        name="hiddenbalance" id="hiddenbalance" required> 
                                                </div>
                                            </div>
                                            <input type="submit" class="d-none" id="paymentsubmit">
                                        </form>

                                        <div class="row">

                                            <div class="col-md-6">
                                                <h6 class=" font-weight-bold text-dark">Sub total*</h6>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 id="paymenttotal" class=" font-weight-bold text-dark">Rs.0.00*</h6>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class=" font-weight-bold text-dark">Discount*</h6>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 id="paymentdiscount" class=" font-weight-bold text-dark">Rs.0.00*
                                                </h6>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class=" font-weight-bold text-dark">Net total*</h6>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 id="paymentnettotal" class=" font-weight-bold text-dark">Rs.0.00*
                                                </h6>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class=" font-weight-bold text-dark">Balance*</h6>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 id="balancetext" class=" font-weight-bold text-dark">Rs.0.00*</h6>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="row">
                                            <table class="table table-bordered table-striped table-sm nowrap"
                                                id="paymenttable">

                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-outline-primary btn-sm fa-pull-right" id="createdirectsale"><i
                            class="fas fa-check"></i>&nbsp;Make Payment</button>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal No quantity Warning -->
    <div class="modal fade" id="noqtymodal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-body bg-danger text-white text-center">
                    <div id="">
                        <h5 id="viewmessage" style="color:white;"></h5>
                    </div>
                </div>
                <div class="modal-footer bg-danger rounded-0">
                    <a class="btn btn-outline-light btn-sm " data-dismiss="modal" aria-label="Close"
                        style="color:white;">Close</a>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- Customer modal-->
<div class="modal fade" id="customermodal" tabindex="-1" role="dialog" aria-labelledby="customerLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="" action="account_balance_update.php" method="post" target="_self" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customerLabel">All customers</h5>&nbsp;
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-12">
                        <div class="scrollbar pb-3" id="style-2">
                            <table class="table table-bordered table-striped table-sm nowrap table-hover"
                                id="customerTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if($resultCustomer->num_rows > 0) {while ($row = $resultCustomer-> fetch_assoc()) { ?>
                                    <tr>
                                        <td><?php echo $row['idtbl_customer'] ?></td>
                                        <td><?php echo $row['name'] ?></td>
                                        <td><?php echo $row['phone'] ?></td>
                                        <td><?php echo $row['email'] ?></td>
                                        <td><?php echo $row['address'] ?></td>
                                    </tr>
                                    <?php }} ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        </form>
    </div>
</div>




<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function () {
        $('#customerTable').DataTable();
        $('#dataTable').DataTable();
        $('#customermodal').modal('show');


        $('#directsalesform').keydown(function (event) {
            if (event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });

        // $("#product").select2({
        //     ajax: {
        //         url: "getprocess/getproductlistforsearch.php",
        //         type: "post",
        //         dataType: 'json',
        //         delay: 250,
        //         data: {
        //             function (params) {
        //                 console.log(params)
        //                 return {
        //                     searchTerm: params.term, // search term
        //                     searchCat: cat
        //                 };
        //             }
        //         },
        //         processResults: function (response) {
        //             console.log(response)
        //             return {
        //                 results: response
        //             };
        //         },
        //         cache: true
        //     }
        // });
    })


    $('#produtcat').change(function () {
        var cat = $(this).val();

        $.ajax({
            type: "POST",
            data: {
                cat: cat
            },
            url: 'getprocess/getproductdetailsaccocategory.php',
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

                $('#product').empty().append(html1);

                $('#saleprice').val("")
                $('#discount').val("")
                $('#barcode').val("")
                $('#modalnumber').val("")
                $("#product").select2({});

            }
        });

    })

    $('#customerTable tbody tr').on('click', function () {
        var customerID = $(this).closest('tr').find('td:eq(0)').text()
        var customerName = $(this).closest('tr').find('td:eq(1)').text()
        var customerContact = $(this).closest('tr').find('td:eq(2)').text()
        var customerAddress = $(this).closest('tr').find('td:eq(4)').text()

        console.log(customerName);

        $('#customername').val(customerName)
        $('#customer').val(customerID)
        $('#contact').val(customerContact)
        $('#addresscus').val(customerAddress)
        $('#addresscus').attr("readonly", true);
        $('#contact').attr("readonly", true);
        $('#customermodal').modal('hide');

    });

    $('#customerTable').on('search.dt', function (e) {
        var value = $('.dataTables_filter input').val();

        if (e.keyCode === 13) {
            alert('ss')
        }

        $('#customername').val(value);
    });


    $('#barcode').keyup(function () {
        $('#modalinvoice').modal('show');
        var barcode = $(this).val()

        $.ajax({
            type: "POST",
            data: {
                barcode: barcode
            },
            url: 'getprocess/getproductaccobarcode.php',
            success: function (result) { //alert(result);
                var obj = JSON.parse(result);
                var flag = 1;

                $('#product').val(obj.id)
                // $('#product').attr("name", obj.id)

                $('#saleprice').val(obj.saleprice)
                $('#discount').val(obj.salediscount)
                $('#modalnumber').val(obj.modalnumber)
            }
        });
    })
    $('#modalnumber').keyup(function (event) {
        var modalnumber = $(this).val()

        if (event.which === 13) {
            if ($('#product').data('select2')) {
                $("#product").select2('destroy');
            }

            $.ajax({
                type: "POST",
                data: {
                    modalnumber: modalnumber
                },
                url: 'getprocess/getproductaccomodel.php',
                success: function (result) { //alert(result);
                    var obj = JSON.parse(result);
                    var html = ''

                    html += '<option value ="' + obj.id + '" >';
                    html += obj.name;
                    html += '</option>';

                    $('#product').empty().append(html)

                    // $('#product').attr("name", obj.id)

                    $('#saleprice').val(obj.saleprice)
                    $('#produtcat').val(obj.productcat)
                    $('#discount').val(obj.salediscount)
                    $('#barcode').val(obj.barcode)
                }
            });
        }

    })

    $('#product').change(function () {
        var productid = $(this).val()

        $.ajax({
            type: "POST",
            data: {
                recordID: productid
            },
            url: 'getprocess/getproduct.php',
            success: function (result) { //alert(result);
                var obj = JSON.parse(result);


                $('#product').val(obj.id)
                // $('#product').attr("name", obj.id)

                $('#saleprice').val(obj.saleprice)
                $('#discount').val(obj.salediscount)
                $('#barcode').val(obj.barcode)
                // $('#modalnumber').val(obj.modelnumber)f
            }
        });
    })

    $('#customer').change(function () {

        var customerID = $('#customer').val()


        $.ajax({
            type: "POST",
            data: {
                customerID: customerID
            },
            url: 'getprocess/getCustomerVatDetails.php',
            success: function (result) { //alert(result);
                var obj = JSON.parse(result);


                $('#vatcustomer').val(obj.name)
                $('#vatcustomer').attr("name", obj.id)
                $('#vatregno').val(obj.vatregno)




            }
        });
    })

    $("#formsubmit").click(function () {

        if (!$("#directsalesform")[0].checkValidity()) {
            // If the form is invalid, submit it. The form won't actually submit;
            // this will just cause the browser to display the native HTML5 error messages.
            $("#submitBtn").click();
        } else {

            checkStock()




        }
    });

    function checkStock() {
        var locationid = $('#locations').val()
        var productid = $('#product').val()
        var qty = $('#qty').val()
        var status = false
        $.ajax({
            type: 'POST',
            data: {
                locationid: locationid,
                productid: productid
            },
            url: 'getprocess/checkstock.php',
            success: function (result) {

                var obj = JSON.parse(result);
                var availableqty = obj.qty

                if (parseInt(qty) <= parseInt(availableqty)) {
                    status = true
                }
                submitForm(status, availableqty)
            }
        })
    }

    function submitForm(status, availableqty) {
        if (status) {
            var productID = $('#product').val();
            var product = $('#product option:selected').text();
            var saleprice = $('#saleprice').val();
            var qty = $('#qty').val();
            var discount = $('#discount').val();
            var warrantyyears = $('#warrantyyears').val();
            // var warrantymonths = $('#warrantymonths').val();
            var warrantymonths = 0;

            var hiddenfulltot = saleprice * qty;
            var newtotal = parseFloat(saleprice * qty);
            var discountedprice = newtotal * discount / 100;
            var afterdiscount = parseFloat(newtotal - discountedprice);
            var newtotal = afterdiscount

            var showtotal = addCommas(parseFloat(afterdiscount).toFixed(2));

            $('#directDetails > tbody:last').append('<tr class="pointer"><td>' + product +
                '</td><td class="d-none">' + productID + '</td><td class="">' + saleprice +
                '</td><td class="text-center">' + qty + '</td><td class="text-center discount">' +
                discountedprice +
                '</td><td class="total d-none">' +
                newtotal + '</td><td class="text-right">' + showtotal + '</td><td class="d-none">' +
                warrantyyears + '</td><td class="d-none fulltot">' +
                hiddenfulltot + '</td><td class="d-none">' +
                warrantymonths + '</td></tr>');

            $('#product').val('');
            $('#product').attr("name", '')
            $("#product").focus();

            $('#saleprice').val('');
            $('#saleprice').val('');
            $('#barcode').val('');
            $('#discount').val('');
            $('#qty').val('');
            $('#warrantyyears').val('');
            // $('#warrantymonths').val('');
            $('#modalnumber').val('');


            var sum = 0;
            var discount = 0;
            var fulltot = 0;
            $(".total").each(function () {
                sum += parseFloat($(this).text());
            });

            $(".discount").each(function () {
                discount += parseFloat($(this).text());
            });

            $(".fulltot").each(function () {
                fulltot += parseFloat($(this).text());
            });
            $('#hiddentotaliscount').val(discount);

            var showsum = addCommas(parseFloat(sum).toFixed(2));
            var fulltot = addCommas(parseFloat(fulltot).toFixed(2));
            var discount = addCommas(parseFloat(discount).toFixed(2));


            $('#divtotal').html('Rs. ' + fulltot);
            $('#hiddentotal').val(sum);
            $('#finalhiddentotal').val(sum);
            $('#product').focus();
            $('#netTotal').html('Rs. ' + showsum);
            $('#eachproductdiscount').html('Rs. ' + discount);
        } else {
            var producttext = $("#product option:selected").text()
            var locationtext = $("#locations option:selected").text()

            $('#viewmessage').html('There are only ' + availableqty + ' products');
            $('#noqtymodal').modal('show')
        }
    }

    $('#billdiscount').keyup(function () {
        var value = $(this).val();
        var total = $('#hiddentotal').val()

        var discount = total * value / 100;
        var finalprice = total - discount;

        $('#totaldiscount').html('Rs. ' + discount);
        $('#netTotal').html('Rs. ' + finalprice);
        $('#finalhiddentotal').val(finalprice);


        // alert(finalprice)
    })

    $('#createdirectmodal').click(function () {
        $('#modalpayment').modal('show');

        var tableHtml = $('#directDetails').html();
        var paymenttotal = $('#divtotal').text();
        var paymentdiscount = $('#eachproductdiscount').text();
        var paymentnettotal = $('#netTotal').text();

        $('#paymentnettotal').text(paymentnettotal)
        $('#paymenttotal').text(paymenttotal)
        $('#paymentdiscount').text(paymentdiscount)
        $('#paymenttable').html(tableHtml)

    })

    $('#createdirectsale').click(function () {


        if (!$("#paymentform")[0].checkValidity()) {
            // If the form is invalid, submit it. The form won't actually submit;
            // this will just cause the browser to display the native HTML5 error messages.
            $("#paymentsubmit").click();
        } else {
            $('#modalpayment').modal('hide');

            var tbody = $('#directDetails tbody');
            if (tbody.children().length >= 0) {
                jsonObj = []
                $("#directDetails tbody tr").each(function () {
                    item = {}
                    $(this).find('td').each(function (col_idx) {
                        item["col_" + (col_idx + 1)] = $(this).text();
                    });
                    jsonObj.push(item);
                });
            }

            var deliveryDate = $('#date').val();
            var customer = $('#customer').val();
            var salerep = $('#salerep').val();
            var finalhiddentotal = $('#finalhiddentotal').val();
            var vatregno = $('#vatregno').val();
            var vatrate = $('#vatrate').val();
            var locations = $('#locations').val();

            var addresscus = $('#addresscus').val();
            var customername = $('#customername').val();
            var contact = $('#contact').val();


            var vatoption = 0

            var paymentoption = $("input[name='paymenttype']:checked").val();
            var advancepayment = $('#advancepayment').val();
            var customerdiscount = $('#customerdiscount').val();
            var paymentdiscount = $('#hiddentotaliscount').val();
            var hiddenbalance = $('#hiddenbalance').val();

            // if ($("#vatcheck").prop('checked') == true) {
            //     vatoption = 1
            // } else {
            //     vatoption = 2
            // }

            $.ajax({
                type: "POST",
                data: {
                    tableData: jsonObj,
                    deliveryDate: deliveryDate,
                    customer: customer,
                    salerep: salerep,
                    total: finalhiddentotal,
                    paymentoption: paymentoption,
                    // vatoption: vatoption,
                    vatregno: vatregno,
                    vatrate: vatrate,
                    locations: locations,
                    contact: contact,
                    customername: customername,
                    addresscus: addresscus,
                    advancepayment: advancepayment,
                    customerdiscount: customerdiscount,
                    paymentdiscount: paymentdiscount,
                    hiddenbalance: hiddenbalance,
                },
                url: 'process/directsalesprocess.php',
                success: function (result) {
                    var obj = JSON.parse(result);

                    if (obj.actiontype == 1) {

                        var invoiceID = obj.invoiceid;

                        $.ajax({
                            type: "POST",
                            data: {
                                recordID: invoiceID
                            },
                            url: 'getprocess/getinvoiceprint.php',
                            success: function (result) {
                                $('#divinvoiceview').html(result);
                                $('#modalinvoice').modal('show');
                            }
                        });
                    }
                }
            });
        }



    })

    $('input[type="checkbox"]').click(function () {
        // vatcusregno
        // vatcusdiv
        if ($(this).prop("checked") == true) {
            $('#vatcusdiv').removeClass('d-none');
            $('#vatcusregno').removeClass('d-none');

        } else if ($(this).prop("checked") == false) {
            $('#vatcusdiv').addClass('d-none');
            $('#vatcusregno').addClass('d-none');

        }
    });

    $('#advancepayment').keyup(function () {
        var paytotal = $(this).val()
        var netTotal = $('#finalhiddentotal').val();
        var balance = addCommas(parseFloat(netTotal - paytotal).toFixed(2))
        $('#balancetext').text('Rs.' + balance)
        $('#hiddenbalance').val(balance)
    })

    $('#customerdiscount').keyup(function () {
        var discounttot = $(this).val()
        var netTotal = $('#finalhiddentotal').val();
        var paytotal = $('#advancepayment').val();

        var balance = addCommas(parseFloat(netTotal - paytotal - discounttot).toFixed(2))
        $('#balancetext').text('Rs.' + balance)
        $('#hiddenbalance').val(balance)

    })


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

    $('#modalinvoice').on('hidden.bs.modal', function (event) {
        location.reload();
    })

    document.getElementById('btnreceiptprint').addEventListener("click", print);

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

    function print() {
        printJS({
            printable: 'divinvoiceview',
            type: 'html',
            style: '@page { size: A4 portrait; margin:0.25cm; }',
            targetStyles: ['*']
        })
    }
</script>
<?php include "include/footer.php"; ?>


