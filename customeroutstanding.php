<?php 
include "include/header.php";  
include "connection/db.php";  

$sqlcustomer = "SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE `status`=1 ORDER BY `name` ASC";
$resultcustomer = $conn->query($sqlcustomer);

$sqlrep = "SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `status`=1 ORDER BY `name` ASC";
$resultrep = $conn->query($sqlrep);

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
                            <span>Outstanding Report</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12">
                                <form id="outstandingForm">
                                    <div class="form-row">
                                        <div class="col-2">
                                            <label class="small font-weight-bold text-dark">Search Type*</label>
                                            <div class="input-group input-group-sm">
                                                <select class="form-control form-control-sm" name="searchType"
                                                    id="searchType">
                                                    <option value="0">Select Type</option>
                                                    <option value="1">All</option>
                                                    <option value="2">Rep Vise</option>
                                                    <option value="3">Customer Vise</option>
                                                    
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-2 search-dependent" style="display: none" id="selectSaleRepDiv">
                                            <label class="small font-weight-bold text-dark">Rep*</label>
                                            <select class="form-control form-control-sm" style="width: 100%;"
                                                name="selectSaleRep" id="selectSaleRep">
                                                <option value="0">All</option>
                                                <?php while ($rowresultrep = $resultrep->fetch_assoc()) { ?>
                                                <option value="<?php echo $rowresultrep['idtbl_employee']; ?>">
                                                    <?php echo $rowresultrep['name']; ?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-2 search-dependent" style="display: none"
                                            id="selectCustomerDiv">
                                            <label class="small font-weight-bold text-dark">Customer*</label>
                                            <select class="form-control form-control-sm" style="width: 100%;"
                                                name="selectCustomer" id="selectCustomer">
                                                <option value="0">All</option>
                                                <?php while ($rowcustomerlist = $resultcustomer->fetch_assoc()) { ?>
                                                <option value="<?php echo $rowcustomerlist['idtbl_customer']; ?>">
                                                    <?php echo $rowcustomerlist['name']; ?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-2 search-dependent" style="display: none" id="selectDateFrom">
                                            <label class="small font-weight-bold text-dark">From*</label>
                                            <input type="date" class="form-control form-control-sm" name="fromdate"
                                                id="fromdate" >
                                        </div>
                                        <div class="col-2 search-dependent" style="display: none" id="selectDateTo">
                                            <label class="small font-weight-bold text-dark">To*</label>
                                            <input type="date" class="form-control form-control-sm" name="todate"
                                                id="todate" >
                                        </div>
                                        <div class="col-2"style="display: none" id="aginreportshow">
                                            <label class="small font-weight-bold text-dark">Agin Report*</label>
                                            <div class="input-group input-group-sm">
                                                <select class="form-control form-control-sm" name="aginvalue"
                                                    id="aginvalue">
                                                    <option value="0">ALL</option>
                                                    <option value="1">0-15 Days</option>
                                                    <option value="2">15-30 Days</option>
                                                    <option value="3">30-45 Days</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-1 search-dependent" style="display: none;" id="hidesumbit">
                                            &nbsp;<br>
                                            <button type="submit"
                                                class="btn btn-outline-danger btn-sm ml-auto w-25 mt-2 px-5 btnPdf"
                                                id="submitBtn">
                                                <i class="fas fa-file-pdf"></i>&nbsp;View
                                            </button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="recordID" id="recordID" value="">
                                </form>
                            </div>
                        </div>
                        <hr>
                        <div class="col-12">
                            <hr class="border-dark">
                            <div id="targetviewdetail" style="display: none;">
                                <table id="outstandingReportTable" class="display table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Customer</th>
                                            <th>Rep</th>
                                            <th>Date</th>
                                            <th>Invoice</th>
                                            <th>Invoice Total</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4" class="text-right"><strong>Total</strong></td>
                                            <td class="text-center"><strong id="totalAmount"></strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <br>
                        <!-- <div class="col-12" style="display: none" align="right" id="hideprintBtn">
                            <button type="button"
                                class="btn btn-outline-danger btn-sm ml-auto w-10 mt-2 px-5 align-right printBtn"
                                id="printBtn">
                                <i class="fas fa-file-pdf"></i>&nbsp;Print
                            </button>
                        </div> -->
                        <div class="col-12" id="showpdfview" style="display: none;">
                            <div class="embed-responsive embed-responsive-1by1" id="pdfframe">
                                <iframe class="embed-responsive-item" frameborder="0"></iframe>
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
<div class="modal fade" id="printreport" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">View Outstanding PDF</h5>
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
$(document).ready(function() {
    $('#searchType').change(function() {
        var searchType = $(this).val();
        resetFields();
        if (searchType == 1) {
            $('#selectDateFrom, #selectDateTo, #hidesumbit, #aginreportshow').show();
        } else if (searchType == 2) {
            $('#selectSaleRepDiv, #selectDateFrom, #selectDateTo, #hidesumbit, #aginreportshow').show();
        } else if (searchType == 3) {
            $('#selectCustomerDiv, #selectDateFrom, #selectDateTo, #hidesumbit, #aginreportshow').show();
        } 
    });

    $('#outstandingForm').submit(function(event) {
        event.preventDefault();

        var searchType = $('#searchType').val();
        var validfrom = $('#fromdate').val();
        var validto = $('#todate').val();
        var customer = $('#selectCustomer').val();
        var rep = $('#selectSaleRep').val();
        var aginvalue = $('#aginvalue').val();

        $.ajax({
            type: "POST",
            data: {
                searchType: searchType,
                validfrom: validfrom,
                validto: validto,
                customer: customer,
                rep: rep,
                aginvalue: aginvalue
            },
            url: 'getprocess/getoutstandingreport.php',
            success: function(result) {
                $('#targetviewdetail').html(result).show(); // Show the table
                $('#hideprintBtn').show();

                if ($.fn.DataTable.isDataTable('#outstandingReportTable')) {
                    $('#outstandingReportTable').DataTable().destroy();
                }

                $('#outstandingReportTable').DataTable({
                    "dom": "<'row'<'col-sm-5'B><'col-sm-2'l><'col-sm-5'f>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                    "buttons": [{
                            extend: 'csv',
                            className: 'btn btn-success btn-sm',
                            title: 'Everest Outstanding Report Information',
                            text: '<i class="fas fa-file-csv mr-2"></i> CSV'
                        },
                        {
                            extend: 'pdf',
                            className: 'btn btn-danger btn-sm',
                            title: 'Everest Outstanding Report Information',
                            text: '<i class="fas fa-file-pdf mr-2"></i> PDF'
                        },
                        {
                            extend: 'print',
                            title: 'Everest Outstanding Report Information',
                            className: 'btn btn-primary btn-sm',
                            text: '<i class="fas fa-print mr-2"></i> Print'
                        }
                    ],
                    "paging": true,
                    "searching": true,
                    "ordering": true,
                });

                $('#totalAmount').text($('#totalAmount').text());
                //resetForm();
            }
        });
    });

    $('#printBtn').click(function() {
        var searchType = $('#searchType').val();
        var validfrom = $('#fromdate').val();
        var validto = $('#todate').val();
        var customer = getElementValue('#selectCustomer');
        var rep = getElementValue('#selectSaleRep');
        var aginvalue = getElementValue('#aginvalue');

        
        $('#frame').html('');
        $('#frame').html('<iframe class="embed-responsive-item" frameborder="0"></iframe>');
        $('#printreport iframe').contents().find('body').html(
            "<img src='images/spinner.gif' class='img-fluid' style='margin-top:200px;margin-left:500px;' />"
        );

        
        var params = `?validfrom=${encodeURIComponent(validfrom)}&validto=${encodeURIComponent(validto)}&searchType=${encodeURIComponent(searchType)}&customer=${encodeURIComponent(customer)}&rep=${encodeURIComponent(rep)}&aginvalue=${encodeURIComponent(aginvalue)}`;
        var src = 'pdfprocess/outstandingreportpdf.php' + params;

        var width = $(this).attr('data-width') || 640;
        var height = $(this).attr('data-height') || 360;

        $("#printreport iframe").attr({
            'src': src,  
            'height': height,
            'width': width,
            'allowfullscreen': true
        });

        $('#printreport').modal({
            keyboard: false,
            backdrop: 'static'
        });
        resetForm();

    });


    function getElementValue(id) {
        var element = $(id);
        
    }

    function resetFields() {
        $('.search-dependent').hide();
    }

    function resetForm() {
        $('#outstandingForm')[0].reset();
        resetFields();
        $('#searchType').val(0);
    }
});
</script>