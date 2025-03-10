<?php 
include "include/header.php";  

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
                            <span>Invoice Recovery</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col">
                                <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right" id="btnrecovercreate"><i class="fas fa-plus"></i>&nbsp;Create Recovery</button>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-bordered table-striped table-sm nowrap w-100" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th class="text-right">Total</th>
                                            <th class="text-right">Recovery Amount</th>
                                            <th class="text-right">Balance Amount</th>
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
<!-- Modal Create Recover -->
<div class="modal fade" id="modalcreaterecover" data-backdrop="static" data-keyboard="false" tabindex="-1"
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
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <form id="searchform">
                            <div class="form-row">
                                <div class="col-3">
                                    <label class="small font-weight-bold text-dark">Date*</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control dpd1a rounded-0" id="fromdate" name="fromdate" value="<?php echo date('Y-m-d') ?>" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text rounded-0" id="inputGroup-sizing-sm"><i data-feather="calendar"></i></span>
                                        </div>
                                        <input type="text" class="form-control dpd1a rounded-0 border-left-0" id="todate" name="todate" value="<?php echo date('Y-m-d') ?>" required>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <label class="small font-weight-bold text-dark">&nbsp;</label><br>
                                    <button class="btn btn-outline-dark btn-sm rounded-0 px-4" type="button" id="formSearchBtn"><i class="fas fa-search"></i>&nbsp;Search</button>
                                </div>
                            </div>
                            <input type="submit" class="d-none" id="hidesubmit">
                        </form>
                        <hr>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div id="recoverdetailview"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 text-right">
                        <hr>
                        <button class="btn btn-outline-dark btn-sm" id="createrecoverybtn" name="createrecoverybtn" disabled><i class="fas fa-save"></i>&nbsp;Create Recovery</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Create Recover -->
<div class="modal fade" id="modalinvoicelist" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header p-2">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="viewinvoicelist"></div>
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

        $('#dataTable').DataTable( {
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "scripts/recoverylist.php",
                type: "POST", // you can use GET
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_invoice_diff"
                },
                {
                    "data": "date"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        return parseFloat(full['total']).toFixed(2);     
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        return parseFloat(full['paymentrecovery']).toFixed(2);     
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        return parseFloat(full['recoveramount']).toFixed(2);     
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='<button class="btn btn-outline-dark btn-sm mr-1 btnView" data-toggle="tooltip" data-placement="bottom" title="View Invoice List" id="'+full['idtbl_invoice_diff']+'"><i class="far fa-eye"></i></button>';
                                                
                        return button;
                    }
                }
            ]
        } );

        $('.dpd1a').datepicker({
            uiLibrary: 'bootstrap4',
            autoclose: 'true',
            todayHighlight: true,
            endDate: 'today',
            format: 'yyyy-mm-dd'
        });

        $('#btnrecovercreate').click(function(){
            $('#modalcreaterecover').modal('show');
            $('#modalcreaterecover').on('shown.bs.modal', function () {
                $('#fromdate').trigger('focus');
            })
        });
        $('#formSearchBtn').click(function(){
            if (!$("#searchform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmit").click();
            } else {   
                var validfrom = $('#fromdate').val();
                var validto = $('#todate').val();

                $('#recoverdetailview').html('<div class="card border-0 shadow-none bg-transparent"><div class="card-body text-center"><img src="images/spinner.gif" alt="" srcset=""></div></div>');

                $.ajax({
                    type: "POST",
                    data: {
                        validfrom: validfrom,
                        validto: validto
                    },
                    url: 'getprocess/getinvoicerecoveraccoperiod.php',
                    success: function(result) {//alert(result);
                        $('#recoverdetailview').html(result);
                        $('#createrecoverybtn').prop('disabled', false);
                        invoiceoption();
                    }
                });
            }
        });

        $('#dataTable tbody').on('click', '.btnView', function() {
            var id = $(this).attr('id');
            $.ajax({
                type: "POST",
                data: {
                    recoverID: id
                },
                url: 'getprocess/getinvoicelistaccorecoverid.php',
                success: function(result) { //alert(result);
                    $('#viewinvoicelist').html(result);
                    $('#modalinvoicelist').modal('show');
                }
            });
        });
    });
    function invoiceoption(){
        $('#createrecoverybtn').click(function(){
            var totnew = $('#totalnewrecovery').val();
            var totrefill = $('#totalrefillrecovery').val();

            jsonObjInvoice = [];
            item = {}
            $(".invnumber").each(function(){
                item["invoiceID"] = $(this).text();
                jsonObjInvoice.push(item);
            });
            
            // console.log(jsonObjDispatch);

            $.ajax({
                type: "POST",
                data: {
                    invoicedata: jsonObjInvoice,
                    totnew: totnew,
                    totrefill: totrefill
                },
                url: 'process/invoicerecoveryprocess.php',
                success: function(result) {//alert(result);
                    action(result);
                    $('#modalcreaterecover').modal('hide');
                    $('#recoverdetailview').html('');
                    location.reload();
                }
            });
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
</script>
<?php include "include/footer.php"; ?>
