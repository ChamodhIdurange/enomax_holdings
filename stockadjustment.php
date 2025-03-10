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
                            <div class="page-header-icon"><i data-feather="git-merge"></i></div>
                            <span>Adjust Stock</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-5">
                                <h6 class="small font-weight-bold text-dark mb-3">Item Details</h6>
                                <div class="form-group">
                                    <label class="small font-weight-bold text-dark">Search Product*</label>
                                    <div class="input-group input-group-sm">
                                        <select class="form-control form-control-sm" style="width: 100%;"
                                            name="searchproduct" id="searchproduct">
                                            <option value="0">Select Product</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="small font-weight-bold text-dark">Item Code*</label>
                                    <input type="text" class="form-control form-control-sm" name="itemcode"
                                        id="itemcode" readonly>
                                </div>
                                <div class="form-group">
                                    <label class="small font-weight-bold text-dark">Item Category*</label>
                                    <input type="text" class="form-control form-control-sm" name="itemcategory"
                                        id="itemcategory" readonly>
                                </div>
                                <div class="form-group">
                                    <label class="small font-weight-bold text-dark">Common Name*</label>
                                    <input type="text" class="form-control form-control-sm" name="commonname"
                                        id="commonname" readonly>
                                </div>
                            </div>
                            <div class="col-7">
                                <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Available Qty</th>
                                            <th>Average Unit Cost</th>
                                            <th>Average Sale Cost</th>
                                            <th>Last Pur Cost</th>
                                            <th>Last Pur Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-5">
                                <form action="process/stockadjustmentprocess.php" method="post" autocomplete="off">

                                    <h6 class="small font-weight-bold text-dark mb-3">Adjustment</h6>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="small font-weight-bold text-dark">Adjust Type*</label>
                                                <div class="input-group input-group-sm" required>
                                                    <select class="form-control form-control-sm" style="width: 100%;"
                                                        name="adjustmenttype" id="adjustmenttype">
                                                        <option value="1" selected>(+) Add Stock</option>
                                                        <option value="2">(-) Deduct Stock</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Adjust Qty*</label>
                                            <input type="number" class="form-control form-control-sm" name="adjustqty"
                                                id="adjustqty" required>
                                        </div>
                                    </div>
                                    <div class="row" id="divunitprice">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Unit Price*</label>
                                            <input type="text" class="input-integer form-control form-control-sm"
                                                name="productunitprice" id="productunitprice">
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Retail Price*</label>
                                            <input type="number" class="form-control form-control-sm"
                                                name="productsaleprice" id="productsaleprice" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="small font-weight-bold text-dark">Remarks</label>
                                        <textarea class="form-control form-control-sm" type="text" id="remarks"
                                            name="remarks" required></textarea>
                                    </div>
                                    <div class="form-group mt-2">
                                        <input type="text" class="d-none" id="hiddenproductid" name="hiddenproductid">
                                        <button type="submit" id="submitBtn"
                                            class="btn btn-outline-primary btn-sm w-50 fa-pull-right"
                                            <?php if($addcheck==0){echo 'disabled';} ?>><i
                                                class="far fa-save"></i>&nbsp;Add</button>
                                    </div>
                                </form>
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
    $(document).ready(function () {
        $('#divunitprice').removeClass('d-none');
        $('#productunitprice').attr('required', true);
        $('#productsaleprice').attr('required', true);

        $("#searchproduct").select2({
            ajax: {
                url: "getprocess/getproductforselect2.php",
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

        $('#adjustmenttype').change(function () {
            var val = $(this).val()

            if (val == 1) {
                $('#divunitprice').removeClass('d-none');
                $('#productunitprice').attr('required', true);
                $('#productsaleprice').attr('required', true);

            } else {
                $('#divunitprice').addClass('d-none');
                $('#productunitprice').attr('required', false);
                $('#productsaleprice').attr('required', false);
            }
        })
        $('#searchproduct').change(function () {
            var productId = $(this).val();
            var productText = $(this).find(":selected").text();

            $('#hiddenproductid').val(productId);

            $.ajax({
                type: "POST",
                data: {
                    recordID: productId
                },
                url: 'getprocess/getproduct.php',
                success: function (result) { //alert(result);
                    $('#dataTable tbody').empty();
                    var obj = JSON.parse(result);

                    $('#itemcode').val(obj.productcode);
                    $('#itemcategory').val(obj.categoryname);
                    $('#commonname').val(obj.commonname);
                    $('#productsaleprice').val(obj.saleprice);

                }
            });

            $.ajax({
                type: "POST",
                data: {
                    recordID: productId
                },
                url: 'getprocess/getstockadjustmentdata.php',
                success: function (result) {
                    console.log(result);
                    var obj = JSON.parse(result);

                    $('#dataTable > tbody:last').append(
                        '<tr class="pointer"><td>' + productText + '</td><td>' + obj
                        .totqty +
                        '</td><td class="text-right">' + parseFloat(obj.avgunitprice)
                        .toFixed(2) +
                        '</td><td class="text-right">' + parseFloat(obj.avgsaleprice)
                        .toFixed(2) +
                        '</td><td class="text-right">' + parseFloat(obj.lastunitprice)
                        .toFixed(2) +
                        '</td><td>' + obj.lastdate + '</td></tr>'


                    );

                    $('#dataTable').DataTable();

                }
            });
        })

        $('.input-integer').on('input', function () {
            var inputValue = $(this).val().replace(/[^0-9.]/g, ''); 
            inputValue = inputValue.replace(/(\..*)\./g, '$1'); 

            if (inputValue === '' || inputValue === '0') {
                $(this).val('');
            } else {
                $(this).val(inputValue);
            }
        });

        $('.input-integer').on('blur', function () {
            var inputValue = $(this).val().trim();
            if (inputValue === '' || isNaN(inputValue)) {
                $(this).val('0');
            } else {
                $(this).val(parseFloat(inputValue));
            }
        });
    });
</script>
<?php include "include/footer.php"; ?>


