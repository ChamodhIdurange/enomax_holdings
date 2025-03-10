<?php 
include "include/header.php";  
include "include/topnavbar.php";

$sqlcustomer="SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE `status`=1 ORDER BY `name` ASC";
$resultcustomer =$conn-> query($sqlcustomer);

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
                            <span>Customer Overpayment Report</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12">
                                <form id="saleInformationForm">
                                    <div class="form-row">
                                        <div class="col-2 search-dependent" id="selectCustomerDiv">
                                            <label class="small font-weight-bold text-dark">Customer*</label>
                                            <select class="form-control form-control-sm" style="width: 100%;"
                                                name="selectCustomer" id="selectCustomer">
                                                <option value="0">All</option>
                                              
                                            </select>
                                        </div>
                                        <div class="col-2 search-dependent d-none" id="selectDateFrom">
                                            <label class="small font-weight-bold text-dark">From*</label>
                                            <input type="date" class="form-control form-control-sm" name="fromdate"
                                                id="fromdate" >
                                        </div>
                                        <div class="col-2 search-dependent d-none" id="selectDateTo">
                                            <label class="small font-weight-bold text-dark">To*</label>
                                            <input type="date" class="form-control form-control-sm" name="todate"
                                                id="todate" >
                                        </div>
                                        <div class="col-1 search-dependent" id="hidesumbit">
                                            &nbsp;<br>
                                            <button type="submit"
                                                class="btn btn-outline-primary btn-sm ml-auto w-25 mt-2 px-5 btnPdf"
                                                id="submitBtn">
                                                </i>&nbsp;View
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
                            <div id="targetviewdetail">
                                <table id="salesReportTable" class="display">
                                </table>
                            </div>
                        </div>
                        <br>
                        <div class="col-12" style="display: none" align="right" id="hideprintBtn">
                            <button type="button"
                                class="btn btn-outline-danger btn-sm ml-auto w-10 mt-2 px-5 align-right printBtn"
                                id="printBtn">
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
                <h5 class="modal-title" id="exampleModalCenterTitle">View Sale Report PDF</h5>
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

        $("#selectCustomer").select2({
            ajax: {
                url: "getprocess/getallcustomersforselect.php",
                // url: "getprocess/getproductaccosupplier.php",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term, // search term
                    };
                },
                processResults: function (response) { //console.log(response)
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        $('#saleInformationForm').submit(function (event) {
            event.preventDefault();

            var validfrom = $('#fromdate').val();
            var validto = $('#todate').val();
            var customer = getElementValue('#selectCustomer');

            $.ajax({
                type: "POST",
                data: {
                    validfrom: validfrom,
                    validto: validto,
                    customer: customer
                },
                url: 'getprocess/getallcustomeroverpayments.php',
                success: function (result) {
                    $('#targetviewdetail').html(result);
                    $('#hideprintBtn').show();

                    if ($.fn.DataTable.isDataTable('#reportTable')) {
                        $('#reportTable').DataTable().destroy();
                    }

                    $('#reportTable').DataTable({
                        "dom": "<'row'<'col-sm-5'B><'col-sm-2'l><'col-sm-5'f>>" +
                            "<'row'<'col-sm-12'tr>>" +
                            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                        "buttons": [{
                                extend: 'csv',
                                className: 'btn btn-success btn-sm',
                                title: 'Everest Sale Report Information',
                                text: '<i class="fas fa-file-csv mr-2"></i> CSV'
                            },
                            {
                                extend: 'pdf',
                                className: 'btn btn-danger btn-sm',
                                title: 'Everest Sale Report Information',
                                text: '<i class="fas fa-file-pdf mr-2"></i> PDF'
                            },
                            {
                                extend: 'print',
                                title: 'Everest Sale Report Information',
                                className: 'btn btn-primary btn-sm',
                                text: '<i class="fas fa-print mr-2"></i> Print'
                            }
                        ]
                    });

                }
            });
        });

        $('#printBtn').click(function () {
            // var validfrom = encodeURIComponent($('#fromdate').val());
            // var validto = encodeURIComponent($('#todate').val());
            // var customer = encodeURIComponent(getElementValue('#selectCustomer'));

            var validfrom = encodeURIComponent($('#fromdate').val());
            var validto = encodeURIComponent($('#todate').val());
            var customer = encodeURIComponent(getElementValue('#selectCustomer'));

            $('#frame').html('');
            $('#frame').html('<iframe class="embed-responsive-item" frameborder="0"></iframe>');
            $('#printreport iframe').contents().find('body').html(
                "<img src='images/spinner.gif' class='img-fluid' style='margin-top:200px;margin-left:500px;' />"
            );

            var params =
                `?validfrom=${validfrom}&validto=${validto}&customer=${customer}`;
            var src = 'pdfprocess/customeroverpayreportpdf.php' + params;

            var width = $(this).attr('data-width') || 640;
            var height = $(this).attr('data-height') || 360;

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



        function getElementValue(id) {
            var element = $(id);
            if (element.length === 0) {
                return null;
            }
            return element.val();
        }

        function resetFields() {
            $('.search-dependent').hide();
        }

        function resetForm() {
            $('#saleInformationForm')[0].reset();
            resetFields();
            $('#searchType').val(0);
        }
    });
</script>