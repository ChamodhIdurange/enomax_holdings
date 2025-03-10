<?php 
include "include/header.php";  

$sqlcompany="SELECT `idtbl_company`, `name`, `code` FROM `tbl_company` WHERE `status`=1";
$resultcompany =$conn-> query($sqlcompany); 

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
                            <div class="page-header-icon"><i data-feather="server"></i></div>
                            <span>Payment To Account</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Order No</th>
                                            <th>Order Total</th>
                                            <th>Preious Bill Total</th>
                                            <th>Balance Total</th>
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
<!-- Modal View -->
<div class="modal fade" id="modalaccountdetail" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header p-1">
                <h5 class="modal-title" id="headertitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="searchform">
                    <div class="form-row">
                        <div class="col">
                            <label class="small font-weight-bold text-dark">Company</label>
                            <select class="form-control form-control-sm rounded-0" name="company" id="company" required>
                                <option value="">Select</option>
                                <?php if($resultcompany->num_rows > 0) {while ($rowcompany = $resultcompany-> fetch_assoc()) { ?>
                                <option value="<?php echo $rowcompany['idtbl_company'] ?>"><?php echo $rowcompany['name'] ?></option>
                                <?php }} ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <label class="small font-weight-bold text-dark">Branch</label>
                            <select class="form-control form-control-sm rounded-0" name="companybranch" id="companybranch" required>
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <label class="small font-weight-bold text-dark">Credit Account</label>
                            <select class="form-control form-control-sm rounded-0" name="creditaccount" id="creditaccount" required>
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <label class="small font-weight-bold text-dark">Debit Account</label>
                            <select class="form-control form-control-sm rounded-0" name="debitaccount" id="debitaccount" required>
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <label class="small font-weight-bold text-dark">Narration</label>
                            <textarea class="form-control form-control-sm rounded-0" name="narration" id="narration"></textarea>
                        </div>
                    </div>
                    <div class="form-row mt-3">
                        <div class="col">
                            <button class="btn btn-outline-dark btn-sm rounded-0 fa-pull-right px-4" type="button" id="formSearchBtn"><i class="fas fa-plus"></i>&nbsp;Add To Account</button>
                        </div>
                    </div>
                    <input type="submit" class="d-none" id="hidesubmit">
                    <input type="hidden" name="hideorderpaymentid" id="hideorderpaymentid" value="">
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal View -->
<div class="modal fade" id="modalviewprevious" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header p-1">
                <h5 class="modal-title" id="headertitle">Previous Bill Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="viewhtmlprevoius"></div>
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
                url: "scripts/porderpaymentlist.php",
                type: "POST", // you can use GET
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_porder_payment"
                },
                {
                    "data": "date"
                },
                {
                    "targets": -1,
                    "className": '',
                    "data": null,
                    "render": function(data, type, full) {
                        return 'PO000'+full['tbl_porder_idtbl_porder'];     
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var payment=addCommas(parseFloat(full['ordertotal']).toFixed(2));
                        return payment;
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var payment=addCommas(parseFloat(full['previousbill']).toFixed(2));
                        return payment;
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var payment=addCommas(parseFloat(full['balancetotal']).toFixed(2));
                        return payment;
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';

                        button+='<button class="btn btn-outline-dark btn-sm mr-1 viewpreviousbtn" data-toggle="tooltip" data-placement="bottom" title="View Previous Bill" id="'+full['idtbl_porder_payment']+'"><i class="far fa-list-alt"></i></button><button class="btn btn-outline-primary btn-sm mr-1 btnaddtoaccount" data-toggle="tooltip" data-placement="bottom" title="Add To PaymentAccount" id="'+full['idtbl_porder_payment']+'"><i class="fas fa-calculator"></i></button><a href="process/statuspaymenttoaccount.php?record='+full['idtbl_porder_payment']+'&type=2" onclick="return delete_confirm()" target="_self" data-toggle="tooltip" data-placement="bottom" title="Cancel payment" class="btn btn-outline-danger btn-sm ';if(deletecheck==0){button+='d-none';}button+='"><i class="far fa-times-circle"></i></a>';
                        return button;
                    }
                }
            ],
            "createdRow": function( row, data, dataIndex){
                if ( data['status']  == 2) {
                    $(row).addClass('table-danger');
                }
                else if ( data['accountstatus']  == 1) {
                    $(row).addClass('table-success');
                }
            },
        } );
        $('#dataTable tbody').on('click', '.btnaddtoaccount', function() {
            var id = $(this).attr('id');
            $('#hideorderpaymentid').val(id);
            $('#modalaccountdetail').modal('show');
        });
        $('#dataTable tbody').on('click', '.viewpreviousbtn', function() {
            var id = $(this).attr('id');
            $.ajax({
                type: "POST",
                data: {
                    recordID: id
                },
                url: 'getprocess/getpreviousbillaccoporderpayment.php',
                success: function(result) {//alert(result);
                    $('#viewhtmlprevoius').html(result);
                    $('#modalviewprevious').modal('show');
                }
            });
        });

        $('#company').change(function(){
            var company = $(this).val();
            var typeID = '2';
            var companybranch = $('#companybranch').val();

            $.ajax({
                type: "POST",
                data: {
                    company: company
                },
                url: 'getprocess/getcompanybranchaccocompany.php',
                success: function(result) { //alert(result);
                    var objfirst = JSON.parse(result);

                    var html = '';
                    html += '<option value="">Select</option>';
                    $.each(objfirst, function(i, item) {
                        //alert(objfirst[i].id);
                        html += '<option value="' + objfirst[i].id + '">';
                        html += objfirst[i].branch;
                        html += '</option>';
                    });

                    $('#companybranch').empty().append(html);
                }
            });

            accountlist(typeID, company, companybranch);
        });
        $('#companybranch').change(function(){
            var company = $('#company').val();
            var typeID = '2';
            var companybranch = $(this).val();

            accountlist(typeID, company, companybranch);
        });

        $('#formSearchBtn').click(function(){
            if (!$("#searchform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmit").click();
            } else {
                var company = $('#company').val();
                var companybranch = $('#companybranch').val();
                var creditaccount = $('#creditaccount').val();
                var debitaccount = $('#debitaccount').val();
                var narration = $('#narration').val();
                var porderpaymentID = $('#hideorderpaymentid').val();

                $.ajax({
                    type: "POST",
                    data: {
                        company: company,
                        companybranch: companybranch,
                        creditaccount: creditaccount,
                        debitaccount: debitaccount,
                        narration: narration,
                        porderpaymentID: porderpaymentID
                    },
                    url: 'process/paymenttoaccountprocess.php',
                    success: function(result) {//alert(result);
                        action(result);
                        $('#modalaccountdetail').modal('hide');
                        location.reload();
                    }
                });
            }
        });
    });
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

    function accountlist(typeID, company, companybranch){
        if(typeID!='' && company!='' && companybranch!=''){
            $.ajax({
                type: "POST",
                data: {
                    typeID: typeID,
                    company: company,
                    companybranch: companybranch
                },
                url: 'getprocess/getaccountlistaccoreceipttype.php',
                success: function(result) {//alert(result);
                    var objfirst = JSON.parse(result);

                    var html = '';
                    html += '<option value="">Select</option>';
                    $.each(objfirst, function(i, item) {
                        //alert(objfirst[i].id);
                        html += '<option value="' + objfirst[i].subaccount + '">';
                        html += objfirst[i].subaccount;
                        html += '</option>';
                    });

                    $('#creditaccount').empty().append(html);
                    $('#debitaccount').empty().append(html);
                }
            });
        }
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

    function delete_confirm() {
        return confirm("Are you sure you want to cancel this payment?");
    }
</script>
<?php include "include/footer.php"; ?>
