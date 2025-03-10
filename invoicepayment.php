<?php 
include "include/header.php";  

$sql="SELECT * FROM `tbl_vehicle` WHERE `status` IN (1,2)";
$result =$conn-> query($sql); 

// $sqlcustomer="SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE `status`=1 ORDER BY `name` ASC";
// $resultcustomer =$conn-> query($sqlcustomer);

$sqlbank="SELECT `idtbl_bank`, `bankname` FROM `tbl_bank` WHERE `status`=1 AND `idtbl_bank`!=1";
$resultbank =$conn-> query($sqlbank); 

$sqlasm="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `status`=1 AND `tbl_user_type_idtbl_user_type` IN ('8','9') ORDER BY `name` ASC";
$resultasm =$conn-> query($sqlasm);

$sqlcreditnote="SELECT `u`.`idtbl_creditenote`, `u`.`balAmount`, `c`.`name` FROM `tbl_creditenote` AS `u` LEFT JOIN `tbl_customer` AS `c` ON (`u`.`tbl_customer_idtbl_customer` = `c`.`idtbl_customer`) WHERE `u`.`status`=1 AND `u`.`settle`=0 AND `u`.`balAmount`>0";
$resultcreditnote =$conn-> query($sqlcreditnote); 

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
                            <span>Invoice Payment</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-row">
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Customer*</label>
                                        <select class="form-control form-control-sm" name="customer" id="customer" required>
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">ASM*</label>
                                        <select class="form-control form-control-sm" name="asm" id="asm" required>
                                            <option value="">Select</option>
                                            <?php if($resultasm->num_rows > 0) {while ($rowasm = $resultasm-> fetch_assoc()) { ?>
                                            <option value="<?php echo $rowasm['idtbl_employee'] ?>"><?php echo $rowasm['name']; ?></option>
                                            <?php }} ?>
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <form id="searchform">
                                            <label class="small font-weight-bold text-dark">Invoice Search</label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control" placeholder="" aria-label="Invoice Number" aria-describedby="button-addon2" id="formInvNum" required>
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-dark" type="button" id="formSearchBtn"><i class="fas fa-search"></i>&nbsp;Search</button>
                                                </div>
                                            </div>
                                            <input type="submit" id="hidesearchsubmit" class="d-none">
                                        </form>
                                    </div>
                                    <div class="col">&nbsp;</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <hr class="border-dark">
                                <div id="invoiceviewdetail"></div>
                                <hr class="border-dark">
                                <button type="button" class="btn btn-outline-danger btn-sm fa-pull-right" disabled id="invPaymentCreateBtn"><i class="fas fa-receipt"></i>&nbsp;Issue Payment Receipt</button>
                                <button type="button" class="btn btn-outline-dark btn-sm fa-pull-right mr-2" id="invPaymentCheckBtn" disabled><i class="fas fa-tasks"></i>&nbsp;Check Payment</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>
<!-- Modal Alert -->
<div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content bg-danger">
            <div class="modal-body text-white">
                <div class="row">
                    <div class="col" id="bodyAlert"></div>
                </div>
                <button type="button" class="btn btn-outline-light btn-sm fa-pull-right pl-4 pr-4" data-dismiss="modal">OK</button>
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
                                <label class="small font-weight-bold text-dark">Payment Date*</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control dpd1a" placeholder="" name="paymentdate"
                                        id="paymentdate" required>
                                    <div class="input-group-append">
                                        <span class="btn btn-light border-gray-500"><i
                                                class="far fa-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold text-dark">Payment Amount</label>
                                <input id="paymentPayAmountWithoutdis" name="paymentPayAmountWithoutdis" type="text" class="form-control form-control-sm" placeholder="Total Amount" readonly>
                            </div>
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold text-dark">Discount Amount</label>
                                <input id="paymentPayDiscount" name="paymentPayDiscount" type="text" class="form-control form-control-sm" placeholder="Total Amount" readonly>
                            </div>
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold text-dark">Net Payment Amount</label>
                                <input id="paymentPayAmount" name="paymentPayAmount" type="text" class="form-control form-control-sm" placeholder="Total Amount" readonly>
                            </div>
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold text-dark">Payment Method</label>
                                <select name="paymentMethod" id="paymentMethod" class="form-control form-control-sm">
                                    <option value="">Select</option>
                                    <option value="1">Cash</option>
                                    <option value="2">Bank / Cheque</option>
                                    <option value="3">Credit Note</option>
                                </select>
                            </div>
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold text-dark">Receipt Number</label>
                                <input id="paymentReceiptNum" name="paymentReceiptNum" type="text"
                                    class="form-control form-control-sm" placeholder="">
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
                                                <label class="small font-weight-bold text-dark">Cheque Date</label>
                                                <input type="date" class="form-control form-control-sm" placeholder="" name="paymentchequeDate" id="paymentchequeDate" readonly>
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
                                <div class="card shadow-none border-0">
                                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                                        data-parent="#accordionExample">
                                        <div class="card-body p-0">
                                            <div class="form-group mb-1">
                                                <label class="small font-weight-bold text-dark">Credit Note</label>
                                                <select id="creditnote" name="creditnote" type="text" style="width: 100%;" class="form-control form-control-sm" disabled required>
                                                    <option value="">Select</option>
                                                    <?php if($resultcreditnote->num_rows > 0) {while ($rowcreditnote = $resultcreditnote-> fetch_assoc()) { ?>
                                                    <option value="<?php echo $rowcreditnote['idtbl_creditenote'] ?>" data-creditnoteamount="<?php echo $rowcreditnote['balAmount']; ?>"><?php echo 'CRN'.$rowcreditnote['idtbl_creditenote'].'-'.number_format($rowcreditnote['balAmount'], 2).' / ' . $rowcreditnote['name']; ?></option>
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
                                <th class="text-right">Credit Note</th>
                                <th>Che No</th>
                                <th>Receipt</th>
                                <th>Che Date</th>
                                <th>Bank</th>
                                <th class="">BankID</th>
                                <th class="">paymethod</th>
                                <th class="">CreditnoteID</th>
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
                        <!-- <textarea name="discountlist" id="discountlist" class="d-none"></textarea> -->
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
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function() {
        $('.dpd1a').datepicker({
            uiLibrary: 'bootstrap4',
            autoclose: 'true',
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        });
        $("#creditnote").select2();
        
        $("#customer").select2({
            ajax: {
                url: 'getprocess/getcustomerlistforreturn.php',
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term 
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        $('#customer').change(function(){
            var customerID = $(this).val();
            var asmID = '';
            var invoiceno = '';
            loadinvoice(customerID, asmID, invoiceno);
        });

        $('#asm').change(function(){
            var customerID = '';
            var asmID = $(this).val();
            var invoiceno = '';
            loadinvoice(customerID, asmID, invoiceno);
        });

        $('#formSearchBtn').click(function(){
            if (!$("#searchform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesearchsubmit").click();
            } else {
                var customerID = '';
                var asmID = '';
                var invoiceno = $('#formInvNum').val();
                loadinvoice(customerID, asmID, invoiceno);
            }
        });

        $('#invPaymentCheckBtn').click(function() {
            var promise = tblTextRemove();
            promise.then(function() {
                var value = 0;

                var table = $("#paymentDetailTable tbody");
                table.find('tr').each(function(i, el) {
                    var row = $(this);
                    var tds = $(this).find('td');
                    value += parseFloat(tds.eq(9).text());
                });
                // alert(value)

                if (value == '0.00') {
                    $('#bodyAlert').html('<i class="fas fa-exclamation-triangle fa-pull-left fa-3x"></i><p>Please enter the payment value or uncheck full payment | halfpayment check box.</p>');
                    $('#alertModal').modal({
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
            var table = $("#paymentDetailTable tbody");
            var total = '0';
            var invnetBal = '0';
            var invBal = '0';
            
            table.find('tr').each(function(i, el) {
                var $tds = $(this).find('td');
                var value = parseFloat($tds.eq(9).text());

                total = parseFloat(total);
                total = (total + value);

                var bal = parseFloat($tds.eq(5).text());
                invBal = parseFloat(invBal);
                invBal = (invBal + invBal);
            });

            total = parseFloat(total).toFixed(2);
            invBal = parseFloat(invBal).toFixed(2);
            invnetBal = parseFloat(total + invBal).toFixed(2);

            jsonObj = [];
            $("#paymentDetailTable tbody tr").each(function() {
                item = {}
                $(this).find('td').each(function(col_idx) {
                    item["col_" + (col_idx + 1)] = $(this).text();
                });
                jsonObj.push(item);
            });
            // console.log(jsonObj);

            var tablejson = JSON.stringify(jsonObj);

            if (invnetBal == '0.00') {
                $('#bodyAlert').html('<i class="fas fa-exclamation-triangle fa-pull-left fa-3x"></i><p>Please enter the payment value and press the create payment button</p>');
                $('#alertModal').modal({
                    keyboard: false,
                    backdrop: 'static'
                });
                $('#invPaymentCreateBtn').prop("disabled", true);
            } else {
                $('#paymentPayAmountWithoutdis').val(invnetBal);
                $('#paymentPayDiscount').val('0');
                $('#paymentPayAmount').val(invnetBal);
                $('#totAmount').html(invnetBal);
                $('#hideAllBalAmount').val(invnetBal);
                $('#paymentmodal').modal({
                    keyboard: false,
                    backdrop: 'static'
                });
                // $.ajax({
                //     type: "POST",
                //     data: {
                //         tablejson: tablejson
                //     },
                //     url: 'getprocess/getdiscountamountaccobillamount.php',
                //     success: function(result) { //alert(result);
                //         // console.log(result);
                //         var obj = JSON.parse(result);

                //         var discount=parseFloat(obj.totaldiscount).toFixed(2);

                //         var baltotal=parseFloat(invnetBal-discount).toFixed(2);

                //         $('#paymentPayAmountWithoutdis').val(invnetBal);
                //         $('#paymentPayDiscount').val(discount);
                //         $('#paymentPayAmount').val(baltotal);
                //         $('#totAmount').html(baltotal);
                //         $('#hideAllBalAmount').val(baltotal);
                //         $('#discountlist').val(obj.paymentlist);
                //         $('#paymentmodal').modal({
                //             keyboard: false,
                //             backdrop: 'static'
                //         });
                //     }
                // });
            }
        });
        $('#paymentMethod').change(function() {
            if ($(this).val() == '1') {
                $('#paymentCheque').prop("readonly", true);
                $('#paymentChequeNum').prop("readonly", true);
                $('#paymentchequeDate').prop("readonly", true);
                $('#paymentBank').prop("disabled", true);
                $('#paymentCash').prop("readonly", false);
                $('#creditnote').prop("disabled", true);
                $('#collapseOne').collapse('show');
            } else if ($(this).val() == '2') {
                $('#paymentCheque').prop("readonly", false);
                $('#paymentChequeNum').prop("readonly", false);
                $('#paymentchequeDate').prop("readonly", false);
                $('#paymentBank').prop("disabled", false);
                $('#paymentCash').prop("readonly", true);
                $('#creditnote').prop("disabled", true);
                $('#collapseTwo').collapse('show');
            } else if ($(this).val() == '3') {
                $('#paymentCheque').prop("readonly", true);
                $('#paymentChequeNum').prop("readonly", true);
                $('#paymentchequeDate').prop("readonly", true);
                $('#paymentBank').prop("disabled", true);
                $('#paymentCash').prop("readonly", true);
                $('#creditnote').prop("disabled", false);
                $('#collapseThree').collapse('show');
            }
        });
        $("#submitBtnModal").click(function() {
            if (!$("#formModal")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hideSubmitModal").click();
            } else {
                var paymenttype = $('#paymentMethod').val();
                var paymentCheque = $('#paymentCheque').val();
                var paymentChequeNum = $('#paymentChequeNum').val();
                var paymentReceiptNum = $('#paymentReceiptNum').val();
                var paymentchequeDate = $('#paymentchequeDate').val();
                var paymentBankID = $('#paymentBank').val();
                var paymentBank = $("#paymentBank option:selected").text();
                var paymentCash = $('#paymentCash').val();
                var creditnote = $('#creditnote').val();
                var creditnoteamount = $('#creditnote').find(':selected').attr('data-creditnoteamount');

                if(paymenttype==1){
                    $('#tblPaymentTypeModal > tbody:last').append('<tr><td>Cash</td><td class="text-right">' + parseFloat(paymentCash).toFixed(2) + '</td><td class=""></td><td class=""></td><td class=""></td><td>'+paymentReceiptNum+'</td><td></td><td></td><td class=""></td><td class="">1</td><td class=""></td></tr>');
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
                    $('#paymentMethod').val('');

                    $('#btnIssueInv').prop('disabled', false);
                }
                else if(paymenttype==2){
                    $('#tblPaymentTypeModal > tbody:last').append('<tr><td>Bank / Cheque</td><td class=""></td><td class="text-right">' + parseFloat(paymentCheque).toFixed(2) + '</td><td class=""></td><td class="">'+paymentChequeNum+'</td><td>'+paymentReceiptNum+'</td><td>'+paymentchequeDate+'</td><td>'+paymentBank+'</td><td class="">'+paymentBankID+'</td><td class="">2</td><td class=""></td></tr>');

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
                    $('#paymentchequeDate').val('').prop('readonly', true);
                    $('#paymentBank').val('').prop('disabled', true);
                    $('#paymentMethod').val('');

                    $('#btnIssueInv').prop('disabled', false);
                }
                else if(paymenttype==3){
                    $('#tblPaymentTypeModal > tbody:last').append('<tr><td>Credit Note</td><td class=""></td><td class=""></td><td class="text-right">' + parseFloat(creditnoteamount).toFixed(2) + '</td><td class=""></td><td class="">'+paymentReceiptNum+'</td><td></td><td></td><td></td><td class="">3</td><td class="">'+creditnote+'</td></tr>');

                    var paidAmount = parseFloat($('#hidePayAmount').val());
                    var PayAmount = parseFloat(creditnoteamount);
                    var paymentPayAmount = parseFloat($('#hideAllBalAmount').val());

                    paidAmount = (paidAmount + PayAmount);
                    var balance = (paymentPayAmount - paidAmount);
                    $('#hideBalAmount').val(balance);
                    $('#balanceAmount').html((balance).toFixed(2));
                    $('#payAmount').html((paidAmount).toFixed(2));
                    $('#hidePayAmount').val(paidAmount);

                    $('#creditnote').val('').prop('disabled', true);
                    $('#paymentMethod').val('');

                    $('#btnIssueInv').prop('disabled', false);
                }

                $('#collapseOne').collapse('hide');
                $('#collapseTwo').collapse('hide');
                $('#collapseThree').collapse('hide');
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
            var paymentdate = $('#paymentdate').val();
            // var discountlist = $('#discountlist').val();

            $.ajax({
                type: "POST",
                data: {
                    tblData: jsonObj,
                    tblPayData: jsonObjOne,
                    totAmount: totAmount,
                    payAmount: payAmount,
                    balAmount: balAmount,
                    paymentdate: paymentdate
                    // discountlist: discountlist
                },
                url: 'process/invoicepaymentprocess.php',
                success: function(result) { //alert(result);
                    console.log(result);
                    var obj = JSON.parse(result);
                    if(obj.paymentinvoice>0){
                        $('#paymentmodal').modal('hide');
                        paymentreceiptview(obj.paymentinvoice);
                        $('#paymentmodal').modal('hide');
                        $('#modalpaymentreceipt').modal('show');
                        // $('#discountlist').val('');
                    }
                    action(obj.action);
                }
            });
        });

        document.getElementById('btnreceiptprint').addEventListener ("click", print);

        $('#modalpaymentreceipt').on('hidden.bs.modal', function (e) {
            location.reload();
        });
    });

    function loadinvoice(customerID, asmID, invoiceno){
        $('#invoiceviewdetail').html('<div class="card border-0 shadow-none bg-transparent"><div class="card-body text-center"><img src="images/spinner.gif" alt="" srcset=""></div></div>');

        $.ajax({
            type: "POST",
            data: {
                customerID: customerID,
                asmID: asmID,
                invoiceno: invoiceno
            },
            url: 'getprocess/getinvoicepayment.php',
            success: function(result) {//alert(result);
                $('#invoiceviewdetail').html(result);
                $('#invPaymentCheckBtn').prop("disabled", false);
                tblcheckboxevent();
            }
        });  
    }

    function tblcheckboxevent() {
        $('#paymentDetailTable tbody').on('click', '.fullAmount', function() {
            var row = $(this);
            if ((row.closest('.fullAmount')).is(':checked')) {
                var fullAmount = row.closest("tr").find('td:eq(4)').text();
                row.closest("tr").find('td:eq(9)').text(fullAmount);
                row.closest("tr").find('td:eq(8)').find('.halfAmount').prop('checked', false);
                row.closest("tr").find('td:eq(10)').text('1');
            } else {
                row.closest("tr").find('td:eq(10)').text('0');
                row.closest("tr").find('td:eq(9)').text('0.00');
            }
        });

        $('#paymentDetailTable tbody').on('click', '.halfAmount', function() {
            var row = $(this);
            if ((row.closest('.halfAmount')).is(':checked')) {
                row.closest("tr").find('td:eq(7)').find('.fullAmount').prop('checked', false);
                row.closest("tr").find('td:eq(10)').text('2');
                tblpayamount();
            } else {
                tblTextRemove();
                row.closest("tr").find('td:eq(9)').text('0.00');
            }
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

    function tblpayamount() {
        $('.paidAmount').click(function(e) {
            if (($(this).closest('tr')).find('td:eq(8) .halfAmount').is(':checked')) {
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

</script>
<?php include "include/footer.php"; ?>


