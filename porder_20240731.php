<?php 
include "include/header.php";  

$sql="SELECT * FROM `tbl_porder` WHERE `confirmstatus` IN (1,0,2)";
$result =$conn-> query($sql); 

$sqlcommonnames="SELECT DISTINCT `common_name` FROM `tbl_product` WHERE `status`=1";
$resultcommonnames =$conn-> query($sqlcommonnames); 


include "include/topnavbar.php"; 
?>
<style>
    .tableprint {
        table-layout: fixed;
    }
    .porder-modal {
        max-width: 1000px;
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
                            <span>Purchase Order</span>
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
                                    <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right" id="btnordercreate">
                                        <i class="fas fa-plus"></i>&nbsp;Create Purchasing Order
                                    </button>
                                </div>
                                </div>
                                <hr>
                                <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
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
<!-- Modal Create Order -->
<div class="modal fade" id="modalcreateorder" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered porder-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">CREATE PURCHASING ORDER</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <form id="createorderform" autocomplete="off">
                            <div class="form-row mb-1">
                                <div class="col-3">
                                    <label class="small font-weight-bold text-dark">Order Date*</label>
                                    <div class="input-group input-group-sm">
                                        <input type="date" id="orderdate" name="orderdate"
                                            class="form-control form-control-sm" value="<?php echo date('Y-m-d') ?>"
                                            required>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <label class="small font-weight-bold text-dark">Common name*</label>
                                    <select class="form-control form-control-sm" name="productcommonname" id="productcommonname">
                                        <option value="">Select</option>
                                        <?php if($resultcommonnames->num_rows > 0) { while ($rowcommonname = $resultcommonnames->fetch_assoc()) { ?>
                                            <option value="<?php echo $rowcommonname['common_name'] ?>">
                                                <?php echo $rowcommonname['common_name'] ?>
                                            </option>
                                        <?php }} ?>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label class="small font-weight-bold text-dark">Supplier</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" id="suppliername" name="suppliername"
                                            class="form-control form-control-sm" readonly>
                                    </div>
                                </div>
                                <div class="col-3">
                                <label class="d-none small font-weight-bold text-dark">Supplier Id</label>
                                        <input type="hidden" id="supplierId" name="supplierId"
                                            class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                            <input type="hidden" name="unitprice" id="unitprice" value="">
                        </form>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                <table class="table table-striped table-bordered table-sm small" id="tableorder">
                                    <thead>
                                        <tr>
                                            <th style="width: 100px;">Product</th>
                                            <th class="d-none" style="width: 100px;">ProductID</th>
                                            <th class="d-none" style="width: 100px;">UnitPrice</th>
                                            <th class="text-center" style="width: 50px;">Unit Price</th>
                                            <th class="text-center" style="width: 50px;">Qty</th>
                                            <th class="d-none" style="width: 100px;">HideTotal</th>
                                            <th class="text-right" style="width: 100px;">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableBody"></tbody>
                                </table>
                            </div>
                        <div class="row">
                            <div class="col-7 text-right">
                                <h1 class="font-weight-600">Total</h1>
                            </div>
                            <div class="col-1 text-right">
                                <h1 class="font-weight-600">:</h1>
                            </div>
                            <div class="col-4 text-right">
                                <h1 class="font-weight-600" id="divtotal">Rs. 0.00</h1>
                            </div>
                            <input type="hidden" id="hidetotalorder" value="0">
                        </div>
                        <div class="row">
                            <div class="col-7 text-right">
                                <h1 class="font-weight-600">VAT %</h1>
                            </div>
                            <div class="col-1 text-right">
                                <h1 class="font-weight-600">:</h1>
                            </div>
                            <div class="col-4 text-right">
                                <input type="hidden" id="hidevatper" value="0">
                                <input type="hidden" id="hidevatamount" value="0">
                                <h1 class="font-weight-600" id="divvat">Rs. 0.00</h1>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-7 text-right">
                                <h1 class="font-weight-600">Net Total</h1>
                            </div>
                            <div class="col-1 text-right">
                                <h1 class="font-weight-600">:</h1>
                            </div>
                            <div class="col-4 text-right">
                                <h1 class="font-weight-600" id="divnettotal">Rs. 0.00</h1>
                            </div>
                            <input type="hidden" id="hidenettotal" value="0">
                        </div>
                        <hr>

                        <div class="form-group col-6 ">
                            <label class="small font-weight-bold text-dark">Remark</label>
                            <textarea name="remark" id="remark" class="form-control form-control-sm"></textarea>
                        </div>
                        <div class="form-group mt-2">
                            <button type="button" id="btncreateorder"
                                class="btn btn-outline-primary btn-sm fa-pull-right"
                                <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-save"></i>&nbsp;Create
                                Order</button>
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
                            <th class="text-center">Qty</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <div class="row">
                    <div class="col-12 text-right"><h1 class="font-weight-600" id="divtotalview">Rs. 0.00</h1></div>
                    <div class="col-12"><h6 class="title-style"><span>Remark Information</span></h6></div>
                    <div class="col-12"><div id="remarkview"></div></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal order print -->
<div class="modal fade" id="modalorderprint" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
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
                <button class="btn btn-danger btn-sm fa-pull-right" id="btnorderprint"><i class="fas fa-print"></i>&nbsp;Print Order</button>
            </div>
        </div>
    </div>
</div>

<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function() {

        $('body').tooltip({
            selector: '[data-toggle="tooltip"]'
        });
        $('[data-toggle="tooltip"]').tooltip({
            trigger : 'hover'
        });

        var addcheck='<?php echo $addcheck; ?>';
        var editcheck='<?php echo $editcheck; ?>';
        var statuscheck='<?php echo $statuscheck; ?>';
        var deletecheck='<?php echo $deletecheck; ?>';

        $('#dataTable').DataTable( {
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "scripts/porderlist.php",
                type: "POST", // you can use GET
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_porder"
                },
                {
                    "data": "orderdate"
                },
                {
                    "data": "name"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        return parseFloat(full['nettotal']).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    }
                },

                {
                    "targets": -1,
                    "className": 'text-center',
                    "data": null,
                    "render": function(data, type, full) {
                        var html = '';
                        if(full['confirmstatus']==1){
                            html+='<i class="fas fa-check text-success"></i>&nbsp;Confirm';
                        }
                        else if(full['confirmstatus']==2){
                            html+='<i class="fas fa-times text-danger"></i>&nbsp;Cancelled';
                        }
                        else{
                            html+='<i class="fas fa-times text-danger"></i>&nbsp;Not Confirm';
                        }
                        return html;     
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';
                        button +=
                        '<button class="btn btn-outline-primary btn-sm mr-1 btnprint" data-toggle="tooltip" data-placement="bottom" title="Print Order" id="' +
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


                        if(full['completestatus']==1){button+='<button class="btn btn-outline-yellow btn-sm mr-1 ';if(statuscheck==0){button+='d-none';}button+='"  data-toggle="tooltip" data-placement="bottom" title="Complete Order"><i class="fas fa-check-circle"></i></button>';}
                        else{button+='<a href="process/statusporder.php?record='+full['idtbl_porder']+'&type=8"  data-toggle="tooltip" data-placement="bottom" title="Complete Order" onclick="return allcustomer_confirm()" target="_self" class="btn btn-outline-pink btn-sm mr-1 ';if(statuscheck==0){button+='d-none';}button+='"><i class="fas fa-times"></i></a>';}
                        
                        if(full['confirmstatus']==1){button+='<button class="btn btn-outline-success btn-sm mr-1 ';if(statuscheck==0){button+='d-none';}button+='"><i class="fas fa-check"></i></button>';}
                        else{button+='<a href="process/statusporder.php?record='+full['idtbl_porder']+'&type=1" data-toggle="tooltip" data-placement="bottom" title="Confirm Order" onclick="return order_confirm()" target="_self" class="btn btn-outline-orange btn-sm mr-1 ';if(statuscheck==0 | full['confirmstatus']==2){button+='d-none';}button+='"><i class="fas fa-times"></i></a>';}

                        button+='<a href="process/statusporder.php?record='+full['idtbl_porder']+'&type=2" onclick="return delete_confirm()" target="_self" class="btn btn-outline-danger btn-sm mr-1 ';if(statuscheck==0){button+='d-none';}button+='"><i class="far fa-trash-alt"></i></a>';
                        
                        return button;
                    }
                }
            ]
        } );
        $('.dpd1a').datepicker({
            uiLibrary: 'bootstrap4',
            autoclose: 'true',
            todayHighlight: true,
            startDate: 'today',
            format: 'yyyy-mm-dd'
        });

        $('#productcommonname').change(function() {
                var commonName = $(this).val();
                if (commonName) {
                    $.ajax({
                        url: 'getprocess/get_supplier.php',
                        type: 'POST',
                        data: { common_name: commonName },
                        success: function(response) {
                            var data = JSON.parse(response);
                            $('#suppliername').val(data.suppliername);
                            $('#supplierId').val(data.idtbl_supplier);
                        }
                    });
                } else {
                    $('#suppliername').val('');
                    $('#supplierId').val('');
                }
            });

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

        $('#modalorderview').on('hidden.bs.modal', function (e) {
            $('#tableorderview > tbody').html('');
        });

        document.getElementById('btnorderprint').addEventListener ("click", print);

        // Create order part
        $('#productcommonname').change(function () {
            var commonName = $(this).val();

            if (commonName) {
                $.ajax({
                    url: 'getprocess/get_products.php',
                    type: 'GET',
                    data: {
                        common_name: commonName
                    },
                    dataType: 'json',
                    success: function (data) {
                        var tableBody = $('#tableBody');
                        tableBody.empty();

                        // Set VAT value in the hidden field
                        $('#hidevatper').val(data.vat);

                        $.each(data, function (index, product) {
                            if (index !== 'vat') {
                                tableBody.append('<tr>' +
                                    '<td>' + product.product_name + '</td>' +
                                    '<td class="d-none">' + product.idtbl_product + '</td>' +
                                    '<td class="d-none">' + product.unitprice + '</td>' +
                                    '<td class="text-center">' + addCommas(parseFloat(product.unitprice).toFixed(2)) + '</td>' +
                                    '<td class="text-center"><input type="text" class="input-integer form-control form-control-sm custom-width" name="new_quantity[]" value="0"></td>' +
                                    '<td class="d-none hide-total-column"><input type="number" class="form-control form-control-sm custom-width" name="hidetotal_quantity[]" value="0"></td>' +
                                    '<td class="text-right total-column"><input type="number" class="input-integer-decimal form-control form-control-sm custom-width" name="total_quantity[]" value="0" readonly></td>' +
                                    '</tr>');
                            }

                            $('.input-integer').on('input', function () {
                                var inputValue = $(this).val().replace(/\D/g, '');
                                if (inputValue === '' || inputValue === '0') {
                                    $(this).val('');
                                } else {
                                    $(this).val(inputValue);
                                }
                            });

                            $('.input-integer').on('blur', function () {
                                var inputValue = $(this).val().trim();
                                if (inputValue === '') {
                                    $(this).val('0');
                                }
                            });
                        });
                    },
                    error: function (error) {
                        console.log('Error fetching products:', error);
                    }
                });
            } else {
                $('#tableBody').empty();
            }
        });

        $('#btnordercreate').click(function () {
            $('#modalcreateorder').modal('show');
            $('#modalcreateorder').on('shown.bs.modal', function () {
                $('#orderdate').trigger('focus');
            });
        });

        $('#tableBody').on('input', 'input[name^="new_quantity"]', function () {
            var row = $(this).closest('tr');
            updateTotalForRow(row);
            updateGrandTotal();
        });


        function updateTotalForRow(row) {
            var newQuantity = parseFloat(row.find('input[name^="new_quantity"]').val()) || 0;
            var unitPrice = parseFloat(row.find('td:eq(2)').text()) || 0;

            var newTotal = newQuantity * unitPrice;

            var totalColumn = row.find('td:eq(6)');
            var formattedTotal = newTotal.toFixed(2);
            totalColumn.find('input[name^="total_quantity"]').val(formattedTotal);

            var hideTotalColumn = row.find('.hide-total-column');
            var formattedHideTotal = newTotal.toFixed(5);
            hideTotalColumn.find('input[name^="hidetotal_quantity"]').val(formattedHideTotal);
        }

        function updateGrandTotal() {
            var grandTotal = 0;

            $('#tableBody').find('input[name^="total_quantity"]').each(function () {
                var total = parseFloat($(this).val().replace(/,/g, '')) || 0;
                grandTotal += total;
            });

            var vatPercentage = parseFloat($('#hidevatper').val()) || 0;
            var vatAmount = grandTotal * (vatPercentage / 100);
            var netTotal = grandTotal + vatAmount;

            $('#divtotal').text('Rs. ' + addCommas(grandTotal.toFixed(2)));
            $('#divvat').text('Rs. ' + addCommas(vatAmount.toFixed(2)));
            $('#divnettotal').text('Rs. ' + addCommas(netTotal.toFixed(2)));

            $('#hidetotalorder').val(grandTotal.toFixed(2));
            $('#hidenettotal').val(netTotal.toFixed(2));
            $('#hidevatamount').val(vatAmount.toFixed(2));

        }

        $('#btncreateorder').click(function () {
            // Collect data from the form and table
            var orderDate = $('#orderdate').val();
            var remark = $('#remark').val();
            var total = $('#hidetotalorder').val();
            var supplierId = $('#supplierId').val();
            var vatper = $('#hidevatper').val();
            var vatamount = $('#hidevatamount').val();
            var nettotal = $('#hidenettotal').val();

            var orderDetails = [];
            $('#tableBody tr').each(function () {
            var productId = $(this).find('td:eq(1)').text();
            var unitprice = $(this).find('td:eq(2)').text();
            var newQty = $(this).find('input[name^="new_quantity"]').val();
            var unittotal = $(this).find('input[name^="hidetotal_quantity"]').val();


            orderDetails.push({
                productId: productId,
                unitPrice: unitprice,
                newQty: newQty
            });
        });

            // Send data to the server
            $.ajax({
                url: 'process/porderprocess.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    orderdate: orderDate,
                    remark: remark,
                    total: total,
                    nettotal: nettotal,
                    vatper: vatper,
                    vatamount: vatamount,
                    supplierId: supplierId,
                    orderDetails: orderDetails
                },
                success: function(result) {
                    $('#modalcreateorder').modal('hide');
                    action(JSON.stringify(result)); // Convert the object to a JSON-formatted string
                    // Optionally reload the page after a delay or user interaction
                    // setTimeout(function() { location.reload(); }, 2000); // Reload after 2 seconds
                    location.reload();

                }
            });
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

    function order_confirm() {
        return confirm("Are you sure you want to Confirm this order?");
    }

    function delete_confirm() {
        return confirm("Are you sure you want to remove this?");
    }
    function allcustomer_confirm() {
        return confirm("Are you sure you want to complete this order?");
    }

    function datepickercloneload(){
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
