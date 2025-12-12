<?php
include "include/header.php";

$sqlcustomer = "SELECT `idtbl_customer`, `customer` FROM `tbl_customer` WHERE `status`=1 ORDER BY `name` ASC";
$resultcustomer = $conn->query($sqlcustomer);


$sqlproduct = "SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1";
$resultproduct = $conn->query($sqlproduct);


$sqlhelperlist = "SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `tbl_user_type_idtbl_user_type`=7 AND `status`=1";
$resulthelperlist = $conn->query($sqlhelperlist);

$sqlsupplier = "SELECT `idtbl_supplier`, `suppliername` FROM `tbl_supplier` WHERE `status`=1";
$resultsupplier = $conn->query($sqlsupplier);

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
                            <div class="page-header-icon"><i data-feather="corner-down-left"></i></div>
                            <span>Product Return</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-row">
                                    <div class="col-3 mt-4">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="invoicestatus"
                                                id="invoicable" value="1" checked>
                                            <label class="form-check-label" for="invoicable">Invoice return</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="invoicestatus"
                                                id="noninvoicable" value="0">
                                            <label class="form-check-label" for="noninvoicable">Non Invoice
                                                Return</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row mt-3">
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Return type*</label>
                                        <select name="returntype" id="returntype"
                                            class="form-control form-control-sm rounded-0" required>
                                            <option value="">Select</option>
                                            <option value="1">Customer return</option>
                                            <option value="2">Supplier return</option>
                                            <option value="3">Damage return</option>
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Customer*</label>
                                        <div class="input-group input-group-sm">
                                            <select class="form-control form-control-sm rounded-0" name="customer"
                                                id="customer">
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-3" id="supplierdiv">
                                        <label class="small font-weight-bold text-dark">Supplier*</label>
                                        <div class="input-group input-group-sm">
                                            <select class="form-control form-control-sm rounded-0" name="supplier"
                                                id="supplier">
                                                <option value="">Select</option>
                                                <?php if ($resultsupplier->num_rows > 0) {
                                                    while ($rowsupplier = $resultsupplier->fetch_assoc()) { ?>
                                                        <option value="<?php echo $rowsupplier['idtbl_supplier'] ?>">
                                                            <?php echo $rowsupplier['suppliername'] ?></option>
                                                <?php }
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-3" id="fieldcustomerinvoice">
                                        <label class="small font-weight-bold text-dark">Customer Invoice*</label>
                                        <div class="input-group input-group-sm">
                                            <select class="form-control form-control-sm rounded-0"
                                                name="customerinvoice" id="customerinvoice">
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-3" id="reasontypdiv">
                                        <label class="small font-weight-bold text-dark">Reason Type*</label>
                                        <div class="input-group input-group-sm">
                                            <select class="form-control form-control-sm rounded-0"
                                                name="reasontype" id="reasontype">
                                                <option value="" disabled selected>Select</option>
                                                <option value="1">Damage</option>
                                                <option value="2">Exchange</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-3" id="fieldcustomerinvoice">
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
                                </div>
                                <div id="addproductdiv" class="mt-2">
                                    <form id="subform" type="post">
                                        <div class="form-row">
                                            <div class="col">
                                                <label class="small font-weight-bold text-dark">Product*</label>
                                                <select class="form-control form-control-sm" name="returnproduct"
                                                    id="returnproduct" required>
                                                    <option value="">Select</option>
                                                </select>
                                            </div>
                                            <div class="col">
                                                <label class="small font-weight-bold text-dark">Sale Price</label>
                                                <input id="saleprice" type="text" name="saleprice"
                                                    class="form-control form-control-sm" placeholder="">
                                            </div>
                                            <div class="col">
                                                <label class="small font-weight-bold text-dark">Invoice Qty</label>
                                                <input id="invoiceqty" type="text" name="invoiceqty"
                                                    class="form-control form-control-sm" placeholder="">
                                            </div>
                                        </div>
                                        <div class="form-row mt-3">
                                            <div class="col text-right">
                                                <button type="button" id="subformsubmit"
                                                    class="btn btn-outline-primary btn-sm px-4"
                                                    <?php if ($addcheck == 0) {
                                                        echo 'disabled';
                                                    } ?>>
                                                    <i class="far fa-save"></i>&nbsp;Add Product
                                                </button>
                                            </div>
                                        </div>
                                        <input type="submit" class="d-none" id="hiddensubformsubmit">
                                    </form>
                                </div>

                            </div>
                        </div>
                        <hr class="border-dark">
                        <div class="row">
                            <div class="col-12">
                                <form id="returnform" method="post" autocomplete="off">
                                    <div class="row">
                                        <div class="col-md-8" id="getinvotable">
                                            <small id="" class="form-text text-danger">Select and Enter Return
                                                Quantity</small>
                                            <table class="table table-hover small" id="tablereturnamount">
                                                <thead>
                                                    <tr>
                                                        <th class="d-none">#</th>
                                                        <th class="d-none">Product Id</th>
                                                        <th class="d-none">Detail Id</th>
                                                        <th>Product</th>
                                                        <th>Sale Price</th>
                                                        <th>Qty</th>
                                                        <th>Return Qty</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-4" id="reasondiv">
                                            <label class="small font-weight-bold text-dark">Remarks</label>
                                            <textarea class="form-control form-control-sm" id="remarks"
                                                name="remarks"></textarea>
                                            <div class="form-group mt-3">
                                                <button type="button" id="formsubmit"
                                                    class="btn btn-outline-primary btn-sm px-4 fa-pull-right"
                                                    <?php if ($addcheck == 0) {
                                                        echo 'disabled';
                                                    } ?>>
                                                    <i class="far fa-save"></i>&nbsp;Add
                                                </button>
                                                <button class="d-none" id="submitBtn">Submit</button>
                                            </div>
                                            <input type="hidden" name="recordID" id="recordID" value="">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-striped table-bordered table-sm small" id="tablereturn">
                                        <thead>
                                            <tr>
                                                <th class="d-none">ProductID</th>
                                                <th>Product</th>
                                                <th>Sale price</th>
                                                <th>Quantity</th>
                                                <th>Discount</th>
                                                <th class="d-none">total</th>
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
                                            <h5 class="font-weight-600" id="divtotal">Rs. 0.00</h5>
                                        </div>
                                        <div class="col-9 text-right">
                                            <h5 class="font-weight-600">Discount</h5>
                                        </div>
                                        <div class="col-3 text-right">
                                            <h5 class="font-weight-600" id="discountedprice">Rs. 0.00</h5>
                                        </div>
                                        <div class="col-9 text-right">
                                            <h1 class="font-weight-600">Nettotal</h1>
                                        </div>
                                        <div class="col-3 text-right">
                                            <h1 class="font-weight-600" id="divtotalview">Rs. 0.00</h1>
                                        </div>

                                        <input type="hidden" id="hidetotalorder" value="0">
                                        <input type="hidden" id="hidedis" value="0">
                                        <input type="hidden" id="hidenetamount" value="0">
                                    </div>
                                    <div class="form-group mt-2">
                                        <button type="button" id="btnCreateReturn"
                                            class="btn btn-outline-primary btn-sm fa-pull-right"
                                            <?php if ($addcheck == 0) {
                                                echo 'disabled';
                                            } ?>><i
                                                class="fas fa-save"></i>&nbsp;Create Return</button>
                                    </div>
                                    <input type="hidden" id="hidetotalorder2" value="">

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
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function() {
        var addcheck = '<?php echo $addcheck; ?>';
        var editcheck = '<?php echo $editcheck; ?>';
        var statuscheck = '<?php echo $statuscheck; ?>';
        var deletecheck = '<?php echo $deletecheck; ?>';

        $('body').tooltip({
            selector: '[data-toggle="tooltip"]'
        });
        $('[data-toggle="tooltip"]').tooltip({
            trigger: 'hover'
        });

        // Initialize Select2 for return product
        $("#returnproduct").select2({
            ajax: {
                url: "getprocess/getproductforselect2.php",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        searchTerm: params.term,
                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        // Initialize Select2 for customer
        $("#customer").select2({
            ajax: {
                url: "getprocess/getcustomerlistforreturn.php",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        searchTerm: params.term,
                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        // Hide unnecessary fields initially
        $('#addproductdiv').addClass('d-none');
        $('#fieldcustomerinvoice').removeClass('d-none');
        $('#supplierdiv').addClass('d-none');
        $('#reasondiv').removeClass('d-none');
        $('#reasontype').prop('required', false);
        $('#reasontypdiv').addClass('d-none');

        // Handle invoice status radio button change
        $('input[name="invoicestatus"]').click(function() {
            var invoiceStatus = $(this).val();
            var returntype = $('#returntype').val();

            if (invoiceStatus == 1) { // Invoice return
                $('#addproductdiv').addClass('d-none');
                $('#fieldcustomerinvoice').removeClass('d-none');
            } else { // Non Invoice Return
                $('#addproductdiv').removeClass('d-none');
                $('#fieldcustomerinvoice').addClass('d-none');
            }

            // Trigger return type change to update fields properly
            if (returntype) {
                $('#returntype').trigger('change');
            }
        });

        // Handle return type change
        $('#returntype').change(function() {
            var type = $(this).val();
            var invoiceStatus = $('input[name="invoicestatus"]:checked').val();

            // Reset all fields
            $('#tablereturnamount tbody').empty();
            $('#tablereturn tbody').empty();
            $('#customer').val('').trigger('change');
            $('#supplier').val('').trigger('change');
            $('#customerinvoice').val('').trigger('change');
            $('#returnproduct').val('').trigger('change');
            resetTotals();

            if (type == 1) { // Customer return
                $('#customer').closest('.col-3').removeClass('d-none');
                $('#customer').prop('required', true);
                $('#supplierdiv').addClass('d-none');
                $('#supplier').prop('required', false);
                $('#reasondiv').removeClass('d-none');
                $('#remarks').prop('required', false);
                $('#repname').prop('required', true);
                $('#reasontypdiv').removeClass('d-none');
                $('#reasontype').prop('required', true);
                $('#repname').closest('.col-3').removeClass('d-none');

                // Show/hide based on invoice status
                if (invoiceStatus == 1) {
                    $('#addproductdiv').addClass('d-none');
                    $('#fieldcustomerinvoice').removeClass('d-none');
                } else {
                    $('#addproductdiv').removeClass('d-none');
                    $('#fieldcustomerinvoice').addClass('d-none');
                }

            } else if (type == 2) { // Supplier return
                $('#customer').closest('.col-3').addClass('d-none');
                $('#customer').prop('required', false);
                $('#supplierdiv').removeClass('d-none');
                $('#supplier').prop('required', true);
                $('#reasondiv').removeClass('d-none');
                $('#remarks').prop('required', false);
                $('#addproductdiv').removeClass('d-none');
                $('#fieldcustomerinvoice').addClass('d-none');
                $('#repname').prop('required', false);
                $('#repname').closest('.col-3').addClass('d-none');
                $('#reasontypdiv').addClass('d-none');
                $('#reasontype').prop('required', false);
                $('#reasontype').val('');

            } else if (type == 3) { // Damage return
                $('#customer').closest('.col-3').removeClass('d-none');
                $('#customer').prop('required', true);
                $('#supplierdiv').addClass('d-none');
                $('#supplier').prop('required', false);
                $('#reasondiv').removeClass('d-none');
                $('#remarks').prop('required', true);
                $('#repname').prop('required', true);
                $('#repname').closest('.col-3').removeClass('d-none');
                $('#reasontypdiv').addClass('d-none');
                $('#reasontype').prop('required', false);
                $('#reasontype').val('');

                // Show/hide based on invoice status
                if (invoiceStatus == 1) {
                    $('#addproductdiv').addClass('d-none');
                    $('#fieldcustomerinvoice').removeClass('d-none');
                } else {
                    $('#addproductdiv').removeClass('d-none');
                    $('#fieldcustomerinvoice').addClass('d-none');
                }
            }
        });

        // Handle supplier change - Load products for that supplier
        $('#supplier').change(function() {
            var supplierId = $(this).val();

            // Reinitialize returnproduct select2 with supplier filter
            $("#returnproduct").select2('destroy');
            $("#returnproduct").select2({
                ajax: {
                    url: "getprocess/getproductaccosupplier.php",
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            searchTerm: params.term,
                            supplierId: supplierId
                        };
                    },
                    processResults: function(response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            });
        });

        // Handle customer change for invoice returns
        $('#customer').change(function() {
            var customer = $(this).val();

            $.ajax({
                type: "POST",
                data: {
                    customerId: customer,
                },
                url: 'getprocess/getinvoicesaccocustomer.php',
                success: function(result) {
                    var objfirst = JSON.parse(result);
                    var html = '';
                    html += '<option value="">Select</option>';
                    $.each(objfirst, function(i, item) {
                        html += '<option value="' + objfirst[i].invoiceId + '">';
                        html += objfirst[i].invoiceId + ' - ' + objfirst[i].invoiceNo;
                        html += '</option>';
                    });
                    $('#customerinvoice').empty().append(html);
                }
            });
        });

        // Handle customer invoice change
        $('#customerinvoice').change(function() {
            var invoiceId = $(this).val();
            $('#tablereturnamount tbody').empty();

            $.ajax({
                type: "POST",
                data: {
                    invoiceId: invoiceId,
                },
                url: 'getprocess/returngettable.php',
                success: function(result) {
                    var obj = JSON.parse(result);
                    $.each(obj, function(i, item) {
                        $('#tablereturnamount > tbody:last').append(
                            '<tr class="pointer"><td class="d-none">' +
                            obj[i].invoiceid +
                            '</td><td class="d-none">' +
                            obj[i].productid +
                            '</td><td class="d-none">' +
                            obj[i].invoicedetailid +
                            '</td><td>' +
                            obj[i].productname +
                            '</td><td><input type="text" name="editsalepricereturn" value="' +
                            obj[i].saleprice + '"></td><td>' +
                            obj[i].qty +
                            '</td><td><input type="text" name="editqtyreturn" value="0"></td></tr>'
                        );
                    });
                }
            });
        });

        // Add product to return table (for non-invoice returns)
        $('#subformsubmit').click(function() {
            var returntype = $('#returntype').val();

            // Check if return type is selected first
            if (!returntype) {
                alert('Please select return type first');
                return;
            }

            var returnproduct = $('#returnproduct').val();

            if (!returnproduct) {
                alert('Please select a product');
                return;
            }

            // For supplier return, get unit price and stock ID from select2 data
            if (returntype == 2) {
                var selectedOption = $("#returnproduct").select2('data')[0];
                var unitprice = selectedOption.unitprice || 0;
                var stockQty = selectedOption.stock || 0;
                var stockId = selectedOption.stockid || 0;
                var batchQty = selectedOption.batchqty || '';
                var returnproductname = selectedOption.text;

                $('#tablereturnamount > tbody:last').append(
                    '<tr class="pointer">' +
                    '<td class="d-none">0</td>' +
                    '<td class="d-none">' + returnproduct + '</td>' +
                    '<td class="d-none">0</td>' +
                    '<td>' + returnproductname + '</td>' +
                    '<td><input type="text" name="editsalepricereturn" value="' + unitprice + '" readonly></td>' +
                    '<td>' + stockQty + '</td>' +
                    '<td><input type="text" name="editqtyreturn" value="0"></td>' +
                    '<td class="d-none">' + stockId + '</td>' +
                    '<td class="d-none">' + batchQty + '</td>' +
                    '</tr>'
                );

                $('#returnproduct').val('').trigger('change');
            } else {
                // For other return types - validate all fields
                if (!$("#subform")[0].checkValidity()) {
                    $("#hiddensubformsubmit").click();
                    return;
                }

                var returnproductname = $("#returnproduct option:selected").text();
                var saleprice = $('#saleprice').val();
                var invoiceqty = $('#invoiceqty').val();

                if (!saleprice || !invoiceqty) {
                    alert('Please fill all fields');
                    return;
                }

                $('#tablereturnamount > tbody:last').append(
                    '<tr class="pointer">' +
                    '<td class="d-none">0</td>' +
                    '<td class="d-none">' + returnproduct + '</td>' +
                    '<td class="d-none">0</td>' +
                    '<td>' + returnproductname + '</td>' +
                    '<td><input type="text" name="editsalepricereturn" value="' + saleprice + '"></td>' +
                    '<td>' + invoiceqty + '</td>' +
                    '<td><input type="text" name="editqtyreturn" value="0"></td>' +
                    '<td class="d-none">0</td>' +
                    '<td class="d-none"></td>' +
                    '</tr>'
                );

                $('#returnproduct').val('').trigger('change');
                $('#saleprice').val('');
                $('#invoiceqty').val('');
            }
        });

        // Process return items and add to final table
        $("#formsubmit").click(function() {
            if (!$("#returnform")[0].checkValidity()) {
                $("#submitBtn").click();
            } else {
                $('#tablereturn > tbody').empty();

                $('#tablereturnamount tbody tr').each(function() {
                    var invoiceId = $(this).find('td:eq(0)').text();
                    var productId = $(this).find('td:eq(1)').text();
                    var invoiceDetailId = $(this).find('td:eq(2)').text();
                    var productName = $(this).find('td:eq(3)').text();
                    var qty = $(this).find('td:eq(5)').text();
                    var inputValue = $(this).find('input[name="editqtyreturn"]').val();
                    var saleprice = $(this).find('input[name="editsalepricereturn"]').val();
                    var stockId = $(this).find('td:eq(7)').text();
                    var batchQty = $(this).find('td:eq(8)').text();

                    var salepricenoformat = parseFloat(saleprice.replace(/,/g, ''));
                    var showtotalreturn = addCommas(parseFloat(inputValue * salepricenoformat).toFixed(2));

                    if (inputValue != 0 && parseFloat(inputValue) > 0) {
                        $('#tablereturn > tbody:last').append(
                            '<tr class="pointer">' +
                            '<td class="d-none">' + productId + '</td>' +
                            '<td>' + productName + '</td>' +
                            '<td>' + saleprice + '</td>' +
                            '<td>' + inputValue + '</td>' +
                            '<td class="">' + 0 + '</td>' +
                            '<td class="total d-none">' + (salepricenoformat * inputValue) + '</td>' +
                            '<td class="text-right">' + showtotalreturn + '</td>' +
                            '<td class="discount d-none">' + 0 + '</td>' +
                            '<td class="d-none">' + 0 + '</td>' +
                            '<td class="d-none">' + invoiceDetailId + '</td>' +
                            '<td class="d-none">' + salepricenoformat + '</td>' +
                            '<td class="d-none">' + stockId + '</td>' +
                            '<td class="d-none">' + batchQty + '</td>' +
                            '</tr>'
                        );
                    }
                });

                $('#tablereturnamount tbody').empty();
                calculateTotals();
            }
        });

        // Create return - Submit to database
        $('#btnCreateReturn').click(function() {
            var tbody = $("#tablereturn tbody");
            if (tbody.children().length > 0) {
                jsonObj = [];
                $("#tablereturn tbody tr").each(function() {
                    item = {}
                    $(this).find('td').each(function(col_idx) {
                        item["col_" + (col_idx + 1)] = $(this).text();
                    });
                    jsonObj.push(item);
                });

                var returntype = $('#returntype').val();
                var customer = $('#customer').val();
                var supplier = $('#supplier').val();
                var customerinvoice = $('#customerinvoice').val();
                var total = $('#hidetotalorder').val();
                var remarks = $('#remarks').val();
                var discountamount = $('#hidedis').val();
                var netamount = $('#hidenetamount').val();
                var repId = $('#repname').val();
                var invoicestatus = $('input[name="invoicestatus"]:checked').val();
                var reasontype = $('#reasontype').val();

                // Validation
                if (!returntype) {
                    alert('Please select return type');
                    return;
                }

                if (returntype == 1 || returntype == 3) {
                    if (!customer) {
                        alert('Please select customer');
                        return;
                    }
                    if (!repId) {
                        alert('Please select rep name');
                        return;
                    }
                }

                if (returntype == 2) {
                    if (!supplier) {
                        alert('Please select supplier');
                        return;
                    }
                    repId = 0;
                }

                $.ajax({
                    type: "POST",
                    data: {
                        tableData: jsonObj,
                        returntype: returntype,
                        total: total,
                        remarks: remarks,
                        customer: customer,
                        supplier: supplier,
                        customerinvoice: customerinvoice,
                        discountamount: discountamount,
                        netamount: netamount,
                        invoicestatus: invoicestatus,
                        repId: repId,
                        reasontype: reasontype
                    },
                    url: 'process/returnprocess.php',
                    success: function(result) {
                        console.log('Server Response:', result);
                        try {
                            var response = JSON.parse(result);
                            alert(response.message);
                            if (response.type === 'success') {
                                location.reload();
                            }
                        } catch (e) {
                            console.error('Parse error:', e);
                            console.error('Raw response:', result);
                            alert('Error: ' + result);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                        console.error('Response:', xhr.responseText);
                        alert('Error creating return: ' + error + '\nCheck console for details');
                    }
                });
            } else {
                alert('Please add products to return');
            }
        });

        // Remove item from final table
        $('#tablereturn').on('click', 'tr', function() {
            var r = confirm("Are you sure, You want to remove this?");
            if (r == true) {
                $(this).closest('tr').remove();
                calculateTotals();
            }
        });

        // Helper function to calculate totals
        function calculateTotals() {
            var sum = 0;
            $(".total").each(function() {
                sum += parseFloat($(this).text());
            });
            var showsum = addCommas(parseFloat(sum).toFixed(2));

            var sumdis = 0;
            $(".discount").each(function() {
                sumdis += parseFloat($(this).text());
            });
            var showsumdis = addCommas(parseFloat(sumdis).toFixed(2));
            var hidedis = parseFloat(sumdis);
            var nettotal = sum - sumdis;
            var nettotalshow = addCommas(nettotal.toFixed(2));

            $('#discountedprice').html('Rs. ' + showsumdis);
            $('#divtotal').html('Rs. ' + showsum);
            $('#divtotalview').html('Rs. ' + nettotalshow);

            $('#hidetotalorder').val(sum);
            $('#hidedis').val(hidedis);
            $('#hidenetamount').val(nettotal);
        }

        // Helper function to reset totals
        function resetTotals() {
            $('#discountedprice').html('Rs. 0.00');
            $('#divtotal').html('Rs. 0.00');
            $('#divtotalview').html('Rs. 0.00');
            $('#hidetotalorder').val(0);
            $('#hidedis').val(0);
            $('#hidenetamount').val(0);
        }

        // Helper function to add commas to numbers
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
    });

    function accept_confirm() {
        return confirm("Are you sure you want to Accept this?");
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

    function company_confirm() {
        return confirm("Are you sure this product send to company?");
    }

    function warehouse_confirm() {
        return confirm("Are you sure this product back to warehouse?");
    }

    function customer_confirm() {
        return confirm("Are you sure this product return back to customer?");
    }
</script>
<?php include "include/footer.php"; ?>