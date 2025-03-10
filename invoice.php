<?php 
include "include/header.php";  

$sql="SELECT * FROM `tbl_product_category` WHERE `status` IN (1,2)";
$result =$conn-> query($sql); 

$sqlreflist="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `status`=1 AND `tbl_user_type_idtbl_user_type`=7";
$resultreflist =$conn-> query($sqlreflist);

$sqlproduct="SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1";
$resultproduct =$conn-> query($sqlproduct);

$sqlbank="SELECT `idtbl_bank`, `bankname` FROM `tbl_bank` WHERE `status`=1 AND `idtbl_bank`!=1";
$resultbank =$conn-> query($sqlbank); 

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
                            <div class="page-header-icon"><i data-feather="file"></i></div>
                            <span>Invoice Create</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-4">
                                <form id="invcreateform" autocomplete="off">
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Date*</label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control dpd1a" placeholder="" name="invoicedate" id="invoicedate" required>
                                                <div class="input-group-append">
                                                    <span class="btn btn-light border-gray-500"><i class="far fa-calendar"></i></span>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Rep Name*</label>
                                            <select class="form-control form-control-sm" name="ref" id="ref" required>
                                                <option value="">Select</option>
                                                <?php if($resultreflist->num_rows > 0) {while ($rowreflist = $resultreflist-> fetch_assoc()) { ?>
                                                <option value="<?php echo $rowreflist['idtbl_employee'] ?>"><?php echo $rowreflist['name'] ?></option>
                                                <?php }} ?>
                                            </select>
                                        </div> 
                                    </div>
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Load list*</label>
                                            <select class="form-control form-control-sm" name="vehicleload" id="vehicleload" required>
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Area*</label>
                                            <select class="form-control form-control-sm" name="area" id="area" required>
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Customer*</label>
                                            <select class="form-control form-control-sm" name="customer" id="customer" required>
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Customer Available Stock -->
                                    <div class="collapse" id="customeravastock">
                                        <div class="card card-body p-0 border-0 shadow-none">
                                            <div class="form-row mt-3">
                                                <div class="col">
                                                    <h6 class="small title-style font-weight-bold"><span>Customer Qty</span></h6>
                                                </div>
                                            </div>
                                            <div id="customeravaqtydiv"></div>
                                        </div>
                                    </div>
                                    <!-- Customer Available Stock -->
                                    <!-- Invoice Information -->
                                    <div class="collapse" id="invoiceinfo">
                                        <div class="card card-body p-0 border-0 shadow-none">
                                            <div class="form-row mb-1">
                                                <div class="col">
                                                    <label class="small font-weight-bold text-dark">Product*</label>
                                                    <select class="form-control form-control-sm" name="product" id="product" required>
                                                        <option value="">Select</option>
                                                        <?php if($resultproduct->num_rows > 0) {while ($rowproduct = $resultproduct-> fetch_assoc()) { ?>
                                                        <option value="<?php echo $rowproduct['idtbl_product'] ?>"><?php echo $rowproduct['product_name'] ?></option>
                                                        <?php }} ?>
                                                    </select>
                                                </div>
                                                <div class="col">
                                                    <label class="small font-weight-bold text-dark">Available Qty</label>
                                                    <input type="text" name="avaqty" id="avaqty" class="form-control form-control-sm" readonly>
                                                </div>
                                            </div>
                                            <div class="form-row mb-1">
                                                <div class="col">
                                                    <label class="small font-weight-bold text-dark">New</label>
                                                    <input type="text" name="newqty" id="newqty" class="form-control form-control-sm" value="0">
                                                </div>
                                                <div class="col">
                                                    <label class="small font-weight-bold text-dark">Refill</label>
                                                    <input type="text" name="refillqty" id="refillqty" class="form-control form-control-sm" value="0">
                                                </div>
                                                <div class="col">
                                                    <label class="small font-weight-bold text-dark">Trust</label>
                                                    <input type="text" name="trustqty" id="trustqty" class="form-control form-control-sm" value="0">
                                                </div>
                                                <div class="col">
                                                    <label class="small font-weight-bold text-dark">Return</label>
                                                    <input type="text" name="returnqty" id="returnqty" class="form-control form-control-sm" value="0">
                                                </div>
                                            </div>
                                            <div class="form-group mt-3">
                                                <button type="button" id="submitbtn" class="btn btn-outline-primary btn-sm px-5 fa-pull-right" <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Add</button>
                                                <input name="hidesubmit" type="submit" value="Save" id="hidesubmit" class="d-none">
                                                <input type="hidden" name="unitprice" id="unitprice" value="">
                                                <input type="hidden" name="refillprice" id="refillprice" value="">
                                                <input type="hidden" name="newsaleprice" id="newsaleprice" value="">
                                                <input type="hidden" name="refillsaleprice" id="refillsaleprice" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Invoice Information -->
                                </form>
                            </div>
                            <div class="col-6">
                                <h6 class="small title-style font-weight-bold my-2"><span>Invoice Detail</span></h6>
                                <table class="table table-striped table-bordered table-sm small" id="tableinvoice">
                                    <thead>
                                       <tr>
                                            <th>Product</th>
                                            <th class="d-none">ProductID</th>
                                            <th class="d-none">Unitprice</th>
                                            <th class="d-none">Refillprice</th>
                                            <th class="d-none">Newsaleprice</th>
                                            <th class="d-none">Refillsaleprice</th>
                                            <th>New</th>
                                            <th>Refill</th>
                                            <th>Return</th>
                                            <th>Trust</th>
                                            <th class="d-none">Totalhide</th>
                                            <th class="text-right">Total</th>
                                            <th class="d-none">Cusfull</th>
                                            <th class="d-none">Cusempty</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                <div class="row">
                                    <div class="col text-right"><h1 class="font-weight-600" id="divtotal">Rs. 0.00</h1></div>
                                    <input type="hidden" id="hidetotalinvoice" value="0">
                                </div>
                                <div class="row">
                                    <div class="col-6">&nbsp;</div>
                                    <div class="col-6">
                                        <button class="btn btn-outline-primary btn-sm fa-pull-right mt-2 px-4" id="btnissuemodal">Issue Invoice</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 small text-primary text-justify"><hr>Now you can add the invoice payment click the invoice number & add payment. Payment complete invoice show "Green" colour.</div>
                                </div>
                            </div>
                            <div class="col-2">
                                <h6 class="small title-style font-weight-bold my-2"><span>Issue Invoices</span></h6>
                                <ul class="list-group mt-2" id="invoicelist"></ul>
                                <!-- <button class="btn btn-outline-dark btn-sm fa-pull-right mt-2" id="btncompleteinvoice" disabled>Complete Invoices</button> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>
<!-- Modal Warning -->
<div class="modal fade" style="z-index: 2000; " id="warningModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
<!-- Modal Payment Invoice -->
<div class="modal fade" id="modalinvoicepayment" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h6 class="modal-title" id="staticBackdropLabel">Invoice Payment</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="invoiceviewdetail"></div>
                <hr class="border-dark">
                <button type="button" class="btn btn-outline-danger btn-sm fa-pull-right" disabled id="invPaymentCreateBtn"><i class="fas fa-receipt"></i>&nbsp;Issue Payment Receipt</button>
                <button type="button" class="btn btn-outline-dark btn-sm fa-pull-right mr-2" id="invPaymentCheckBtn" disabled><i class="fas fa-tasks"></i>&nbsp;Check Payment</button>
            </div>
        </div>
    </div>
</div>
<!--Issue Payment Receipt Modal-->
<div class="modal fade" id="paymentmodal" tabindex="-1" role="dialog" aria-labelledby="oLevel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header p-0 p-2">
                <h5 class="modal-title" id="oLevelTitle">Issue Payment Receipt</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
                        <form id="formModal">
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold text-dark">Payment Amount</label>
                                <input id="paymentPayAmount" name="paymentPayAmount" type="text" class="form-control form-control-sm" placeholder="Total Amount" readonly>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="paymentMethod1" name="paymentMethod" class="custom-control-input" value="1" data-toggle="collapse" href="#collapseOne">
                                <label class="custom-control-label" for="paymentMethod1">Cash</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="paymentMethod2" name="paymentMethod" class="custom-control-input" value="2" data-toggle="collapse" href="#collapseTwo">
                                <label class="custom-control-label" for="paymentMethod2">Bank / Cheque</label>
                            </div>
                            <div class="accordion" id="accordionExample">
                                <div class="card shadow-none border-0">
                                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                                        data-parent="#accordionExample">
                                        <div class="card-body p-0">
                                            <div class="form-group mb-1">
                                                <label class="small font-weight-bold text-dark">Cash Advance</label>
                                                <input id="paymentCash" name="paymentCash" type="text" class="form-control form-control-sm" placeholder="" required readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card shadow-none border-0">
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                        data-parent="#accordionExample">
                                        <div class="card-body p-0">
                                            <div class="form-group mb-1">
                                                <label class="small font-weight-bold text-dark">Cheque / Deposit Advance</label>
                                                <input id="paymentCheque" name="paymentCheque" type="text" class="form-control form-control-sm" placeholder="" required readonly>
                                            </div>
                                            <div class="form-group mb-1">
                                                <label class="small font-weight-bold text-dark">Cheque Number</label>
                                                <input id="paymentChequeNum" name="paymentChequeNum" type="text" class="form-control form-control-sm" placeholder="" readonly>
                                            </div>
                                            <div class="form-group mb-1">
                                                <label class="small font-weight-bold text-dark">Receipt Number</label>
                                                <input id="paymentReceiptNum" name="paymentReceiptNum" type="text" class="form-control form-control-sm" placeholder="" readonly>
                                                <small id="" class="form-text text-muted">Bank deposit receipt number only</small>
                                            </div>
                                            <div class="form-group mb-1">
                                                <label class="small font-weight-bold text-dark">Cheque Date</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control dpd2a" placeholder="" name="paymentchequeDate" id="paymentchequeDate" readonly>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i class="far fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mb-1">
                                                <label class="small font-weight-bold text-dark">Bank Name</label>
                                                <select id="paymentBank" name="paymentBank" class="form-control form-control-sm" disabled>
                                                    <option value="">Select</option>
                                                    <?php if($resultbank->num_rows > 0) {while ($rowbank = $resultbank-> fetch_assoc()) { ?>
                                                    <option value="<?php echo $rowbank['idtbl_bank'] ?>"><?php echo $rowbank['bankname']; ?></option>
                                                    <?php }} ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <button name="submitBtnModal" type="button" id="submitBtnModal" class="btn btn-outline-primary btn-sm fa-pull-right"><i class="fas fa-file-invoice-dollar"></i>&nbsp;Add Payment</button>
                                <input type="submit" class="d-none" id="hideSubmitModal">
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-12 col-md-9 col-lg-9 col-xl-9">
                        <table class="table table-bordered table-sm table-striped" id="tblPaymentTypeModal">
                            <thead>
                                <th>Type</th>
                                <th class="text-right">Cash</th>
                                <th class="text-right">Cheque / Deposit</th>
                                <th>Che No</th>
                                <th>Receipt</th>
                                <th>Che Date</th>
                                <th>Bank</th>
                                <th class="d-none">BankID</th>
                                <th class="d-none">paymethod</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <div class="row">
                            <div class="col-sm-12 col-md-9 col-lg-9 col-xl-9 text-right">Total Amount :</div>
                            <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 text-right">
                                <div id="totAmount"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-9 col-lg-9 col-xl-9 text-right">Pay Amount :</div>
                            <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 text-right">
                                <div id="payAmount"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-9 col-lg-9 col-xl-9 text-right">&nbsp;</div>
                            <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 text-right">
                                <hr class="border-dark">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-9 col-lg-9 col-xl-9 text-right">Balance :</div>
                            <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 text-right">
                                <div id="balanceAmount"></div>
                            </div>
                        </div>
                        <input type="hidden" id="hidePayAmount" value="0">
                        <input type="hidden" id="hideBalAmount" value="0">
                        <input type="hidden" id="hideAllBalAmount" value="0">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12" align="right">
                        <button class="btn btn-outline-danger btn-sm" id="btnIssueInv" disabled><i class="fas fa-file-pdf"></i>&nbsp;Issue Payment Receipt</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Payment Receipt -->
<div class="modal fade" id="modalpaymentreceipt" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="viewreceiptprint"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger btn-sm fa-pull-right" id="btnreceiptprint"><i class="fas fa-print"></i>&nbsp;Print Receipt</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Day End Warning -->
<div class="modal fade" id="warningDayEndModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function() {
        checkdayendprocess();
        var addcheck='<?php echo $addcheck; ?>';

        $('.dpd1a').datepicker({
            uiLibrary: 'bootstrap4',
            autoclose: 'true',
            todayHighlight: true,
            endDate: 'today',
            format: 'yyyy-mm-dd'
        });
        $('.dpd2a').datepicker({
            uiLibrary: 'bootstrap4',
            autoclose: 'true',
            todayHighlight: true,
            startDate: 'today',
            format: 'yyyy-mm-dd'
        });
        $('#invoicedate').change(function(){
            var invdate = $(this).val();
            var refID = $('#ref').val();
            if(refID!=''){
                getdispatchlist(invdate, refID);
            }
        });
        $('#ref').change(function(){
            var refID = $(this).val();
            var invdate = $('#invoicedate').val();

            if (!$('#invoicedate').val()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmit").click();
            } else {  
                getloadlist(invdate, refID);
            }
        });
        $('#vehicleload').change(function(){
            var vehicleloadID=$(this).val();
            var invoicedate=$('#invoicedate').val();
            
            $.ajax({
                type: "POST",
                data: {
                    vehicleloadID: vehicleloadID
                },
                url: 'getprocess/getareacustomeraccovehicleload.php',
                success: function(result) { //alert(result);
                    var objfirst = JSON.parse(result);
                    var arealist = objfirst.arealist;
                    var cuslist = objfirst.cuslist;

                    var html = '';
                    $.each(arealist, function(i, item) {
                        //alert(objfirst[i].id);
                        html += '<option value="' + arealist[i].areaid + '">';
                        html += arealist[i].area;
                        html += '</option>';
                    });

                    $('#area').empty().append(html);

                    var htmlcus = '';
                    htmlcus += '<option value="">Select</option>';
                    $.each(cuslist, function(i, item) {
                        //alert(objfirst[i].id);
                        htmlcus += '<option value="' + cuslist[i].customerID + '">';
                        htmlcus += cuslist[i].customer;
                        htmlcus += '</option>';
                    });

                    $('#customer').empty().append(htmlcus);
                    $('#customer').focus();
                }
            });
            //Get Invoice List
            $.ajax({
                type: "POST",
                data: {
                    vehicleloadID: vehicleloadID,
                    invoicedate: invoicedate
                },
                url: 'getprocess/getinvoiceaccoload.php',
                success: function(result) { 
                    var objfirst = JSON.parse(result);
                    var html=''; 
                    $.each(objfirst, function(i, item) {
                        html+='<li class="list-group-item d-flex justify-content-between align-items-center py-1 px-2 small';
                        if(objfirst[i].paystatus==1){
                            html+=' bg-success-soft';
                        }
                        else{
                            html+=' pointer clickinvpay';
                        }
                        html+='" id="INV-'+objfirst[i].invoiceid+'">INV-' + objfirst[i].invoiceid + '<div>' + parseFloat(objfirst[i].invoicetotal).toFixed(2) + '</div></li>';
                    });

                    $('#invoicelist').empty().html(html);
                    optionpayinvoice();
                    if($('ul#invoicelist li').length > 0){
                        $('#btncompleteinvoice').prop('disabled', false);
                    }
                }
            });
        });
        $('#product').change(function(){  
            if (!$('#customer').val()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmit").click();
                $('#product').val('');
            } else {
                var listqty = parseFloat('0');
                var productID = $(this).val();
                var customerID=$('#customer').val();
                var vehicleloadID=$('#vehicleload').val();
            
                $.ajax({
                    type: "POST",
                    data: {
                        productID: productID,
                        customerID: customerID,
                        vehicleloadID: vehicleloadID
                    },
                    url: 'getprocess/getproductpricesaccoproduct.php',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);

                        $('#unitprice').val(obj.unitprice);
                        $('#refillprice').val(obj.refillprice);
                        $('#newsaleprice').val(obj.newsaleprice);
                        $('#refillsaleprice').val(obj.refillsaleprice);

                        var tbody = $("#tableinvoice tbody");

                        if (tbody.children().length > 0) {
                            $("#tableinvoice tbody tr").each(function() {
                                var currentRow=$(this).closest("tr"); 
         
                                var col2=currentRow.find("td:eq(1)").text();
                                var col6=parseFloat(currentRow.find("td:eq(6)").text());
                                var col7=parseFloat(currentRow.find("td:eq(7)").text());
                                var col9=parseFloat(currentRow.find("td:eq(9)").text());

                                if(col2==productID){
                                    listqty = parseFloat(listqty+col6+col7+col9);
                                }
                            });
                        }

                        $('#avaqty').val((obj.avaqty-listqty));
                        // $('#bufferqty').val((obj.bufferqty));

                        $('#newqty').focus();
                        $('#newqty').select();
                    }
                });
            }
        });
        $('#customer').change(function(){
            var customerID = $(this).val();
            var invoicedate = $('#invoicedate').val();

            $.ajax({
                type: "POST",
                data: {
                    customerID: customerID
                },
                url: 'getprocess/getcreditinfoaccocustomer.php',
                success: function(result) { 
                    if(result==1){
                        $('#warningdesc').html("This customer can't issue any invoice, please get payment or active emergency option.")
                        $('#warningModal').modal('show');
                        $('#product').prop('disabled', true);
                        $('#refillqty').prop('redonly', true);
                        $('#returnqty').prop('redonly', true);
                        $('#newqty').prop('redonly', true);
                        $('#trustqty').prop('redonly', true);
                    }
                    else{
                        $.ajax({
                            type: "POST",
                            data: {
                                customerID: customerID,
                                addcheck: addcheck,
                                invoicedate: invoicedate
                            },
                            url: 'getprocess/getbufferqtydetailaccocustomer.php',
                            success: function(result) { 
                                $('#customeravaqtydiv').html(result);
                                availableqtyoption();
                                $('#invoiceinfo').collapse('hide');
                                $('#customeravastock').collapse('show');
                            }
                        });
                    }
                }
            });
        });

        $('#submitbtn').click(function(){
            if (!$("#invoicedate")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmit").click();
            } else {   
                var productID = $('#product').val();
                var product = $("#product option:selected").text();
                var unitprice = parseFloat($('#unitprice').val());
                var refillprice = parseFloat($('#refillprice').val());
                var newsaleprice = parseFloat($('#newsaleprice').val());
                var refillsaleprice = parseFloat($('#refillsaleprice').val());
                var refillqty = parseFloat($('#refillqty').val());
                var returnqty = parseFloat($('#returnqty').val());
                var newqty = parseFloat($('#newqty').val());
                var trustqty = parseFloat($('#trustqty').val());
                var avaqty = parseFloat($('#avaqty').val());

                var totqty=refillqty+newqty+trustqty;

                if(totqty<=avaqty){
                    var totrefill=parseFloat(refillqty*refillsaleprice);
                    var totnew=parseFloat(newqty*newsaleprice);
                    var tottrust=parseFloat(trustqty*refillsaleprice);

                    var total = parseFloat(totrefill+totnew+tottrust);
                    var showtotal = addCommas(parseFloat(total).toFixed(2));

                    $('#tableinvoice > tbody:last').append('<tr class="pointer"><td>' + product + '</td><td class="d-none">' + productID + '</td><td class="d-none">' + unitprice + '</td><td class="d-none">' + refillprice + '</td><td class="d-none">' + newsaleprice + '</td><td class="d-none">' + refillsaleprice + '</td><td>' + newqty + '</td><td>' + refillqty + '</td><td>' + returnqty + '</td><td>' + trustqty + '</td><td class="d-none total">' + total + '</td><td class="text-right">' + showtotal + '</td></tr>');

                    var sum = 0;
                    $(".total").each(function(){
                        sum += parseFloat($(this).text());
                    });

                    var showsum = addCommas(parseFloat(sum).toFixed(2));
                    
                    $('#divtotal').html('Rs. '+showsum);
                    $('#hidetotalinvoice').val(sum);

                    $('#product').val('');
                    $('#unitprice').val('');
                    $('#refillprice').val('');
                    $('#newsaleprice').val('');
                    $('#refillsaleprice').val('');
                    $('#refillqty').val('0');
                    $('#returnqty').val('0');
                    $('#trustqty').val('0');
                    $('#newqty').val('0');
                    $('#avaqty').val('');

                    $('#product').focus();
                }
                else{
                    $('#warningdesc').html("Not enough load quantity, please check invoice quantity")
                    $('#warningModal').modal('show');
                }
            }
        });
        $('#btnissuemodal').click(function(){
            var tbody = $("#tableinvoice tbody");

            if (tbody.children().length > 0) {
                var invoicedate = $('#invoicedate').val();
                var refID = $('#ref').val();
                var vehicleloadID = $('#vehicleload').val();
                var areaID = $('#area').val();
                var customerID = $('#customer').val();
                var rejectID = $('#reject').val();
                var nettotal = $('#hidetotalinvoice').val();

                jsonObj = [];
                $("#tableinvoice tbody tr").each(function() {
                    item = {}
                    $(this).find('td').each(function(col_idx) {
                        item["col_" + (col_idx + 1)] = $(this).text();
                    });
                    jsonObj.push(item);
                });
                // console.log(jsonObj);

                $.ajax({
                    type: "POST",
                    data: {
                        tableData: jsonObj,
                        invoicedate: invoicedate,
                        refID: refID,
                        vehicleloadID: vehicleloadID,
                        areaID: areaID,
                        customerID: customerID,
                        rejectID: rejectID,
                        nettotal: nettotal
                    },
                    url: 'process/invoiceprocess.php',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);

                        if(obj.actiontype==1){
                            action(obj.action);
                            $('#customer').val('');
                            $('#customer').focus();
                            $('#hidetotalinvoice').val('');
                            $('#tableinvoice > tbody').html('');
                            $('#divtotal').html('Rs. 0.00');
                        }
                        else{
                            action(obj.action);
                        }

                        var html=''; 
                        var objfirst = obj.invicelist;
                        $.each(objfirst, function(i, item) {
                            html+='<li class="list-group-item d-flex justify-content-between align-items-center py-1 px-2 small';
                            if(objfirst[i].paystatus==1){
                                html+=' bg-success-soft';
                            }
                            else{
                                html+=' pointer clickinvpay';
                            }
                            html+='" id="INV-'+objfirst[i].invoiceid+'">INV-' + objfirst[i].invoiceid + '<div>' + parseFloat(objfirst[i].invoicetotal).toFixed(2) + '</div></li>';
                        });

                        $('#invoicelist').empty().html(html);
                        $('#modalinvoiceoption').modal('hide');
                        if($('ul#invoicelist li').length > 0){
                            $('#btncompleteinvoice').prop('disabled', false);
                        }
                    }
                });
            }
        });

        //Invoice Payment
        $('#invPaymentCheckBtn').click(function() {
            var promise = tblTextRemove();

            promise.then(function() {
                var value = 0;

                var table = $("#paymentDetailTable tbody");
                table.find('tr').each(function(i, el) {
                    var row = $(this);
                    var tds = $(this).find('td');
                    value += parseFloat(tds.eq(8).text());
                });

                if (value == '0.00') {
                    $('#warningdesc').html('<i class="fas fa-exclamation-triangle fa-pull-left fa-3x"></i><p>Please enter the payment value or uncheck full payment | halfpayment check box.</p>');
                    $('#warningModal').modal({
                        keyboard: false,
                        backdrop: 'static'
                    });
                    $('#invPaymentCreateBtn').prop("disabled", true);
                } else {
                    $('#invPaymentCreateBtn').prop("disabled", false);
                }
            });

        });
        $('#invPaymentCreateBtn').click(function() {
            // $('#modalinvoicepayment').modal('hide');
            var table = $("#paymentDetailTable tbody");
            var total = '0';
            var invnetBal = '0';
            var invBal = '0';
            table.find('tr').each(function(i, el) {
                var $tds = $(this).find('td');
                var value = parseFloat($tds.eq(8).text());

                total = parseFloat(total);
                total = (total + value);

                var bal = parseFloat($tds.eq(4).text());
                invBal = parseFloat(invBal);
                invBal = (invBal + invBal);
            });

            total = parseFloat(total).toFixed(2);
            invBal = parseFloat(invBal).toFixed(2);
            invnetBal = parseFloat(total + invBal).toFixed(2);

            if (invnetBal == '0.00') {
                $('#warningdesc').html('<i class="fas fa-exclamation-triangle fa-pull-left fa-3x"></i><p>Please enter the payment value and press the create payment button</p>');
                $('#warningModal').modal({
                    keyboard: false,
                    backdrop: 'static'
                });
                $('#invPaymentCreateBtn').prop("disabled", true);
            } else {
                $('#paymentPayAmount').val(invnetBal);
                $('#totAmount').html(invnetBal);
                $('#hideAllBalAmount').val(invnetBal);
                $('#paymentmodal').modal({
                    keyboard: false,
                    backdrop: 'static'
                });
            }
        });
        $('input[type=radio][name=paymentMethod]').change(function() {
            if (this.value == '1') {
                $('#paymentCheque').prop("readonly", true);
                $('#paymentChequeNum').prop("readonly", true);
                $('#paymentReceiptNum').prop("readonly", true);
                $('#paymentchequeDate').prop("readonly", true);
                $('#paymentBank').prop("disabled", true);
                $('#paymentCash').prop("readonly", false);
            } else {
                $('#paymentCheque').prop("readonly", false);
                $('#paymentChequeNum').prop("readonly", false);
                $('#paymentReceiptNum').prop("readonly", false);
                $('#paymentchequeDate').prop("readonly", false);
                $('#paymentBank').prop("disabled", false);
                $('#paymentCash').prop("readonly", true);
            }
        });
        $("#submitBtnModal").click(function() {
            if (!$("#formModal")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hideSubmitModal").click();
            } else {
                var paymenttype = $('input[type=radio][name=paymentMethod]:checked').val();
                var paymentCheque = $('#paymentCheque').val();
                var paymentChequeNum = $('#paymentChequeNum').val();
                var paymentReceiptNum = $('#paymentReceiptNum').val();
                var paymentchequeDate = $('#paymentchequeDate').val();
                var paymentBankID = $('#paymentBank').val();
                var paymentBank = $("#paymentBank option:selected").text();
                var paymentCash = $('#paymentCash').val();

                if(paymenttype==1){
                    $('#tblPaymentTypeModal > tbody:last').append('<tr><td>Cash</td><td class="text-right">' + parseFloat(paymentCash).toFixed(2) + '</td><td class="">-</td><td class="">-</td><td>-</td><td>-</td><td>-</td><td class="d-none">1</td><td class="d-none">1</td></tr>');
                    var paidAmount = parseFloat($('#hidePayAmount').val());
                    var PayAmount = parseFloat(paymentCash);
                    var paymentPayAmount = parseFloat($('#hideAllBalAmount').val());

                    paidAmount = (paidAmount + PayAmount);
                    var balance = (paymentPayAmount - paidAmount);
                    $('#hideBalAmount').val(balance);
                    $('#balanceAmount').html((balance).toFixed(2));
                    $('#payAmount').html((paidAmount).toFixed(2));
                    $('#hidePayAmount').val(paidAmount);

                    $('#paymentCash').val('').prop('readonly', true);
                    $('#paymentMethod1').prop('checked', false);

                    $('#btnIssueInv').prop('disabled', false);
                }
                else{
                    $('#tblPaymentTypeModal > tbody:last').append('<tr><td>Bank / Cheque</td><td class="">-</td><td class="text-right">' + parseFloat(paymentCheque).toFixed(2) + '</td><td class="">'+paymentChequeNum+'</td><td>'+paymentReceiptNum+'</td><td>'+paymentchequeDate+'</td><td>'+paymentBank+'</td><td class="d-none">'+paymentBankID+'</td><td class="d-none">2</td></tr>');

                    var paidAmount = parseFloat($('#hidePayAmount').val());
                    var PayAmount = parseFloat(paymentCheque);
                    var paymentPayAmount = parseFloat($('#hideAllBalAmount').val());

                    paidAmount = (paidAmount + PayAmount);
                    var balance = (paymentPayAmount - paidAmount);
                    $('#hideBalAmount').val(balance);
                    $('#balanceAmount').html((balance).toFixed(2));
                    $('#payAmount').html((paidAmount).toFixed(2));
                    $('#hidePayAmount').val(paidAmount);

                    $('#paymentCheque').val('').prop('readonly', true);
                    $('#paymentChequeNum').val('').prop('readonly', true);
                    $('#paymentReceiptNum').val('').prop('readonly', true);
                    $('#paymentchequeDate').val('').prop('readonly', true);
                    $('#paymentBank').val('').prop('disabled', true);
                    $('#paymentMethod2').prop('checked', false);

                    $('#btnIssueInv').prop('disabled', false);
                }

                $('#collapseOne').collapse('hide');
                $('#collapseTwo').collapse('hide');
            }
        });
        $('#btnIssueInv').click(function(){
            jsonObj = [];
            $("#paymentDetailTable tbody tr").each(function() {
                item = {}
                $(this).find('td').each(function(col_idx) {
                    item["col_" + (col_idx + 1)] = $(this).text();
                });
                jsonObj.push(item);
            });
            // console.log(jsonObj);

            jsonObjOne = [];
            $("#tblPaymentTypeModal tbody tr").each(function() {
                item = {}
                $(this).find('td').each(function(col_idx) {
                    item["col_" + (col_idx + 1)] = $(this).text();
                });
                jsonObjOne.push(item);
            });
            // console.log(jsonObjOne);

            var totAmount = $('#paymentPayAmount').val();
            var payAmount = $('#hidePayAmount').val();
            var balAmount = $('#hideBalAmount').val();

            $.ajax({
                type: "POST",
                data: {
                    tblData: jsonObj,
                    tblPayData: jsonObjOne,
                    totAmount: totAmount,
                    payAmount: payAmount,
                    balAmount: balAmount
                },
                url: 'process/invoicepaymentprocess.php',
                success: function(result) { //alert(result);
                    var obj = JSON.parse(result);
                    if(obj.paymentinvoice>0){
                        $('#modalinvoicepayment').modal('hide');
                        $('#paymentmodal').modal('hide');
                        paymentreceiptview(obj.paymentinvoice);
                        $('#modalpaymentreceipt').modal('show');
                    }
                    action(obj.action);
                }
            });
        });

        document.getElementById('btnreceiptprint').addEventListener ("click", print);

        $('#modalpaymentreceipt').on('hidden.bs.modal', function (e) {
            var vehicleloadID=$('#vehicleload').val();
            var invoicedate=$('#invoicedate').val();

            $.ajax({
                type: "POST",
                data: {
                    vehicleloadID: vehicleloadID,
                    invoicedate: invoicedate
                },
                url: 'getprocess/getinvoiceaccoload.php',
                success: function(result) { 
                    var objfirst = JSON.parse(result);
                    var html=''; 
                    $.each(objfirst, function(i, item) {
                        html+='<li class="list-group-item d-flex justify-content-between align-items-center py-1 px-2 small';
                        if(objfirst[i].paystatus==1){
                            html+=' bg-success-soft';
                        }
                        else{
                            html+=' pointer clickinvpay';
                        }
                        html+='" id="INV-'+objfirst[i].invoiceid+'">INV-' + objfirst[i].invoiceid + '<div>' + parseFloat(objfirst[i].invoicetotal).toFixed(2) + '</div></li>';
                    });

                    $('#invoicelist').empty().html(html);
                    optionpayinvoice();
                    if($('ul#invoicelist li').length > 0){
                        $('#btncompleteinvoice').prop('disabled', false);
                    }
                }
            });
        });

        $('#tableinvoice').on( 'click', 'tr', function () {
            var r = confirm("Are you sure, You want to remove this product ? ");
            if (r == true) {
                $(this).closest('tr').remove();

                var sum = 0;
                $(".total").each(function(){
                    sum += parseFloat($(this).text());
                });

                var showsum = addCommas(parseFloat(sum).toFixed(2));
                
                $('#divtotal').html('Rs. '+showsum);
                $('#hidetotalinvoice').val(sum);
            }
        });
    });

    function getloadlist(invdate, refID){
        $.ajax({
            type: "POST",
            data: {
                invdate: invdate,
                refID: refID
            },
            url: 'getprocess/getloadaccoref.php',
            success: function(result) { 
                var objfirst = JSON.parse(result);
                var html = '';
                html += '<option value="">Select</option>';
                $.each(objfirst, function(i, item) {
                    //alert(objfirst[i].id);
                    html += '<option value="' + objfirst[i].id + '">';
                    html += 'VL-'+objfirst[i].id;
                    html += '</option>';
                });

                $('#vehicleload').empty().append(html);
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

    function addCommas(nStr){
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

    function optionpayinvoice(){
        $('.clickinvpay').click(function(){
            var invID = $(this).attr('id');

            $('#modalinvoicepayment').modal('show');

            $('#invoiceviewdetail').html('<div class="card border-0 shadow-none bg-transparent"><div class="card-body text-center"><img src="images/spinner.gif" alt="" srcset=""></div></div>');

            $.ajax({
                type: "POST",
                data: {
                    invoiceno: invID
                },
                url: 'getprocess/getinvoicepayment.php',
                success: function(result) {//alert(result);
                    $('#invoiceviewdetail').html(result);
                    $('#invPaymentCheckBtn').prop("disabled", false);
                    tblcheckboxevent();
                }
            });
        });
    }
    
    function tblcheckboxevent() {
        $('#paymentDetailTable tbody').on('click', '.fullAmount', function() {
            var row = $(this);
            if ((row.closest('.fullAmount')).is(':checked')) {
                var fullAmount = row.closest("tr").find('td:eq(3)').text();
                row.closest("tr").find('td:eq(8)').text(fullAmount);
                row.closest("tr").find('td:eq(7)').find('input[type="checkbox"]').prop('checked', false);
            } else {
                row.closest("tr").find('td:eq(8)').text('0.00');
                row.closest("tr").find('td:eq(7)').find('input[type="checkbox"]').prop('checked', false);
            }
        });

        $('#paymentDetailTable tbody').on('click', '.halfAmount', function() {
            var row = $(this);
            if ((row.closest('.halfAmount')).is(':checked')) {
                tblpayamount();
                row.closest("tr").find('td:eq(6)').find('input[type="checkbox"]').prop('checked', false);
            } else {
                tblTextRemove();
                row.closest("tr").find('td:eq(8)').text('0.00');
                row.closest("tr").find('td:eq(6)').find('input[type="checkbox"]').prop('checked', false);
            }
        });
    }

    function tblpayamount() {
        $('.paidAmount').click(function(e) {
            if (($(this).closest('tr')).find('td:eq(7) .halfAmount').is(':checked')) {
                e.preventDefault();
                e.stopImmediatePropagation();

                $this = $(this);
                if ($this.data('editing')) return;

                var val = $this.text();

                $this.empty();
                $this.data('editing', true);

                $('<input type="Text" class="form-control form-control-sm editfieldpay">').val(val).appendTo($this);
            }
        });
        putOldValueBack = function() {
            $('.editfieldpay').each(function() {
                $this = $(this);
                var val = $this.val();
                var td = $this.closest('td');
                td.empty().html(parseFloat(val).toFixed(2)).data('editing', false);
            });
        }
        $(document).click(function(e) {
            putOldValueBack();
        });
    }

    function tblTextRemove() {
        $('#paymentDetailTable .editfield').each(function() {
            $this = $(this);
            var val = $this.val();
            var td = $this.closest('td');
            td.empty().html(parseFloat(val).toFixed(2)).data('editing', false);
        });

        var deferred = $.Deferred();

        setTimeout(function() {
            // completes status
            deferred.resolve();
        }, 1000);

        // returns complete status
        return deferred.promise();
    }

    function paymentreceiptview(paymentinoiceID){
        $('#viewreceiptprint').html('<div class="card border-0 shadow-none bg-transparent"><div class="card-body text-center"><img src="images/spinner.gif" alt="" srcset=""></div></div>');

        $.ajax({
            type: "POST",
            data: {
                paymentinoiceID: paymentinoiceID
            },
            url: 'getprocess/getpaymentreceipt.php',
            success: function(result) { //alert(result);
                $('#viewreceiptprint').html(result);
            }
        });
    }

    function print() {
        printJS({
            printable: 'viewreceiptprint',
            type: 'html',
            targetStyles: ['*']
        })
    }

    function availableqtyoption(){
        $('#submitavabtn').click(function(){
            var customerID = $('#customer').val();
            var invoicedate = $('#invoicedate').val();
            var rejectID = $('#reject').val();
            var avaproduct=$("input[name='cusavaproduct\\[\\]']").map(function(){return $(this).val();}).get();
            var avafullqty=$("input[name='cusavafull\\[\\]']").map(function(){return $(this).val();}).get();
            var avaemptyqty=$("input[name='cusavaempty\\[\\]']").map(function(){return $(this).val();}).get();
            var avabufferqty=$("input[name='cusavabuffer\\[\\]']").map(function(){return $(this).val();}).get();

            $.ajax({
                type: "POST",
                data: {
                    customerID: customerID,
                    rejectID: rejectID,
                    avaproduct: avaproduct,
                    avafullqty: avafullqty,
                    avaemptyqty: avaemptyqty,
                    avabufferqty: avabufferqty,
                    invoicedate: invoicedate
                },
                url: 'process/customeravailableqtyprocess.php',
                success: function(result) {
                    var objava = JSON.parse(result);

                    if(objava.actiontype==1){
                        $('#customeravastock').collapse('hide');
                        $('#invoiceinfo').collapse('show');
                    }
                    action(objava.action);
                }
            });
        });
    }
    function checkdayendprocess(){
        $.ajax({
            type: "POST",
            data: {
                
            },
            url: 'getprocess/getstatuslastdayendinfo.php',
            success: function(result) { //alert(result);
                if(result==1){
                    $('#viewmessage').html("Can't create anything, because today transaction is end");
                    $('#warningDayEndModal').modal('show');
                }
                else if(result==0){
                    $('#viewmessage').html("Can't create anythind, because yesterday day end process end not yet.");
                    $('#warningDayEndModal').modal('show');
                }
            }
        });
    }
</script>
<?php include "include/footer.php"; ?>
