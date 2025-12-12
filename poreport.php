<?php
include "include/header.php";
include "include/topnavbar.php";

// Remove the customer query - we'll load via AJAX instead
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
                            <span>Customer PO Report (Delivered Orders)</span>
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
                                            <label class="small font-weight-bold text-dark">Customer*</label>
                                            <select type="text" class="form-control form-control-sm" name="customerlist[]"
                                                id="customerlist" required multiple>
                                                <!-- Options loaded via AJAX -->
                                            </select>
                                        </div>

                                        <div class="col-1">
                                        </div>
                                        <div class="col-3">
                                            <label class="small font-weight-bold text-dark">Start Date*</label>
                                            <div class="input-group input-group-sm mb-3">
                                                <input type="text" class="form-control dpd1a rounded-0" id="fromdate"
                                                    name="fromdate" value="<?php echo $firstDay; ?>" required>
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
                                                    name="todate" value="<?php echo $lastDay; ?>" required>
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
                                                <th>#</th>
                                                <th class="text-center">PO Number</th>
                                                <th class="text-center">Customer</th>
                                                <th class="text-center">Order Date</th>
                                                <th class="text-right">Total Amount</th>
                                                <th class="text-right">Discount</th>
                                                <th class="text-right">VAT</th>
                                                <th class="text-right">Net Total</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
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
        // Initialize Select2 with AJAX
        $("#customerlist").select2({
            ajax: {
                url: 'getprocess/search_customers.php',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.results,
                        pagination: {
                            more: data.more
                        }
                    };
                },
                cache: true
            },
            placeholder: 'Search and select customers...',
            minimumInputLength: 0,
            allowClear: true,
            multiple: true
        });

        $('.dpd1a').datepicker('remove');
        $('.dpd1a').datepicker({
            uiLibrary: 'bootstrap4',
            autoclose: 'true',
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        });

        $('#formSearchBtn').click(function() {
            if (!$("#searchform")[0].checkValidity()) {
                $("#hidesubmit").click();
            } else {
                var fromdate = $('#fromdate').val();
                var todate = $('#todate').val();
                var customerlist = $('#customerlist').val();

                if (!customerlist || customerlist.length === 0) {
                    alert('Please select at least one customer');
                    return;
                }

                $('#targetviewdetail').html(
                    '<div class="card border-0 shadow-none bg-transparent"><div class="card-body text-center"><img src="images/spinner.gif" alt=""></div></div>'
                ).show();

                $.ajax({
                    type: "POST",
                    data: {
                        fromdate: fromdate,
                        todate: todate,
                        customerlist: customerlist
                    },
                    url: 'getprocess/getpoorders.php',
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
                                    title: 'Customer PO Report - Delivered Orders',
                                    text: '<i class="fas fa-file-csv mr-2"></i> CSV',
                                    footer: true
                                },
                                {
                                    extend: 'pdf',
                                    className: 'btn btn-danger btn-sm',
                                    title: 'Customer PO Report - Delivered Orders',
                                    text: '<i class="fas fa-file-pdf mr-2"></i> PDF',
                                    footer: true
                                },
                                {
                                    extend: 'print',
                                    className: 'btn btn-primary btn-sm',
                                    title: 'Customer PO Report - Delivered Orders',
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
                    }
                });
            }
        });
    });
</script>
<?php include "include/footer.php"; ?>