<?php
include "include/header.php";

$sql = "SELECT `tbl_invoice`.`idtbl_invoice`, `tbl_invoice`.`date`, `tbl_invoice`.`total`, `tbl_invoice`.`paymentcomplete`, `tbl_customer`.`name`, `tbl_employee`.`name` AS `saleref`, `tbl_area`.`area` FROM `tbl_invoice` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_invoice`.`tbl_customer_idtbl_customer` LEFT JOIN `tbl_employee` ON `tbl_employee`.`idtbl_employee`=`tbl_invoice`.`ref_id` LEFT JOIN `tbl_area` ON `tbl_area`.`idtbl_area`=`tbl_invoice`.`tbl_area_idtbl_area` WHERE `tbl_invoice`.`status`=1";
$result = $conn->query($sql);

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
                            <div class="page-header-icon"><i data-feather="file"></i></div>
                            <span>Invoice View</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card row">
                    <div class="col-md-12 mt-3">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="inputrow">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="scrollbar pb-3" id="style-2">
                                            <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                                <thead>
                                                    <tr>
                                                        <th>Invoice No</th>
                                                        <th>Date</th>
                                                        <th>Purchase Order</th>
                                                        <th>Customer</th>
                                                        <th>Area</th>
                                                        <th>Sale Rep</th>
                                                        <th class="text-right">Total</th>
                                                        <th>Payment</th>
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

                </div>
            </div>
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>
<!-- Modal Invoice Receipt -->
<div class="modal fade" id="modalinvoicereceipt" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <iframe class="embed-responsive-item w-100" height="600px" id="iframeModal" src=""></iframe>
            </div>
        </div>
    </div>
</div>


<!-- Modal Warning -->
<div class="modal fade" style="z-index: 2000; " id="warningModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body bg-danger text-white text-center">
                Can't cancel this invoice, because firstly cancel payment receipt. Thank you.
            </div>
            <div class="modal-footer bg-danger rounded-0">
                <button type="button" class="btn btn-outline-light btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<!-- Modal Cancel Reason -->
<div class="modal fade" id="modalcancelreason" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Quantity check</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="" action="process/statusinvoice.php" method="get" autocomplete="off">

                    <div class="col-md-12">
                        <div class="form-group mb-1">
                            <label class="small font-weight-bold text-dark">Cancel Reason*</label>
                            <textarea class="form-control form-control-sm" name="reason" id="reason" required></textarea>
                        </div>
                    </div>
                    <div>
                        <input type="hidden" id="record" name="record">
                        <input type="hidden" id="type" name="type" value="3">

                    </div>
                    <div class="form-group mt-3">
                        <button type="submit" id="qtySubmitBtn" class="btn btn-outline-primary btn-sm px-4 fa-pull-right" <?php if ($addcheck == 0) {
                                                                                                                                echo 'disabled';
                                                                                                                            } ?>><i class="far fa-save"></i>&nbsp;Submit</button>
                    </div>
                    <input type="hidden" name="hiddenstatus" id="hiddenstatus" value="">
                </form>
            </div>

        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="printreport" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">View Invoice PDF</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="embed-responsive embed-responsive-16by9" id="frame">
                            <iframe class="embed-responsive-item" frameborder="0"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Payment Receipt -->
<div class="modal fade" id="modalpayments" data-backdrop="static" data-keyboard="false" tabindex="-1"
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
                <div class="embed-responsive embed-responsive-16by9" id="frame">
                    <iframe class="embed-responsive-item" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function() {
        var addcheck = '<?php echo $addcheck; ?>';
        var editcheck = '<?php echo $editcheck; ?>';
        var statuscheck = '<?php echo $statuscheck; ?>';
        var deletecheck = '<?php echo $deletecheck; ?>';

        $('#dataTable').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "scripts/invoiceviewlist.php",
                type: "POST", // you can use GET
            },
            "order": [
                [0, "desc"]
            ],
            "columns": [
                {
                    "data": "invoiceno"
                },
                {
                    "data": "date"
                },
                {
                    "data": "cuspono"
                },
                {
                    "data": "name"
                },
                {
                    "data": "area"
                },
                {
                    "data": "salepep"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var payment = addCommas(parseFloat(full['nettotal']).toFixed(2));
                        return payment;
                    }
                },
                {
                    "targets": -1,
                    "className": '',
                    "data": null,
                    "render": function(data, type, full) {
                        if (full['paymentcomplete'] == 1) {
                            return 'Complete';
                        } else {
                            return 'Pending';
                        }
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button = '';
                        button += '<button class="btn btn-outline-dark btn-sm btnView mr-1" id="' + full['idtbl_invoice'] + '"><i class="fas fa-eye"></i></button> ';
                        if (full['paymentcomplete'] == 0 && deletecheck != 0) {
                            button+='<button type="button" data-url="process/invoicecancellprocess.php?record='+full['idtbl_invoice']+'&type=3"  data-actiontype="3" title="Complete Order" class="btn btn-outline-danger btn-sm mr-1 btntableaction" id="'+full['idtbl_invoice']+'"><i class="far fa-trash-alt"></i></button>';
                        }
                        
                        button+='<button type="button" data-actiontype="3" title="View Payment History" class="btn btn-outline-secondary btn-sm mr-1 btnViewPayments" id="'+full['idtbl_invoice']+'"><i class="fa fa-credit-card"></i></button>';
                        return button;
                    }
                }
            ]
        });

        $('#dataTable tbody').on('click', '.btnViewPayments', function() {
            var id = $(this).attr('id');

            $('#frame').html('');
            $('#frame').html('<iframe class="embed-responsive-item" frameborder="0"></iframe>');
            $('#modalpayments iframe').contents().find('body').html("<img src='images/spinner.gif' class='img-fluid' style='margin-top:200px;margin-left:500px;' />");

            var src = 'pdfprocess/paymentpdf.php?invoiceId=' + id;

            var width = $(this).attr('data-width') || 640; // larghezza dell'iframe se non impostato usa 640
            var height = $(this).attr('data-height') || 360; // altezza dell'iframe se non impostato usa 360

            var allowfullscreen = $(this).attr('data-video-fullscreen'); // impostiamo sul bottone l'attributo allowfullscreen se è un video per permettere di passare alla modalità tutto schermo

            // stampiamo i nostri dati nell'iframe
            $("#modalpayments iframe").attr({
                'src': src,
                'height': height,
                'width': width,
                'allowfullscreen': ''
            });
            $('#modalpayments').modal({
                keyboard: false,
                backdrop: 'static'
            });
        });

        $('#dataTable tbody').on('click', '.btnView', function() {
            var id = $(this).attr('id');
           // alert(id);
            $('#frame').html('');
            $('#frame').html('<iframe class="embed-responsive-item" frameborder="0"></iframe>');
            $('#printreport iframe').contents().find('body').html("<img src='images/spinner.gif' class='img-fluid' style='margin-top:200px;margin-left:500px;' />");

            var src = 'pdfprocess/invoicepdf.php?id=' + id;
            //            alert(src);
            var width = $(this).attr('data-width') || 640; // larghezza dell'iframe se non impostato usa 640
            var height = $(this).attr('data-height') || 360; // altezza dell'iframe se non impostato usa 360

            var allowfullscreen = $(this).attr('data-video-fullscreen'); // impostiamo sul bottone l'attributo allowfullscreen se è un video per permettere di passare alla modalità tutto schermo

            // stampiamo i nostri dati nell'iframe
            $("#printreport iframe").attr({
                'src': src,
                'height': height,
                'width': width,
                'allowfullscreen': ''
            });
            $('#printreport').modal({
                keyboard: false,
                backdrop: 'static'
            });
        });
    });

    function delete_confirm() {
        return confirm("Are you sure you want to remove this?");
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