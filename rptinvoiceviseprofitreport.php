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
                            <span>Profit Analysis Report (Invoice)</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12">
                                <form id="searchform">
                                    <div class="form-row">
                                        <div class="col-3">
                                            <label class="small font-weight-bold text-dark">Start Date*</label>
                                            <div class="input-group input-group-sm mb-3">
                                                <input type="text" class="form-control dpd1a rounded-0" id="fromdate"
                                                    name="fromdate" value="2025-02-01"  required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text rounded-0"
                                                        id="inputGroup-sizing-sm"><i data-feather="calendar"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <label class="small font-weight-bold text-dark">End Date*</label>
                                            <div class="input-group input-group-sm mb-3">
                                                <input type="text" class="form-control dpd1a rounded-0" id="todate"
                                                    name="todate" value="<?php echo date('Y-m-d') ?>" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text rounded-0"
                                                        id="inputGroup-sizing-sm"><i data-feather="calendar"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">&nbsp;</label><br>
                                            <button class="btn btn-outline-dark btn-sm rounded-0 px-4" type="button"
                                                id="formSearchBtn"><i class="fas fa-search"></i>&nbsp;Search</button>
                                        </div>
                                    </div>
                                    <input type="submit" class="d-none" id="hidesubmit">
                                </form>
                            </div>
                            <div class="col-12">
                                <hr class="border-dark">
                                <div id="targetviewdetail" style="display: none;">
                                    <table id="dataTable" class="display table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Date</th>
                                            <th class="text-center">Po No</th>
                                            <th class="text-center">Net Total</th>
                                            <th class="text-center">Profit</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <br>
                            <div class="col-12" id="showpdfview" style="display: none;">
                                <div class="embed-responsive embed-responsive-1by1" id="pdfframe">
                                    <iframe class="embed-responsive-item" frameborder="0"></iframe>
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
<!-- Modal -->
<div class="modal fade" id="printreportstock" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">View Stock PDF</h5>
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
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function () {
        $('.dpd1a').datepicker('remove');
        $('.dpd1a').datepicker({
            uiLibrary: 'bootstrap4',
            autoclose: 'true',
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        });

  
        $('#formSearchBtn').click(function () {
            if (!$("#searchform")[0].checkValidity()) {
                $("#hidesubmit").click();
            } else {
                var fromdate = $('#fromdate').val();
                var todate = $('#todate').val();

                $('#targetviewdetail').html(
                    '<div class="card border-0 shadow-none bg-transparent"><div class="card-body text-center"><img src="images/spinner.gif" alt="" srcset=""></div></div>'
                ).show();

                $.ajax({
                    type: "POST",
                    data: {
                        fromdate: fromdate,
                        todate: todate
                    },
                    url: 'getprocess/getinvoiceviseprofitdata.php',
                    success: function (result) {
                        $('#targetviewdetail').html(result);
                        if ($.fn.DataTable.isDataTable('#dataTable')) {
                            $('#dataTable').DataTable().destroy();
                        }
                        $('#dataTable').DataTable({
                            "dom": "<'row'<'col-sm-5'B><'col-sm-2'l><'col-sm-5'f>>" +
                                "<'row'<'col-sm-12'tr>>" +
                                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                            "buttons": [{
                                    extend: 'csv',
                                    className: 'btn btn-success btn-sm',
                                    title: 'Rep vise Report',
                                    text: '<i class="fas fa-file-csv mr-2"></i> CSV',
                                    footer: true
                                },
                                {
                                    extend: 'pdf',
                                    className: 'btn btn-danger btn-sm',
                                    title: 'Rep vise Report',
                                    text: '<i class="fas fa-file-pdf mr-2"></i> PDF',
                                    footer: true
                                },
                                {
                                    extend: 'print',
                                    className: 'btn btn-primary btn-sm',
                                    title: 'Rep vise Report',
                                    text: '<i class="fas fa-print mr-2"></i> Print',
                                    footer: true
                                }
                            ],
                            "paging": true,
                            "searching": true,
                            "ordering": true,
                            "lengthMenu": [
                                [10, 25, 50, -1],
                                [10, 25, 50, 'All']
                            ],
                        });
                        // $('#hideprintBtn').show();
                    }
                });
            }
        });

        $('#printBtnStock').click(function () {

            var fromdate = encodeURIComponent($('#fromdate').val());

            $('#frame').html('');
            $('#frame').html('<iframe class="embed-responsive-item" frameborder="0"></iframe>');
            $('#printreportstock iframe').contents().find('body').html(
                "<img src='images/spinner.gif' class='img-fluid' style='margin-top:200px;margin-left:500px;' />"
            );

            var src = 'pdfprocess/stockreportpdf.php?fromdate=' + fromdate;

            var width = $(this).attr('data-width') || 640;
            var height = $(this).attr('data-height') || 360;

            $("#printreportstock iframe").attr({
                'src': src,
                'height': height,
                'width': width,
                'allowfullscreen': ''
            });

            $('#printreportstock').modal({
                keyboard: false,
                backdrop: 'static'
            });
        });

    });
</script>
<?php include "include/footer.php"; ?>


