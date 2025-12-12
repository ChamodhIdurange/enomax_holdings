<?php
include "include/header.php";
include "include/topnavbar.php";

$firstDay = date('Y-m-01');
$lastDay = date('Y-m-t');
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
                            <div class="page-header-icon"><i data-feather="file-text"></i></div>
                            <span>Damage Product Report</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-2">
                        <form id="searchform" class="mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>From Date</label>
                                    <input type="text" class="form-control dpd1a" id="fromdate" name="fromdate"
                                        value="<?php echo $firstDay; ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label>To Date</label>
                                    <input type="text" class="form-control dpd1a" id="todate" name="todate"
                                        value="<?php echo $lastDay; ?>" required>
                                </div>
                                <div class="col-md-2">
                                    <label>&nbsp;</label>

                                    <button class="btn btn-outline-dark rounded-0 px-4 btn-block" type="button"
                                        id="formSearchBtn"><i class="fas fa-search"></i>&nbsp;Search</button>
                                    <button type="submit" id="hidesubmit" style="display:none;"></button>
                                </div>
                            </div>
                        </form>

                        <hr class="border-dark">

                        <div id="targetviewdetail">
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-search fa-3x mb-3"></i>
                                <p>Select date range and click "Load Report" to view damage products</p>
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
        $('.dpd1a').datepicker('remove');
        $('.dpd1a').datepicker({
            uiLibrary: 'bootstrap4',
            autoclose: true,
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        });

        $('#formSearchBtn').click(function() {
            if (!$("#searchform")[0].checkValidity()) {
                $("#hidesubmit").click();
                return;
            }

            var fromdate = $('#fromdate').val();
            var todate = $('#todate').val();

            $('#targetviewdetail').html(
                '<div class="card border-0 shadow-none bg-transparent"><div class="card-body text-center"><img src="images/spinner.gif" alt="Loading..."><p class="mt-2">Loading damage products...</p></div></div>'
            ).show();

            $.ajax({
                type: "POST",
                data: {
                    fromdate: fromdate,
                    todate: todate
                },
                url: 'getprocess/getdamageproducts.php',
                success: function(result) {
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
                                title: 'Damage Product Report',
                                text: '<i class="fas fa-file-csv mr-2"></i> CSV',
                                footer: true
                            },
                            {
                                extend: 'pdf',
                                className: 'btn btn-danger btn-sm',
                                title: 'Damage Product Report',
                                text: '<i class="fas fa-file-pdf mr-2"></i> PDF',
                                footer: true,
                                orientation: 'landscape',
                                pageSize: 'A4'
                            },
                            {
                                extend: 'print',
                                className: 'btn btn-primary btn-sm',
                                title: 'Damage Product Report',
                                text: '<i class="fas fa-print mr-2"></i> Print',
                                footer: true
                            }
                        ],
                        "paging": true,
                        "searching": true,
                        "ordering": true,
                        "lengthMenu": [
                            [10, 25, 50, 100, -1],
                            [10, 25, 50, 100, 'All']
                        ],
                        "order": [
                            [1, 'desc']
                        ],
                        "footerCallback": function(row, data, start, end, display) {
                            var api = this.api();

                            var totalQty = api.column(8, {
                                page: 'current'
                            }).data().reduce(function(a, b) {
                                var val = typeof b === 'string' ? b.replace(/,/g, '') : b;
                                return parseFloat(a) + parseFloat(val);
                            }, 0);

                            var totalAmount = api.column(10, {
                                page: 'current'
                            }).data().reduce(function(a, b) {
                                var val = typeof b === 'string' ? b.replace(/<[^>]*>/g, '').replace(/,/g, '') : b;
                                return parseFloat(a) + parseFloat(val);
                            }, 0);

                            $(api.column(8).footer()).html('<strong>' + totalQty.toLocaleString('en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }) + '</strong>');
                            $(api.column(10).footer()).html('<strong>' + totalAmount.toLocaleString('en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }) + '</strong>');
                        }
                    });
                },
                error: function(xhr, status, error) {
                    $('#targetviewdetail').html(
                        '<div class="alert alert-danger">Error loading data: ' + error + '</div>'
                    );
                }
            });
        });
    });
</script>
<?php include "include/footer.php"; ?>