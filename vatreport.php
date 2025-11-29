<?php
include "include/header.php";
include "include/topnavbar.php";


$customers = "SELECT `idtbl_customer`, `customer` FROM `tbl_customer` WHERE `vat_num` <> '' AND `vat_num` IS NOT NULL AND `status` = 1";
$customerlist = $conn->query($customers);

$taxQuery = "SELECT `vat_rate` FROM `tbl_vat` LIMIT 1";
$taxResult = $conn->query($taxQuery);
$tax = 0;

if ($taxResult && $taxResult->num_rows > 0) {
    $row = $taxResult->fetch_assoc();
    $tax = $row['vat_rate'];
}

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
                            <div class="page-header-icon"><i data-feather="file"></i></div>
                            <span>VAT Report</span>
                        </h1>
                        <!-- <button type="button" class="btn btn-sm btn-primary mt-3" data-toggle="modal" data-target="#addvat">
                            Add VAT Percentage
                        </button> -->

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
                                                <option value="all" selected>All</option>
                                                <?php if ($customerlist->num_rows > 0) {
                                                    while ($row = $customerlist->fetch_assoc()) { ?>
                                                        <option value="<?php echo $row['idtbl_customer'] ?>">
                                                            <?php echo $row['customer'] ?></option>
                                                <?php }
                                                } ?>
                                            </select>
                                        </div>

                                        <div class="col-1">
                                        </div>
                                        <div class="col-3">
                                            <label class="small font-weight-bold text-dark">Start Date*</label>
                                            <div class="input-group input-group-sm mb-3">
                                                <input type="text" class="form-control dpd1a rounded-0" id="fromdate"
                                                    name="fromdate" value="2025/02/01" required>
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
                                                <th class="text-center">Invoice No</th>
                                                <th class="text-center">Customer No</th>
                                                <th class="text-center">VAT Number</th>
                                                <th class="text-center">Invoice Value</th>
                                                <th class="text-center">VAT Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <br>
                            <div class="col-12" style="display: none" align="right" id="hideprintBtn">
                                <button type="button"
                                    class="btn btn-outline-danger btn-sm ml-auto w-10 mt-2 px-5 align-right printBtnStock"
                                    id="printBtnStock">
                                    <i class="fas fa-file-pdf"></i>&nbsp;Print
                                </button>
                            </div>
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

<!-- Add VAT Percentage Modal -->
<div class="modal fade" id="addvat" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="addvatLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h5 class="modal-title" id="addvatLabel"><b>Add VAT Percentage</b></h5>
            </div>

            <div class="modal-body">
                <form id="vatForm">
                    <div class="mb-3">
                        <label class="form-label fw-bold">VAT Percentage (%)</label>
                        <input type="number" min="0" step="0.01" class="form-control"
                            name="vat_percentage" id="vat_percentage"
                            value="<?php echo htmlspecialchars($tax); ?>" required>

                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary btn-sm">Save</button>
                        <button type="button" class="btn btn-outline-dark btn-sm" data-dismiss="modal" aria-label="Close">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function() {
        $("#customerlist").select2();

        $('.dpd1a').datepicker('remove');
        $('.dpd1a').datepicker({
            uiLibrary: 'bootstrap4',
            autoclose: 'true',
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        });

        $('#vatForm').on('submit', function(e) {
            e.preventDefault();

            var vat_percentage = $('#vat_percentage').val();

            if (vat_percentage === '' || vat_percentage < 0) {
                alert('Please enter a valid VAT percentage.');
                return;
            }

            $.ajax({
                url: 'process/vatprocess.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    vat_percentage: vat_percentage
                },
                success: function(response) {
                    if (response.status === 'success') {
                        $('#addvat').modal('hide');
                        alert(response.message);
                        window.location.reload();
                        $('#vat_percentage').val('');
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Something went wrong: ' + error);
                }
            });
        });

        $("#customerlist").on("change", function() {
            var selectedValues = $(this).val();

            if (selectedValues && selectedValues.includes("all")) {
                selectedValues = $("#customerlist option[value!='all']").map(function() {
                    return this.value;
                }).get();

                $(this).val(selectedValues).trigger("change");
            }
        });

        $('#formSearchBtn').click(function() {
            if (!$("#searchform")[0].checkValidity()) {
                $("#hidesubmit").click();
            } else {
                var fromdate = $('#fromdate').val();
                var todate = $('#todate').val();
                var customerlist = $('#customerlist').val();

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
                    url: 'getprocess/getvatinvoices.php',
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
                                    title: 'Customer wise VAT Report',
                                    text: '<i class="fas fa-file-csv mr-2"></i> CSV',
                                    footer: true
                                },
                                {
                                    extend: 'pdf',
                                    className: 'btn btn-danger btn-sm',
                                    title: 'Customer wise VAT Report',
                                    text: '<i class="fas fa-file-pdf mr-2"></i> PDF',
                                    footer: true
                                },
                                {
                                    extend: 'print',
                                    className: 'btn btn-primary btn-sm',
                                    title: 'Customer wise VAT Report',
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