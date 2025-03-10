<?php 
include "include/header.php";  

$sqlProduct = "SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1";
$resultProduct =$conn-> query($sqlProduct);

$sqlGrnNum="SELECT `idtbl_grn` FROM `tbl_grn` ORDER BY `idtbl_grn` DESC LIMIT 1";   
$resultGrnNum=$conn->query($sqlGrnNum);
$rowGrnNum=$resultGrnNum->fetch_assoc();
$numRowsGrnNum=mysqli_num_rows($resultGrnNum);
$numRowsGrnNum=$rowGrnNum['idtbl_grn']+1;
$grnid=$numRowsGrnNum;
if($numRowsGrnNum>0){$GRNNum="GRN-".($numRowsGrnNum);}else{$GRNNum="GRN-1";}

$currentDate = date('mdY');
$batchNo = "BTCH" . $currentDate . $grnid;

$sqlorder="SELECT `idtbl_porder` FROM `tbl_porder` WHERE `status`=1 AND `confirmstatus`=1  AND `grnissuestatus` = 0";
$resultorder =$conn-> query($sqlorder); 

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
                            <div class="page-header-icon"><i data-feather="shopping-cart"></i></div>
                            <span>GRN Information</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col">
                                    <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right" id="btnordercreate">
                                        <i class="fas fa-plus"></i>&nbsp;Create Goods Received Note
                                    </button>
                                </div>
                        </div>
                        <hr>
                                <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>GRN</th>
                                            <th>Invoice No</th>
                                            <th>Dispatch No</th>
                                            <th>Batch No</th>
                                            <th class="text-right">Total</th>
                                            <th class="text-right">VAT</th>
                                            <th class="text-right">Nettotal</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                </table>
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
    <div class="modal-dialog modal-dialog-centered porder-modal modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">CREATE GOODS RECEIVED NOTE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <form action="#" method="post" autocomplete="off" id="grnFrom">
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">GRN Number</label>
                                    <input type="text" class="form-control form-control-sm" placeholder="" name="grnnum"
                                        id="grnnum" value="<?php echo $GRNNum; ?>" readonly>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">GRN Date</label>
                                    <div class="input-group input-group-sm">
                                        <input type="date" id="grndate" name="grndate"
                                            class="form-control form-control-sm" value="<?php echo date('Y-m-d') ?>"
                                            required>
                                    </div>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Purches Order</label>
                                    <select name="ponumber" id="ponumber" class="form-control form-control-sm">
                                        <option value="">Select</option>
                                        <?php if($resultorder->num_rows > 0) {while ($roworder = $resultorder-> fetch_assoc()) { ?>
                                        <option value="<?php echo $roworder['idtbl_porder'] ?>">
                                            <?php echo 'PO-'.$roworder['idtbl_porder'] ?></option>
                                        <?php }} ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Invoice Number*</label>
                                    <input type="text" class="form-control form-control-sm" placeholder=""
                                        name="grninvoice" id="grninvoice" required>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Delivery Number*</label>
                                    <input type="text" class="form-control form-control-sm" placeholder=""
                                        name="grndispatch" id="grndispatch" required>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Batch Number*</label>
                                    <input type="text" class="form-control form-control-sm" name="batchno" id="batchno"
                                        value="<?php echo $batchNo; ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group mt-2">
                                <input name="submitBtn" type="submit" value="Save" id="submitBtn" class="d-none">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-12">
                        <h6 class="title-style small font-weight-bold mt-2"><span>GRN Detail</span></h6>
                        <table class="table table-bordered table-sm table-striped" id="tableGrnList">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="d-none">ProductID</th>
                                    <th class="d-none">Unitprice</th>
                                    <th class="text-right">Unit Price</th>
                                    <th class="text-right">Sale Price</th>
                                    <th class="text-center">Qty</th>
                                    <th class="d-none">Hidetotal</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody id="tbodygrncreate"></tbody>
                        </table>
                        <div class="row">
                            <div class="col-sm-12 col-md-9 col-lg-9 col-xl-9 text-right">
                                <h4>Total : </h4>
                            </div>
                            <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 text-right">
                                <h3 class="text-dark" id="showPricewithoutvat">0.00</h3>
                            </div>
                            <div class="col-sm-12 col-md-9 col-lg-9 col-xl-9 text-right">
                                <h4>VAT Amount : </h4>
                            </div>
                            <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 text-right">
                                <h3 class="text-dark" id="showtaxAmount">0.00</h3>
                            </div>
                            <div class="col-sm-12 col-md-9 col-lg-9 col-xl-9 text-right">
                                <h4>Total + (VAT) : </h4>
                            </div>
                            <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 text-right">
                                <h3 class="text-dark" id="showPrice">0.00</h3>
                            </div>
                            <input type="hidden" id="txtShowPricewithoutvat" value="">
                            <input type="hidden" id="txtShowtaxAmount" value="">
                            <input type="hidden" id="txtShowPrice" value="">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <hr class="border-dark">
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right px-5"
                                    id="btnSaveGrn"><i class="far fa-save"></i>&nbsp;Save GRN</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal GRn Detail -->
<div class="modal fade" id="modalgrndetail" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content  bg-warning-soft">
            <div class="modal-header p-2">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="viewgrndetail"></div>
            </div>
        </div>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function() {
        checkdayendprocess();
        $('.dpd1a').datepicker({
            uiLibrary: 'bootstrap4',
            autoclose: 'true',
            todayHighlight: true,
            startDate: 'today',
            format: 'yyyy-mm-dd'
        });
        $('#ponumber').change(function(){
            var ponumber = $(this).val();

            $.ajax({
                type: "POST",
                data: {
                    ponumber : ponumber
                },
                url: 'getprocess/getporderinfoforgrn.php',
                success: function(result) {//alert(result);
                    $('#tbodygrncreate').html(result);
                    tabletotal();
                    orderoption();
                }
            });
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
                url: "scripts/grnlist.php",
                type: "POST", // you can use GET
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_grn"
                },
                {
                    "data": "date"
                },
                {
                    "data": "idtbl_grn",
                    "render": function(data, type, row) {
                        return 'GRN-' + data;
                    }
                },
                {
                    "data": "invoicenum"
                },
                {
                    "data": "dispatchnum"
                },
                {
                    "data": "batchno"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        return parseFloat(full['total']).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        return parseFloat(full['vatamount']).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    }
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
                        if(full['confirm_status']==0){
                            html+='<i class="fas fa-times text-danger"></i>&nbsp;Not Approved';
                        }
                        else{
                            html+='<i class="fas fa-check text-success"></i>&nbsp;Approved GRN';
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

                        button += '<button class="btn btn-outline-dark btn-sm mr-1 btnView ';
                        if (editcheck == 0) {
                            button += 'd-none';
                        }
                        button +=
                            '" data-toggle="tooltip" data-placement="bottom" title="View Order" id="' +
                            full['idtbl_grn'] + '"><i class="far fa-eye"></i></button>';

                        if(full['confirm_status']==1){button+='<button class="btn btn-outline-success btn-sm mr-1 ';if(statuscheck==0){button+='d-none';}button+='"><i class="fas fa-check"></i></button>';}
                        else{button+='<a href="process/statusgrn.php?record='+full['idtbl_grn']+'&type=1" data-toggle="tooltip" data-placement="bottom" title="Confirm GRN" onclick="return order_confirm()" target="_self" class="btn btn-outline-orange btn-sm mr-1 ';if(statuscheck==0){button+='d-none';}button+='"><i class="fas fa-times"></i></a>';}
                        
                        return button;
                    }
                }
            ]
        } );
        $('#btnordercreate').click(function () {
            $('#modalcreateorder').modal('show');
            $('#modalcreateorder').on('shown.bs.modal', function () {
                $('#orderdate').trigger('focus');
            });
        });
        $('#btnSaveGrn').click(function(){
            if (!$("#grnFrom")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#submitBtn").click();
            } else {
                jsonObj = [];
                $("#tableGrnList tbody tr").each(function() {
                    item = {}
                    $(this).find('td').each(function(col_idx) {
                        item["col_" + (col_idx + 1)] = $(this).text();
                    });
                    jsonObj.push(item);
                });
                // console.log(jsonObj);

                var grnnum = $('#grnnum').val();
                var batchno = $('#batchno').val();
                var ponumber = $('#ponumber').val();
                var grndate = $('#grndate').val();
                var grninvoice = $('#grninvoice').val();
                var grndispatch = $('#grndispatch').val();
                var grnnettotal = $('#txtShowPrice').val();
                var grnnettotalwithoutvat = $('#txtShowPricewithoutvat').val();
                var taxamount = $('#txtShowtaxAmount').val();

                $.ajax({
                    type: "POST",
                    data: {
                        tableData: jsonObj,
                        grnnum: grnnum,
                        batchno: batchno,
                        ponumber: ponumber,
                        grndate: grndate,
                        grninvoice: grninvoice,
                        grndispatch: grndispatch,
                        grnnettotal: grnnettotal,
                        grnnettotalwithoutvat: grnnettotalwithoutvat,
                        taxamount: taxamount

                    },
                    url: 'process/grnprocess.php',
                    success: function(result) { //alert(result);
                        action(result);
                        location.reload();
                    }
                });
            }
        });

        $('#dataTable tbody').on('click', '.btnView', function() {
            var grnid=$(this).attr('id');

            $('#modalgrndetail').modal('show');

            $.ajax({
                type: "POST",
                data: {
                    grnid:grnid
                },
                url: 'getprocess/getgrndetail.php',
                success: function(result) {//alert(result);
                    $('#viewgrndetail').html(result);
                }
            });
        });

    });

    function orderoption(){
        $('#tableGrnList tbody').on('click', '.editnewqty', function(e) {
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

            $('<input type="Text" class="form-control form-control-sm optionnewqty">').val(val).appendTo($this);
            textremove('.optionnewqty', row);
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

    function tabletotal() {
        var sum = 0;

        $(".total").each(function () {
            sum += parseFloat($(this).text());
        });

        var vatRateElement = document.getElementById('vatRate');
        if (vatRateElement) {
            var vatRate = parseFloat(vatRateElement.value);
            var vatAmount = sum * vatRate;
            var totalWithVAT = sum + vatAmount;

            var showSum = addCommas(parseFloat(sum).toFixed(2));
            var showVatAmount = addCommas(parseFloat(vatAmount).toFixed(2));
            var showTotalWithVAT = addCommas(parseFloat(totalWithVAT).toFixed(2));

            $('#showPricewithoutvat').html('Rs. ' + showSum);
            $('#txtShowPricewithoutvat').val(sum);

            $('#showtaxAmount').html('Rs. ' + showVatAmount);
            $('#txtShowtaxAmount').val(vatAmount);

            $('#showPrice').html('Rs. ' + showTotalWithVAT);
            $('#txtShowPrice').val(totalWithVAT);
        } else {
            console.error('VAT rate element not found');
        }
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
    //Text remove
    function textremove(classname, row) {
        $('#tableGrnList tbody').on('keyup', classname, function(e) {
            if (e.keyCode === 13) { 
                $this = $(this);
                var val = $this.val();
                var td = $this.closest('td');
                td.empty().html(val).data('editing', false);
                
                var rowID = row.closest("td").parent()[0].rowIndex;
                var unitprice = parseFloat(row.closest("tr").find('td:eq(2)').text());

                var newqty = parseFloat(row.closest("tr").find('td:eq(6)').text());

                var totnew = newqty*unitprice;

                var total = parseFloat(totnew).toFixed(2);
                var showtotal = addCommas(total);

                $('#tableGrnList').find('tr').eq(rowID).find('td:eq(7)').text(total);
                $('#tableGrnList').find('tr').eq(rowID).find('td:eq(8)').text(showtotal);

                tabletotal();
            }
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

    function order_confirm() {
        return confirm("Are you sure you want to Confirm this GRN?");
    }
</script>
<?php include "include/footer.php"; ?>
