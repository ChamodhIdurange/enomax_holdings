<?php 
include "include/header.php";  
include "include/topnavbar.php"; 

$sqlreplist="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `tbl_user_type_idtbl_user_type`=7 AND `status`=1";
$resultreplist =$conn-> query($sqlreplist);
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
                            <span>Customer Outstanding Report</span>
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
                                            <label class="small font-weight-bold text-dark">Sales Rep*</label>
                                            <select type="text" class="form-control form-control-sm" name="replist[]"
                                                id="replist" required multiple>
                                                <option value="all">All</option>
                                                <?php if($resultreplist->num_rows > 0) {while ($row = $resultreplist-> fetch_assoc()) { ?>
                                                <option value="<?php echo $row['idtbl_employee'] ?>">
                                                    <?php echo $row['name'] ?></option>
                                                <?php }} ?>
                                            </select>
                                        </div>
                                        <div class="col-1">
                                        </div>
                                        <div class="col-3">
                                            <label class="small font-weight-bold text-dark">Start Date*</label>
                                            <div class="input-group input-group-sm mb-3">
                                                <input type="text" class="form-control dpd1a rounded-0" id="fromdate"
                                                    name="fromdate" value="<?php echo date('Y-m-d') ?>" required>
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
                                                <th>Product</th>
                                                <th>Color</th>
                                                <th>Category</th>
                                                <th>Group Category</th>
                                                <th>Size</th>
                                                <th>Retail price</th>
                                                <th class="text-center">Available Stock</th>
                                                <th>Total price</th>
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
        $("#replist").select2();

        $('.dpd1a').datepicker({
            uiLibrary: 'bootstrap4',
            autoclose: 'true',
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        });

        $("#replist").on("change", function () {
            var selectedValues = $(this).val();

            if (selectedValues && selectedValues.includes("all")) {
                selectedValues = $("#replist option[value!='all']").map(function () {
                    return this.value;
                }).get();

                $(this).val(selectedValues).trigger("change");
            }
        });

        $('#formSearchBtn').click(function () {
            if (!$("#searchform")[0].checkValidity()) {
                $("#hidesubmit").click();
            } else {
                var fromdate = $('#fromdate').val();
                var todate = $('#todate').val();
                var replist = $('#replist').val();

                $('#targetviewdetail').html(
                    '<div class="card border-0 shadow-none bg-transparent"><div class="card-body text-center"><img src="images/spinner.gif" alt="" srcset=""></div></div>'
                ).show();

                $.ajax({
                    type: "POST",
                    data: {
                        fromdate: fromdate,
                        todate: todate,
                        replist: replist
                    },
                    url: 'getprocess/getoutstandingsalesdata.php',
                    success: function (result) {
                        $('#targetviewdetail').html(result);
                        $('#hideprintBtn').show();
                    }
                });
            }
        });

        $('#printBtnStock').click(function () {
            print()

        });


        function print() {
            printJS({
                printable: 'targetviewdetail',
                type: 'html',
                // style: '@page { size: landscape; }',
                targetStyles: ['*']
            })
        }

    });
</script>
<?php include "include/footer.php"; ?>